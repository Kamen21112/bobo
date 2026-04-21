<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Нова Заявка') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if($cars->isEmpty())
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">Нямате добавени автомобили във вашия профил. Моля, първо добавете автомобил в секция "Моите Автомобили", за да можете да пуснете заявка.</span>
                        </div>
                        <a href="{{ route('my_cars.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Отиди към Добавяне на Автомобил
                        </a>
                    @else
                        <form action="{{ route('service_requests.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Изберете Автомобил <span class="text-red-500">*</span></label>
                                <select name="car_id" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="" disabled selected>Изберете една от вашите коли...</option>
                                    @foreach($cars as $car)
                                        <option value="{{ $car->id }}">{{ $car->make }} {{ $car->model }} ({{ $car->plate_number }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Описание на проблема <span class="text-red-500">*</span></label>
                                <textarea name="description" required rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline placeholder-gray-400" placeholder="Опишете какво не е наред с автомобила..."></textarea>
                            </div>

                            <div class="flex items-center justify-end">
                                <a href="{{ route('service_requests.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">Отказ</a>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Изпрати Заявка
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
