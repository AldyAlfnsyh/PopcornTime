<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PopcornTime</title>
    <Link rel='icon' href="{{ asset('storage/logo.webp') }}">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
</head>
<body class="bg-slate-900">
    <x-navbar></x-navbar>
    <main>
        <div class="px-26">
            {{$slot}}
        </div>
    </main>
    <x-footer></x-footer>
        <!-- Flowbite JS -->
        
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
</html>