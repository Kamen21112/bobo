<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Автосервиз Система</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-100 flex items-center justify-center min-h-screen">
    
    <div class="text-center">
        <h1 class="text-5xl font-bold text-gray-800 mb-8">
            Добре дошли в нашия Автосервиз
        </h1>
        <p class="text-gray-600 mb-8 text-lg">
            Управлявайте своите автомобили и ремонти лесно и удобно.
        </p>

        @if (Route::has('login'))
            <div class="space-x-4">
                @auth
                    {{-- Ако потребителят вече е влязъл --}}
                    <a href="{{ url('/cars') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded shadow-lg transition duration-300">
                        Към моите автомобили
                    </a>
                @else
                    {{-- Ако потребителят не е влязъл --}}
                    <a href="{{ route('login') }}" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 px-6 rounded shadow-lg transition duration-300">
                        Вход в системата
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-white hover:bg-gray-100 text-gray-800 font-bold py-3 px-6 rounded shadow-lg border border-gray-300 transition duration-300">
                            Регистрация
                        </a>
                    @endif
                @endauth
            </div>
        @endif
    </div>

</body>
</html>