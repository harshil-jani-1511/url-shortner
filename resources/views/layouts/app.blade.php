
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'URL Shortner') - URL Shortner</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            margin: 0;
            color: #1f2937;
        }

        .topbar {
            min-height: 70px;
            background: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
            border-bottom: 1px solid #e5e7eb;
        }

        .brand {
            font-size: 21px;
            font-weight: bold;
            color: #172554;
            text-decoration: none;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 22px;
        }

        .dashboard-link {
            color: #1f2937;
            text-decoration: none;
            font-size: 14px;
        }

        .dashboard-link:hover {
            color: #2563eb;
        }

        .logout-button {
            border: 0;
            background: #2563eb;
            color: #fff;
            padding: 9px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .logout-button:hover {
            background: #1d4ed8;
        }

        .content {
            padding: 30px;
        }

        .alert {
            padding: 13px 16px;
            border-radius: 7px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        .alert-error,
        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            padding: 13px 16px;
            border-radius: 7px;
            margin-bottom: 20px;
        }

        .card {
            background: #fff;
            padding: 24px;
            border-radius: 10px;
            margin-bottom: 24px;
            border: 1px solid #e5e7eb;
        }

        .card h2 {
            font-size: 18px;
            margin: 0 0 20px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            gap: 15px;
        }

        .page-header h1 {
            margin: 0 0 6px;
            font-size: 24px;
        }

        .page-header p {
            margin: 0;
            color: #64748b;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 7px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            box-sizing: border-box;
            padding: 11px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            border: 0;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th, td {
            padding: 13px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background: #f8fafc;
            font-weight: 600;
        }

        td a {
            color: #2563eb;
            overflow-wrap: anywhere;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .section-header h2 {
            margin: 0;
        }

        .section-footer {
            margin-top: 18px;
            text-align: right;
        }

        .section-footer a,
        .reset-link {
            color: #2563eb;
            text-decoration: none;
            font-size: 14px;
        }

        .empty-message {
            color: #64748b;
            font-size: 14px;
        }

        .filter-form {
            display: flex;
            align-items: end;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .filter-form label {
            display: block;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .filter-form select,
        .filter-form input {
            padding: 9px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
            min-width: 160px;
            width: auto;
        }

        .btn-download {
            background: #e2e8f0;
            color: #1f2937;
        }

        .btn-download:disabled {
            cursor: not-allowed;
            opacity: 0.6;
        }
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-header div {
            display: ruby;
            align-items: center;
        }
        .header-content-admin {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        @media (max-width: 768px) {
            .topbar {
                padding: 0 16px;
            }

            .brand {
                font-size: 18px;
            }

            .header-actions {
                gap: 14px;
            }

            .content {
                padding: 18px;
            }

            .page-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .card {
                padding: 16px;
            }
            .section-header {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <header class="topbar">
        <a href="{{ route('dashboard') }}" class="brand">
            URL Shortner
        </a>

        @auth
            <div class="header-actions">
                <a href="{{ route('dashboard') }}" class="dashboard-link">
                    Dashboard
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-button">
                        Logout
                    </button>
                </form>
            </div>
        @endauth
    </header>

    <main class="content">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('invitation_url'))
            <div class="alert alert-success">
                <strong>Invitation link generated:</strong>

                <div style="margin-top: 8px; overflow-wrap: anywhere;">
                    <a href="{{ session('invitation_url') }}" target="_blank">
                        {{ session('invitation_url') }}
                    </a>
                </div>

                <button
                    type="button"
                    onclick="copyInvitationLink(this, @js(session('invitation_url')))"
                    style="margin-top: 10px; padding: 7px 12px; cursor: pointer;"
                >
                    Copy Invitation Link
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

    </main>
<script>
    function copyInvitationLink(button, url) {
        navigator.clipboard.writeText(url).then(() => {
            button.textContent = 'Copied!';
            setTimeout(() => {
                button.textContent = 'Copy Invitation Link';
            }, 2000);
        }).catch(() => {
            alert('Unable to copy the invitation link.');
        });
    }
</script>

</body>
</html>
