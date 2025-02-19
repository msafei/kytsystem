@extends('templates.app')

@section('title', 'Add Flow KYT Report')

@section('content')
<div class="container mx-auto mt-6">
    <h1 class="text-2xl font-bold mb-4">Add Flow KYT Report</h1>

    @if ($errors->any())
    <div class="bg-red-500 text-white p-3 rounded mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('flow_kyt_reports.store') }}" method="POST">
        @csrf

        <!-- Flow Selection -->
        <div>
            <label>Flow</label>
            <select name="flow" class="border w-full p-2 rounded">
                <option value="" disabled selected>-- Select Flow --</option>
                <option value="1">CHECKED</option>
                <option value="2">REVIEWED</option>
                <option value="3">APPROVED1</option>
                <option value="4">APPROVED2</option>
            </select>
        </div>

        <!-- Position Selection -->
        <div>
            <label>Position</label>
            <select name="position_id" class="border w-full p-2 rounded">
                <option value="" disabled selected>-- Select Position --</option>
                @foreach($positions as $position)
                    <option value="{{ $position->id }}">{{ $position->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Save</button>
    </form>
</div>
@endsection
