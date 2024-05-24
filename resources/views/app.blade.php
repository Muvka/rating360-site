<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="page">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    @routes
    @viteReactRefresh
    @vite(['resources/css/app.scss', 'resources/js/app.tsx'])
    @inertiaHead
</head>
<body class="page__body">
@inertia
</body>
</html>
