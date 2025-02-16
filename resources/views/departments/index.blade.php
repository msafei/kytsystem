@extends('templates.app')

@section('title', 'Departments')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Departments List</h2>
    
    <a href="{{ route('departments.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">+ Add Department</a>

    <table class="table-auto w-full mt-4 border">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Name</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($departments as $department)
            <tr>
                <td class="border px-4 py-2">{{ $department->id }}</td>
                <td class="border px-4 py-2">{{ $department->name }}</td>
                <td class="border px-4 py-2">
                    <a href="{{ route('departments.edit', $department->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
                    <form action="{{ route('departments.delete', $department->id) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
