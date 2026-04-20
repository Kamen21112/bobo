<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Профил на клиент: {{ $client->first_name }} {{ $client->last_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Client Info Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Данни за контакт</h3>
                    <p><strong>Телефон:</strong> {{ $client->phone ?? 'Няма въведен' }}</p>
                    <p><strong>Имейл:</strong> {{ $client->email ?? 'Няма въведен' }}</p>
                    <p><strong>Бележки:</strong> {{ $client->notes ?? 'Няма' }}</p>
                </div>
            </div>

            {{-- Client Cars Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Притежавани автомобили</h3>
                    
                    @if($client->cars->isEmpty())
                        <p class="text-gray-500">Този клиент все още няма добавени автомобили.</p>
                    @else
                        <ul class="list-disc pl-5">
                            @foreach($client->cars as $car)
                                <li class="mb-2">
                                    <strong>{{ $car->make }} {{ $car->model }}</strong> 
                                    (Рег. номер: {{ $car->plate_number }})
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    {{-- Бутон за добавяне на кола към този клиент --}}
                    <div class="mt-6">
                        <a href="{{ route('cars.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            + Добави автомобил
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>