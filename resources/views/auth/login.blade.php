<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Peminjaman Ruang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-gray-200 rounded-xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="text-white p-8" style="background-color: #800000;">
            <div class="flex items-center justify-center">
                <div class="w-16 h-16 bg-red-200 rounded-2xl flex items-center justify-center mr-6 shadow-lg">
                    <i class="fas fa-sign-in-alt text-3xl text-red-800"></i>
                </div>
                <div class="text-center">
                    <h1 class="text-3xl font-bold mb-2">Masuk ke Akun</h1>
                    <p class="text-gray-300 text-lg">Sistem Peminjaman Ruang</p>
                </div>
            </div>
        </div>

        <!-- Form Login -->
        <div class="p-6 bg-gray-200">
            @if($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-medium mb-2">
                        <i class="fas fa-envelope mr-2"></i>Alamat Email
                    </label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-800 focus:border-red-800 transition duration-200"
                           placeholder="nama@email.com">
                    @error('email')
                        <div class="mt-1 text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-medium mb-2">
                        <i class="fas fa-lock mr-2"></i>Kata Sandi
                    </label>
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-800 focus:border-red-800 transition duration-200"
                           placeholder="Masukkan kata sandi">
                    @error('password')
                        <div class="mt-1 text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center mb-6">
                    <input type="checkbox"
                           id="remember"
                           name="remember"
                           class="h-4 w-4 text-red-800 focus:ring-red-800 border-gray-300 rounded">
                    <label for="remember" class="ml-2 text-sm text-gray-600">
                        Ingat saya
                    </label>
                </div>

                <!-- Tombol Submit -->
                <button type="submit"
                        class="w-full bg-gradient-to-r from-red-700 to-red-800 text-white py-3 rounded-lg font-medium hover:from-red-800 hover:to-red-900 transition duration-300 flex items-center justify-center">
                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                </button>

                <!-- Forgot Password -->
                @if (Route::has('password.request'))
                    <div class="mt-4 text-center">
                        <a href="{{ route('password.request') }}" class="text-sm text-red-800 hover:text-red-900">
                            Lupa kata sandi?
                        </a>
                    </div>
                @endif

                <!-- Register Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Belum memiliki akun?
                        <a href="{{ route('register') }}" class="font-medium text-red-800 hover:text-red-900">
                            Daftar di sini
                        </a>
                    </p>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t">
            <div class="text-center">
                <p class="text-xs text-gray-400">
                    © {{ date('Y') }} Telkom University. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
