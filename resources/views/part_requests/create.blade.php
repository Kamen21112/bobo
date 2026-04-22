<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-slate-700">Нова заявка за část</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <p class="text-sm text-slate-500 mb-6">Изберете съществуваща частта от склада и посочете необходимото количество. Администраторът ще обработи заявката.</p>

            <form action="{{ route('part_requests.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Изберете части <span class="text-red-400">*</span></label>
                    <select name="part_id" required class="w-full border border-slate-300 rounded-lg py-2 px-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <option value="" disabled selected>-- Изберете от склада --</option>
                        @foreach($parts as $part)
                            <option value="{{ $part->id }}" {{ old('part_id') == $part->id ? 'selected' : '' }}>
                                {{ $part->name }}
                                @if($part->part_number) ({{ $part->part_number }}) @endif
                                — {{ number_format($part->price, 2) }} лв.
                            </option>
                        @endforeach
                    </select>
                    @error('part_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Количество <span class="text-red-400">*</span></label>
                    <input type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}" required
                        class="w-full border border-slate-300 rounded-lg py-2 px-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    @error('quantity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Бележки</label>
                    <textarea name="notes" rows="3" placeholder="Допълнителна информация (незадължително)..."
                        class="w-full border border-slate-300 rounded-lg py-2 px-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-300">{{ old('notes') }}</textarea>
                    @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('part_requests.index') }}" class="text-slate-500 hover:text-slate-700 text-sm py-2 px-4 border border-slate-200 rounded-lg">Отказ</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 px-6 rounded-lg transition">
                        Изпрати заявката
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
