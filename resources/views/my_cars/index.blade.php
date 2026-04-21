<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Моите Автомобили') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="mb-4">
                        <a href="{{ route('my_cars.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Добави Нов Автомобил
                        </a>
                    </div>

                    @if($cars->isEmpty())
                        <p class="text-gray-500">Все още нямате добавени автомобили.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="py-2 px-4 border-b">Марка</th>
                                        <th class="py-2 px-4 border-b">Модел</th>
                                        <th class="py-2 px-4 border-b">Рег. номер</th>
                                        <th class="py-2 px-4 border-b">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cars as $car)
                                        <tr class="hover:bg-gray-50 text-center">
                                            <td class="py-2 px-4 border-b">{{ $car->make }}</td>
                                            <td class="py-2 px-4 border-b">{{ $car->model }}</td>
                                            <td class="py-2 px-4 border-b">{{ $car->plate_number }}</td>
                                            <td class="py-2 px-4 border-b text-sm">
                                                <form action="{{ route('my_cars.destroy', $car->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Сигурни ли сте, че искате да премахнете този автомобил от гаража си?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 font-bold underline">
                                                        Изтрий
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
