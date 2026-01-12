<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email Verification</title>
    <style>
        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f3f4f6;
            margin: 0;
        }
        .container {
            background-color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            max-width: 28rem;
            width: 100%;
            text-align: center;
        }
        h1 { font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem; }
        p { color: #4b5563; }
        .text-green-600 { color: #059669; }
        .text-red-600 { color: #dc2626; }
        .text-blue-600 { color: #2563eb; }
    </style>
</head>
<body>
    <div class="container">
        @if ($status === 'success') <h1 class="text-green-600">Verified!</h1>
        @elseif ($status === 'error') <h1 class="text-red-600">Error</h1>
        @else <h1 class="text-blue-600">Notice</h1>
        @endif
        <p>{{ $message }}</p>
    </div>
</body>
</html>