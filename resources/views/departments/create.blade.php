@extends('templates.app')

@section('title', 'Add Department')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white p-8 rounded-lg shadow-md w-2/3 mx-auto">
        <h2 class="text-2xl font-bold mb-6 text-center">Add Department</h2>

        <form method="POST" action="{{ route('departments.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700">Department Name</label>
                <input type="text" name="name" required class="w-full px-3 py-2 border rounded">
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded">Save Department</button>
        </form>
    </div>
</div>
@endsection
