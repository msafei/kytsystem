@extends('templates.app')

@section('title', 'Positions')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Positions List</h2>
    
    <a href="{{ route('positions.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">+ Add Position</a>

    <table class="table-auto w-full mt-4 border">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Name</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($positions as $position)
            <tr>
                <td class="border px-4 py-2">{{ $position->id }}</td>
                <td class="border px-4 py-2">{{ $position->name }}</td>
                <td class="border px-4 py-2">
                    <a href="{{ route('positions.edit', $position->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
                    <form action="{{ route('positions.delete', $position->id) }}" method="POST" class="inline-block">
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
