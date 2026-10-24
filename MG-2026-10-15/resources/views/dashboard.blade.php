@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="card">
    <h1>Welcome, {{ $user->name }}</h1>

    <p>
        Logged in as:
        <strong>{{ ucfirst($user->role) }}</strong>
    </p>

    @if ($user->isInstructor())
        <h2>Instructor Dashboard</h2>
        <p>Create assignments, upload instructions,
           and review student submissions.</p>

        <a class="btn"
           href="{{ route('assignments.create') }}">
            Create Assignment
        </a>

    @else
        <h2>Student Dashboard</h2>
        <p>View your assignments, read PDF instructions,
           and submit completed work.</p>
    @endif

    <a class="btn"
       href="{{ route('assignments.index') }}">
        View Assignments
    </a>

    <a class="btn"
       href="{{ route('submissions.index') }}">
        View Submissions
    </a>
</div>

@endsection
