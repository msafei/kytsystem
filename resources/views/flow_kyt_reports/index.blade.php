@extends('templates.app')

@section('title', 'Flow KYT Reports')

@section('content')
<div class="container mx-auto mt-6">
    <h1 class="text-2xl font-bold mb-4">Flow KYT Reports</h1>

    <a href="{{ route('flow_kyt_reports.create') }}" class="bg-green-500 text-white px-4 py-2 rounded mb-4 inline-block">Add New Flow</a>

    @if(session('success'))
    <div class="bg-green-500 text-white p-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <table class="w-full border">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">#</th>
                <th class="border px-4 py-2">Flow</th>
                <th class="border px-4 py-2">Position</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($flows as $index => $flow)
            <tr>
                <td class="border px-4 py-2">{{ $index + 1 }}</td>
                <td class="border px-4 py-2">
                    @if($flow->flow == 1) CHECKED
                    @elseif($flow->flow == 2) REVIEWED
                    @elseif($flow->flow == 3) APPROVED1
                    @elseif($flow->flow == 4) APPROVED2
                    @endif
                </td>
                <td class="border px-4 py-2">{{ $flow->position->name ?? '-' }}</td>
                <td class="border px-4 py-2">
                    <form action="{{ route('flow_kyt_reports.destroy', $flow->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-1 rounded">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
