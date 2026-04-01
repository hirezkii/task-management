@extends('layout')

@section('content')
    <div class="container" style="height: 100vh;">

        <!-- 🔥 TITLE LEBIH KE ATAS -->
        <div class="pt-5 text-center">
            <h2 class="fw-bold">Task Management System</h2>
        </div>

        <!-- 🔥 CARD TETAP DI TENGAH -->
        <div class="d-flex justify-content-center align-items-center" style="height: 80%;">

            <div class="card shadow-sm p-4" style="width: 350px;">

                <h5 class="text-center mb-3">Login</h5>

                <div id="error" class="alert alert-danger d-none"></div>

                <input id="email" class="form-control mb-2" placeholder="Email">
                <input id="password" type="password" class="form-control mb-3" placeholder="Password">

                <button onclick="login()" class="btn btn-primary w-100">
                    Login
                </button>

            </div>

        </div>

    </div>

    <script>
        async function login() {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            const errorEl = document.getElementById('error');
            errorEl.classList.add('d-none');

            if (!email || !password) {
                errorEl.classList.remove('d-none');
                errorEl.innerText = 'Email dan password wajib diisi';
                return;
            }

            try {
                const res = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email,
                        password
                    })
                });

                const data = await res.json();

                if (!res.ok) {
                    throw data;
                }

                localStorage.setItem('token', data.token);
                window.location.href = '/dashboard';

            } catch (err) {
                errorEl.classList.remove('d-none');
                errorEl.innerText = err.message || 'Login gagal';
            }
        }
    </script>
@endsection
