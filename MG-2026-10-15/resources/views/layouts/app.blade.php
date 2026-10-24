<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Assignment System')</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f3f4f6;
            color: #1f2937;
        }

        header {
            background: #1e293b;
            color: white;
            padding: 18px 30px;
        }

        nav {
            display: flex;
            gap: 18px;
            align-items: center;
            flex-wrap: wrap;
        }

        nav a {
            color: white;
            text-decoration: none;
        }

        main {
            max-width: 1050px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 1px 5px #0001;
        }

        input, select {
            display: block;
            padding: 10px;
            margin: 8px 0 18px;
            width: 100%;
            max-width: 500px;
            box-sizing: border-box;
        }

        button, .btn {
            display: inline-block;
            padding: 10px 16px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        .success {
            background: #dcfce7;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 16px;
        }

        .error {
            background: #fee2e2;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 16px;
        }

        iframe {
            width: 100%;
            height: 550px;
            border: 1px solid #d1d5db;
        }

        .muted {
            color: #64748b;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <strong>Assignment System</strong>

            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('assignments.index') }}">
                Assignments
            </a>
            <a href="{{ route('submissions.index') }}">
                Submissions
            </a>

            <span style="margin-left:auto">
                {{ auth()->user()->name }}
                ({{ auth()->user()->role }})
            </span>

            <form method="POST"
                  action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </nav>
    </header>

    <main>
        @if (session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="error">
                <strong>Please correct:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
