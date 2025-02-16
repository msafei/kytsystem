<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-blue-500 p-4 text-white flex justify-between items-center">
        <!-- Kiri: Logo dan Master Data -->
        <div class="flex items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-10 mr-3">
            <div class="relative group">
                <button class="hover:bg-blue-700 px-4 py-2 rounded">Master Data</button>
                <div class="absolute hidden group-hover:block bg-white text-black rounded shadow-md w-40">
                    <a href="{{ route('users.index') }}" class="block px-4 py-2 hover:bg-gray-200">Users</a>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-200">Employee</a>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-200">Company</a>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-200">Department</a>
                </div>
            </div>
        </div>

        <!-- Kanan: Username & Dropdown -->
        <div class="relative group">
            <button class="hover:bg-blue-700 px-4 py-2 rounded">
                {{ auth()->user()->username }}
            </button>
            <div class="absolute hidden group-hover:block bg-white text-black rounded shadow-md w-40 right-0">
                <a href="#" class="block px-4 py-2 hover:bg-gray-200">Profil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-200">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Konten Halaman -->
    <div class="container mx-auto mt-8">
        @yield('content')
    </div>

</body>
</html>
