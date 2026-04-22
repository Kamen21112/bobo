<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-slate-700">Нова заявка за сервиз</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            @if($cars->isEmpty())
                <div class="bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-lg mb-4 text-sm">
                    Нямате добавени автомобили. Моля, първо добавете автомобил в „Моите Коли".
                </div>
                <a href="{{ route('my_cars.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition inline-block">
                    Добави автомобил
                </a>
            @else
                <form action="{{ route('service_requests.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Автомобил <span class="text-red-400">*</span></label>
                        <select name="car_id" required class="w-full border border-slate-300 rounded-lg py-2 px-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <option value="" disabled selected>Изберете вашата кола...</option>
                            @foreach($cars as $car)
                                <option value="{{ $car->id }}">{{ $car->make }} {{ $car->model }} ({{ $car->plate_number }})</option>
                            @endforeach
                        </select>
                        @error('car_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Описание на проблема <span class="text-red-400">*</span></label>
                        <textarea name="description" required rows="4"
                            placeholder="Опишете какво не е наред с автомобила..."
                            class="w-full border border-slate-300 rounded-lg py-2 px-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-300">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('service_requests.index') }}" class="text-slate-500 hover:text-slate-700 text-sm py-2 px-4 border border-slate-200 rounded-lg">Отказ</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 px-6 rounded-lg transition">
                            Изпрати заявка
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
