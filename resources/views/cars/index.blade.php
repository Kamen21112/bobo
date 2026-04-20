<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Моите автомобили
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Бутон за добавяне --}}
                    <div class="mb-4">
                        <a href="{{ route('cars.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Добави автомобил
                        </a>
                    </div>

                    {{-- Списък с коли --}}
                    @if($cars->isEmpty())
                        <p>Все още нямате добавени автомобили.</p>
                    @else
                        <ul class="list-disc pl-5">
                            @foreach($cars as $car)
                                <li class="mb-4 p-4 border rounded shadow-sm flex items-center justify-between bg-gray-50">
                                    <div>
                                        <strong>{{ $car->make }} {{ $car->model }}</strong> 
                                        <span class="text-gray-600">(Рег. номер: {{ $car->plate_number }})</span>
                                    </div>
                                    
                                    <div class="flex items-center space-x-4">
                                        {{-- Бутон за редактиране --}}
                                        <a href="{{ route('cars.edit', $car->id) }}" class="text-sm text-yellow-600 hover:text-yellow-800 underline">
                                            Редактирай
                                        </a>

                                        {{-- Форма за изтриване --}}
                                        <form method="POST" action="{{ route('cars.destroy', $car->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-500 hover:text-red-700 underline" onclick="return confirm('Сигурни ли сте, че искате да изтриете този автомобил?')">
                                                Изтрий
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>