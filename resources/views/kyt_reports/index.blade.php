@extends('templates.app')

@section('title', 'KYT Reports')

@section('content')
<div class="container mx-auto mt-6">
    <h1 class="text-2xl font-bold mb-4">KYT Reports</h1>
    <a href="{{ route('kyt_reports.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">+ Add Report</a>

    <table class="w-full mt-4 border">
        <thead>
            <tr>
                <th>ID</th>
                <th>Company</th>
                <th>Project</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kytReports as $report)
            <tr>
                <td>{{ $report->id }}</td>
                <td>{{ $report->company->name }}</td>
                <td>{{ $report->projectTitle }}</td>
                <td>
                    <a href="{{ route('kyt_reports.edit', $report) }}" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
                    <form action="{{ route('kyt_reports.destroy', $report) }}" method="POST" class="inline">
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
