<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - VEKTOR</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .split-bg {
            background: linear-gradient(to right, #f25920 0%, #f25920 50%, #f9fafb 50%, #f9fafb 100%);
        }

        .login-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .input-field {
            padding-left: 45px;
        }

        .input-wrapper {
            position: relative;
        }

        .logo-container {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .btn-login {
            background: linear-gradient(135deg, #f25920 100%);
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>

<body class="h-screen flex justify-center items-center split-bg">
    <div class="login-card p-20 rounded-2xl shadow-2xl w-full max-w-md mx-4 bg-white">
        <!-- Logo Section -->
        <div class="flex flex-col items-center mb-8 logo-container">
            <p class="text-2xl font-bold">VEKTOR</p>
            <p class="text-gray-500 text-sm">Sistem Peminjaman Aset</p>
        </div>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="mb-5">
                <!-- <label class="block text-gray-700 font-medium mb-2">
                    <i class="fas fa-envelope mr-2 text-indigo-500"></i>Email
                </label> -->
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" name="email"
                        class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 input-field focus:outline-none focus:border-indigo-500"
                        placeholder="Masukkan email" required value="{{ old('email') }}">
                </div>
                @error('email')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <!-- <label class="block text-gray-700 font-medium mb-2">
                    <i class="fas fa-lock mr-2 text-indigo-500"></i>Password
                </label> -->
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password"
                        class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 input-field focus:outline-none focus:border-indigo-500"
                        placeholder="Masukkan password" required>
                </div>
                @error('password')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <label class="inline-flex items-center mb-4">
                <input type="checkbox" name="remember" class="mr-2">
                <span class="text-gray-700 text-sm">Remember Me</span>
            </label>

            <button 
                type="submit" 
                class="w-full btn-login text-white py-3 rounded-lg font-semibold text-lg focus:outline-none focus:ring-4 focus:ring-indigo-300">
                Masuk
            </button>
        </form>
    </div>

    <script>
        setTimeout(() => {
            const msg = document.getElementById('flashMessage');
            if (msg) msg.style.display = 'none';
        }, 3000);
    </script>
</body>

</html>