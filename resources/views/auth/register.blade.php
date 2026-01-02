<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Sistem Peminjaman Ruang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full bg-gray-200 rounded-xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="text-white p-8" style="background-color: #7F1D1D;">
            <div class="flex items-center justify-center">
                <div class="w-16 h-16 bg-gray-300 rounded-2xl flex items-center justify-center mr-6 shadow-lg">
                    <i class="fas fa-user-plus text-3xl text-gray-800"></i>
                </div>
                <div class="text-center">
                    <h1 class="text-3xl font-bold mb-2">Pendaftaran Akun Baru</h1>
                    <p class="text-gray-300 text-lg">Sistem Peminjaman Ruang</p>
                </div>
            </div>
        </div>

        <!-- Form Registrasi -->
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

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Grid untuk form -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom 1 -->
                    <div>
                        <!-- Nama Lengkap -->
                        <div class="mb-4">
                            <label for="nama_lengkap" class="block text-gray-700 text-sm font-medium mb-2">
                                <i class="fas fa-user mr-2"></i>Nama Lengkap
                            </label>
                            <input type="text" 
                                   id="nama_lengkap" 
                                   name="nama_lengkap" 
                                   value="{{ old('nama_lengkap') }}" 
                                   required 
                                   autofocus
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                   placeholder="Masukkan nama lengkap">
                        </div>

                        <!-- NIM -->
                        <div class="mb-4">
                            <label for="nim" class="block text-gray-700 text-sm font-medium mb-2">
                                <i class="fas fa-id-card mr-2"></i>NIM
                            </label>
                            <input type="text"
                                   id="nim"
                                   name="nim"
                                   value="{{ old('nim') }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                   placeholder="Masukkan NIM">
                        </div>

                        <!-- Program Studi -->
                        <div class="mb-4">
                            <label for="prodi" class="block text-gray-700 text-sm font-medium mb-2">
                                <i class="fas fa-graduation-cap mr-2"></i>Program Studi
                            </label>
                            <select id="prodi"
                                    name="prodi"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                <option value="">Pilih Program Studi</option>
                                <option value="Teknik Elektro" {{ old('prodi') == 'Teknik Elektro' ? 'selected' : '' }}>Teknik Elektro</option>
                                <option value="Teknik Telekomunikasi" {{ old('prodi') == 'Teknik Telekomunikasi' ? 'selected' : '' }}>Teknik Telekomunikasi</option>
                                <option value="Teknik Komputer" {{ old('prodi') == 'Teknik Komputer' ? 'selected' : '' }}>Teknik Komputer</option>
                                <option value="Teknik Industri" {{ old('prodi') == 'Teknik Industri' ? 'selected' : '' }}>Teknik Industri</option>
                                <option value="Sistem Informasi" {{ old('prodi') == 'Sistem Informasi' ? 'selected' : '' }}>Sistem Informasi</option>
                                <option value="Teknik Logistik" {{ old('prodi') == 'Teknik Logistik' ? 'selected' : '' }}>Teknik Logistik</option>
                                <option value="Teknik Informatika" {{ old('prodi') == 'Teknik Informatika' ? 'selected' : '' }}>Teknik Informatika</option>
                                <option value="Rekayasa Perangkat Lunak" {{ old('prodi') == 'Rekayasa Perangkat Lunak' ? 'selected' : '' }}>Rekayasa Perangkat Lunak</option>
                                <option value="Teknologi Informasi" {{ old('prodi') == 'Teknologi Informasi' ? 'selected' : '' }}>Teknologi Informasi</option>
                                <option value="Sains Data" {{ old('prodi') == 'Sains Data' ? 'selected' : '' }}>Sains Data</option>
                                <option value="Bisnis Digital" {{ old('prodi') == 'Bisnis Digital' ? 'selected' : '' }}>Bisnis Digital</option>
                            </select>
                        </div>
                    </div>

                    <!-- Kolom 2 -->
                    <div>
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
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                   placeholder="nama@email.com">
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="block text-gray-700 text-sm font-medium mb-2">
                                <i class="fas fa-lock mr-2"></i>Kata Sandi
                            </label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                   placeholder="Minimal 8 karakter">
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-gray-700 text-sm font-medium mb-2">
                                <i class="fas fa-lock mr-2"></i>Konfirmasi Kata Sandi
                            </label>
                            <input type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                   placeholder="Ulangi kata sandi">
                        </div>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-center mb-6">
                    <input type="checkbox"
                           id="terms"
                           name="terms"
                           required
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="terms" class="ml-2 text-sm text-gray-600">
                        Saya menyetujui
                        <a href="#" class="text-blue-600 hover:text-blue-700">syarat dan ketentuan</a>
                        yang berlaku
                    </label>
                </div>

                <!-- Tombol Submit -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('login') }}"
                       class="bg-gray-200 text-gray-800 py-3 rounded-lg font-medium hover:bg-gray-300 transition duration-300 flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Login
                    </a>

                    <button type="submit"
                            class="bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 rounded-lg font-medium hover:from-blue-600 hover:to-blue-700 transition duration-300 flex items-center justify-center">
                        <i class="fas fa-user-plus mr-2"></i>Daftar Akun
                    </button>
                </div>

                <!-- Info Login -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Sudah memiliki akun?
                        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-700">
                            Masuk di sini
                        </a>
                    </p>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t">
            <div class="text-center">
                <p class="text-xs text-gray-500 mb-1">
                    Dengan mendaftar, Anda menyetujui penggunaan data sesuai dengan
                    <a href="#" class="text-blue-600 hover:text-blue-700">kebijakan privasi</a>
                    kami.
                </p>
                <p class="text-xs text-gray-400">
                    © {{ date('Y') }} Telkom University. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
