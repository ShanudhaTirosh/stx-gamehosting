<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $code }} | {{ $title }} - STX Enterprises</title>
    <style>
        body {
            margin: 0;
            background-color: #080B11;
            color: #F1F5F9;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1.5rem;
            box-sizing: border-box;
        }
        .error-card {
            background-color: #0D121D;
            border: 1px solid #1E2025;
            border-radius: 1rem;
            padding: 2.5rem;
            max-width: 32rem;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .error-badge {
            display: inline-block;
            font-family: monospace;
            font-weight: 700;
            font-size: 2.5rem;
            color: #C5162D;
            margin-bottom: 0.5rem;
        }
        .error-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #FFFFFF;
            margin: 0 0 0.75rem 0;
        }
        .error-subtitle {
            font-size: 0.875rem;
            color: #94A3B8;
            line-height: 1.5;
            margin: 0 0 1.5rem 0;
        }
        .error-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-decoration: none;
            transition: all 0.15s ease-in-out;
        }
        .btn-primary {
            background-color: #C5162D;
            color: #FFFFFF;
        }
        .btn-primary:hover {
            background-color: #A31224;
        }
        .btn-secondary {
            background-color: #181A20;
            color: #D1D5DB;
            border: 1px solid #262830;
        }
        .btn-secondary:hover {
            background-color: #252830;
            color: #FFFFFF;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-badge">{{ $code }}</div>
        <h1 class="error-title">{{ $title }}</h1>
        <p class="error-subtitle">
            {{ $subtitle instanceof \Closure ? $subtitle() : ($subtitle ?? 'An unexpected error occurred while processing your request.') }}
        </p>
        <div class="error-actions">
            <a href="/" class="btn btn-secondary">Storefront</a>
            <a href="/app" class="btn btn-primary">Client Portal</a>
        </div>
    </div>
</body>
</html>
