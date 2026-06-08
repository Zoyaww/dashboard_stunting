<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebGIS - Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
        }

        :root {
            --bg-primary: #ffffff;
            --text-primary: #2c3e50;
            --text-secondary: #6c757d;
            --border-color: #e9ecef;
            --accent-dark: #495057;
            --accent-light: #e9ecef;
        }

        html.dark-mode {
            --bg-primary: #1a1a1a;
            --text-primary: #e9ecef;
            --text-secondary: #b0b0b0;
            --border-color: #404040;
            --accent-dark: #e9ecef;
            --accent-light: #404040;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            transition: background 0.3s ease;
        }

        body.dark-mode {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        }

        .login-container {
            background: var(--bg-primary);
            padding: 3rem 2rem;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            transition: all 0.3s ease;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h1 {
            font-size: 2rem;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .login-header p {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
            font-weight: 500;
            font-size: 0.95rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            background: var(--bg-primary);
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group input::placeholder {
            color: var(--text-secondary);
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 0.75rem 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border: 1px solid #f5c6cb;
        }

        .error-list {
            list-style: none;
            padding-left: 0;
        }

        .error-list li {
            margin-bottom: 0.5rem;
        }

        .error-list li:last-child {
            margin-bottom: 0;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 0.75rem 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border: 1px solid #c3e6cb;
        }

        .btn-login {
            width: 100%;
            padding: 0.75rem 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .demo-credentials {
            background: var(--accent-light);
            padding: 1rem;
            border-radius: 4px;
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .demo-credentials strong {
            color: var(--text-primary);
        }

        .demo-item {
            margin-bottom: 0.5rem;
        }

        .demo-item:last-child {
            margin-bottom: 0;
        }

        .theme-toggle-login {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background: none;
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            cursor: pointer;
            padding: 0.5rem 0.75rem;
            border-radius: 4px;
            font-size: 1.2rem;
            transition: all 0.2s ease;
        }

        .theme-toggle-login:hover {
            background-color: var(--accent-light);
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }

            .login-header h1 {
                font-size: 1.5rem;
            }

            .theme-toggle-login {
                top: 1rem;
                right: 1rem;
            }
        }
    </style>
</head>
<body>
    <button class="theme-toggle-login" id="themeToggle" title="Toggle dark mode">
        <span id="themeIcon">🌙</span>
    </button>

    <div class="login-container">
        <div class="login-header">
            <h1>WebGIS</h1>
            <p>Sistem Informasi Geografis</p>
        </div>

        @if ($errors->any())
            <div class="error-message">
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="error-message">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="username">Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    placeholder="Masukkan username"
                    value="{{ old('username') }}"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Masukkan password"
                    required
                >
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>

        <div class="demo-credentials">
            <strong>📝 Akun Demo:</strong>
            <div class="demo-item">Username: <strong>admin</strong> | Password: <strong>admin123</strong></div>
            <div class="demo-item">Username: <strong>user</strong> | Password: <strong>user123</strong></div>
        </div>
    </div>

    <script>
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const htmlElement = document.documentElement;

        function initTheme() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            if (savedTheme) {
                if (savedTheme === 'dark') {
                    htmlElement.classList.add('dark-mode');
                    themeIcon.textContent = '☀️';
                } else {
                    htmlElement.classList.remove('dark-mode');
                    themeIcon.textContent = '🌙';
                }
            } else if (prefersDark) {
                htmlElement.classList.add('dark-mode');
                themeIcon.textContent = '☀️';
            } else {
                htmlElement.classList.remove('dark-mode');
                themeIcon.textContent = '🌙';
            }
        }

        themeToggle.addEventListener('click', () => {
            htmlElement.classList.toggle('dark-mode');
            const isDarkMode = htmlElement.classList.contains('dark-mode');
            localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
            themeIcon.textContent = isDarkMode ? '☀️' : '🌙';
        });

        initTheme();
    </script>
</body>
</html>
