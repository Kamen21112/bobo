<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Редактиране на автомобил: {{ $car->make }} {{ $car->model }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Форма за редактиране --}}
                    <form method="POST" action="{{ route('cars.update', $car->id) }}">
                        @csrf
                        @method('PUT') {{-- Задължително за редакция в Laravel --}}

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="make">Марка</label>
                            <input type="text" name="make" id="make" value="{{ old('make', $car->make) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            {{-- Показване на грешка --}}
                            @error('make')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="model">Модел</label>
                            <input type="text" name="model" id="model" value="{{ old('model', $car->model) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            {{-- Показване на грешка --}}
                            @error('model')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="plate_number">Регистрационен номер</label>
                            <input type="text" name="plate_number" id="plate_number" value="{{ old('plate_number', $car->plate_number) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            {{-- Показване на грешка --}}
                            @error('plate_number')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="vin">VIN номер на рама</label>
                            <input type="text" name="vin" id="vin" value="{{ old('vin', $car->vin) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            {{-- Показване на грешка --}}
                            @error('vin')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Запази промените
                            </button>
                            <a href="{{ route('cars.index') }}" class="text-gray-500 hover:text-gray-800 underline">
                                Отказ
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>