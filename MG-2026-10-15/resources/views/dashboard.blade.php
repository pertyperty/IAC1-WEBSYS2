<h1>Welcome, {{ $user->name }}</h1>
<p>Role: {{ $user->role }}</p>

@if ($user->isInstructor())
    <h2>Instructor Dashboard</h2>
    <p>Manage assignments and submissions.</p>
@else
    <h2>Student Dashboard</h2>
    <p>View assignments and submit work.</p>
@endif

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>