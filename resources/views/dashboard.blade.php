@extends('layout')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>My Tasks</h3>
        <button onclick="logout()" class="btn btn-outline-danger btn-sm">Logout</button>
    </div>

    <div id="error" class="alert alert-danger d-none"></div>

    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#taskModal">
        Add Task
    </button>

    <!-- ✅ TABLE (PINDAH KE LUAR MODAL) -->
    <table class="table table-striped table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Task Name</th>
                <th>Status</th>
                <th width="120">Action</th>
            </tr>
        </thead>
        <tbody id="taskTable"></tbody>
    </table>

    <!-- ✅ LOADING -->
    <div id="loading" class="text-center d-none">
        <div class="spinner-border"></div>
    </div>

    <!-- ✅ MODAL -->
    <div class="modal fade" id="taskModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Add Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input id="modalTitle" class="form-control" placeholder="Task title">
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button onclick="submitTask()" class="btn btn-primary">Save</button>
                </div>

            </div>
        </div>
    </div>

    <script>
        const token = localStorage.getItem('token');
        if (!token) window.location.href = '/';

        async function fetchTasks() {
            try {
                setLoading(true);

                const res = await fetch('/api/tasks', {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                const tasks = await res.json();
                const table = document.getElementById('taskTable');
                table.innerHTML = '';

                if (tasks.length === 0) {
                    table.innerHTML = `
                <tr>
                    <td colspan="3" class="text-center text-muted">
                        No tasks yet
                    </td>
                </tr>
            `;
                    return;
                }

                tasks.forEach(task => {
                    const badge = task.status === 'done' ?
                        '<span class="badge bg-success">Done</span>' :
                        '<span class="badge bg-warning text-dark">Pending</span>';

                    table.innerHTML += `
                <tr>
                    <td>${task.title}</td>
                    <td>${badge}</td>
                    <td>
                        <button onclick="toggle(${task.id}, '${task.status}')" 
                            class="btn btn-sm btn-outline-primary">
                            Toggle
                        </button>
                    </td>
                </tr>
            `;
                });

            } catch (err) {
                showError(err);
            } finally {
                setLoading(false);
            }
        }

        async function submitTask() {
            const title = document.getElementById('modalTitle').value;

            if (!title) {
                alert('Title wajib diisi');
                return;
            }

            try {
                setLoading(true);

                await fetch('/api/tasks', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        Authorization: `Bearer ${token}`
                    },
                    body: JSON.stringify({
                        title
                    })
                });

                document.getElementById('modalTitle').value = '';

                const modal = bootstrap.Modal.getInstance(document.getElementById('taskModal'));
                modal.hide();

                fetchTasks();

            } catch (err) {
                showError(err);
            } finally {
                setLoading(false);
            }
        }

        async function toggle(id, status) {
            try {
                setLoading(true);

                await fetch(`/api/tasks/${id}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        Authorization: `Bearer ${token}`
                    },
                    body: JSON.stringify({
                        status: status === 'pending' ? 'done' : 'pending'
                    })
                });

                fetchTasks();

            } catch (err) {
                showError(err);
            } finally {
                setLoading(false);
            }
        }

        function showError(err) {
            const el = document.getElementById('error');
            el.classList.remove('d-none');
            el.innerText = 'Something went wrong';
        }

        function logout() {
            localStorage.removeItem('token');
            window.location.href = '/';
        }

        function setLoading(state) {
            const el = document.getElementById('loading');
            state ? el.classList.remove('d-none') : el.classList.add('d-none');
        }

        fetchTasks();
    </script>
@endsection
