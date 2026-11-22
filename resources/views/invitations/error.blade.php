
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation Unavailable</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">

    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md text-center">

        <h1 class="text-2xl font-bold text-gray-800 mb-3">
            Invitation Unavailable
        </h1>

        <p class="text-gray-600 mb-6">
            {{ $message }}
        </p>

        <a href="{{ route('login') }}"
           class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">
            Go to Login
        </a>

    </div>

</body>
</html>
