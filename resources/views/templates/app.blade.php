<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Navbar hanya muncul jika user sudah login -->
    @auth
    <nav class="bg-white p-4 text-gray-900 flex justify-between items-center shadow-md">
        <!-- Kiri: Logo SVG dan Master Data -->
        <div class="flex items-center">
            <!-- Gunakan file SVG -->
            <img src="{{ asset('images/logo.png') }}" alt="KYT System Logo" class="h-10 w-auto mr-3">

            <!-- Dropdown Master Data -->
            <div class="relative group">
                <button class="hover:bg-gray-200 px-4 py-2 rounded">Master Data</button>
                <div class="absolute hidden group-hover:block bg-white text-black rounded shadow-md w-48">
                    <a href="{{ route('users.index') }}" class="block px-4 py-2 hover:bg-gray-200">Users</a>
                    <a href="{{ route('employees.index') }}" class="block px-4 py-2 hover:bg-gray-200">Employees</a>
                    <a href="{{ route('companies.index') }}" class="block px-4 py-2 hover:bg-gray-200">Companies</a>
                    <a href="{{ route('departments.index') }}" class="block px-4 py-2 hover:bg-gray-200">Departments</a>
                    <a href="{{ route('positions.index') }}" class="block px-4 py-2 hover:bg-gray-200">Position</a>
                    <a href="{{ route('kyt_reports.index') }}" class="block px-4 py-2 hover:bg-gray-200">KYT</a>
                </div>
            </div>
        </div>

        <!-- Kanan: Username & Dropdown -->
        <div class="relative group">
            <button class="hover:bg-gray-200 px-4 py-2 rounded">
                {{ auth()->user()->username }}
            </button>
            <div class="absolute hidden group-hover:block bg-white text-black rounded shadow-md w-40 right-0">
                <a href="#" class="block px-4 py-2 hover:bg-gray-200">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-200">Logout</button>
                </form>
            </div>
        </div>
    </nav>
    @endauth

    <!-- Konten Halaman -->
    <div class="container mx-auto mt-8">
        @yield('content')
    </div>

</body>
</html>
