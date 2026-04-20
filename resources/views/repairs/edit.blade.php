<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Редактиране на ремонт
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Форма за редактиране --}}
                    <form method="POST" action="{{ route('repairs.update', $repair->id) }}">
                        @csrf
                        @method('PUT') {{-- Указваме на Laravel, че това е ъпдейт --}}

                        {{-- Избор на автомобил --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="car_id">Избери автомобил</label>
                            <select name="car_id" id="car_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                @foreach($cars as $car)
                                    <option value="{{ $car->id }}" {{ old('car_id', $repair->car_id) == $car->id ? 'selected' : '' }}>
                                        {{ $car->make }} {{ $car->model }} ({{ $car->plate_number }})
                                    </option>
                                @endforeach
                            </select>
                            {{-- Показване на грешка --}}
                            @error('car_id')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Заглавие --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="title">Кратко заглавие</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $repair->title) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            {{-- Показване на грешка --}}
                            @error('title')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Описание --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Подробно описание</label>
                            <textarea name="description" id="description" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description', $repair->description) }}</textarea>
                            {{-- Показване на грешка --}}
                            @error('description')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Цена --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="price">Цена (лв.)</label>
                            <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $repair->price) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            {{-- Показване на грешка --}}
                            @error('price')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Статус --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="status">Статус на ремонта</label>
                            <select name="status" id="status" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="pending" {{ old('status', $repair->status) == 'pending' ? 'selected' : '' }}>Чакащ</option>
                                <option value="in_progress" {{ old('status', $repair->status) == 'in_progress' ? 'selected' : '' }}>В процес на работа</option>
                                <option value="completed" {{ old('status', $repair->status) == 'completed' ? 'selected' : '' }}>Завършен</option>
                            </select>
                            {{-- Показване на грешка --}}
                            @error('status')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                Запази промените
                            </button>
                            <a href="{{ route('repairs.index') }}" class="text-gray-500 hover:text-gray-800 underline">
                                Отказ
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>