
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accept Invitation</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: #f1f5f9;
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        .invitation-card {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            padding: 36px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .invitation-card h1 {
            margin: 0 0 10px;
            color: #1e293b;
            font-size: 25px;
        }

        .description {
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #334155;
            font-size: 14px;
            font-weight: 600;
        }

        .form-group input {
            display: block;
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
        }

        .form-group input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-group input[readonly] {
            background: #f1f5f9;
            color: #64748b;
        }

        .error-message {
            color: #dc2626;
            font-size: 12px;
            margin-top: 6px;
        }

        .submit-button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 6px;
            background: #4f46e5;
            color: #ffffff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
        }

        .submit-button:hover {
            background: #4338ca;
        }

        @media (max-width: 480px) {
            .invitation-card {
                padding: 26px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="invitation-card">
        <h1>Accept Your Invitation</h1>

        <p class="description">
            Complete your details to create your Client Admin account.
        </p>

        <form method="POST" action="{{ route('invitations.accept.store', $token) }}">
            @csrf

            <div class="form-group">
                <label for="name">Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $invitation->name) }}"
                    placeholder="Enter your full name"
                    required
                >
                @error('name')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    value="{{ $invitation->email }}"
                    readonly
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Create a password"
                    required
                >
                @error('password')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Confirm your password"
                    required
                >
            </div>

            <button type="submit" class="submit-button">
                Accept Invitation
            </button>
        </form>
    </div>
</body>
</html>
