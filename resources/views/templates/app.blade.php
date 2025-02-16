<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-500 p-4 text-white flex justify-between">
        <h1 class="text-lg font-bold">KYTsystem</h1>
        <div>
            @auth
                <a href="{{ route('add-user') }}" class="mr-4">Add Users</a>
                <span>{{ auth()->user()->username }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            @endauth
        </div>
    </nav>

    <div class="container mx-auto mt-8">
        @yield('content')
    </div>
</body>
</html>
