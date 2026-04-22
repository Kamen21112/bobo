<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-slate-700">Управление на заявка #{{ $partRequest->id }}</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">

            {{-- Инфо за заявката --}}
            <div class="bg-slate-50 rounded-lg p-4 mb-6 text-sm text-slate-600 space-y-1">
                <div><span class="font-medium text-slate-700">Механик:</span> {{ $partRequest->mechanic->name ?? '—' }}</div>
                <div><span class="font-medium text-slate-700">Час:</span> {{ $partRequest->part->name ?? '—' }}</div>
                <div><span class="font-medium text-slate-700">Количество:</span> {{ $partRequest->quantity }} бр.</div>
                @if($partRequest->notes)
                    <div><span class="font-medium text-slate-700">Бележки:</span> {{ $partRequest->notes }}</div>
                @endif
                <div><span class="font-medium text-slate-700">Дата:</span> {{ $partRequest->created_at->format('d.m.Y H:i') }}</div>
            </div>

            <form action="{{ route('part_requests.update', $partRequest->id) }}" method="POST">
                @csrf @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Статус <span class="text-red-400">*</span></label>
                    <select name="status" required class="w-full border border-slate-300 rounded-lg py-2 px-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <option value="pending"   {{ $partRequest->status === 'pending'   ? 'selected' : '' }}>Чакаща</option>
                        <option value="ordered"   {{ $partRequest->status === 'ordered'   ? 'selected' : '' }}>Поръчана</option>
                        <option value="delivered" {{ $partRequest->status === 'delivered' ? 'selected' : '' }}>Доставена</option>
                        <option value="rejected"  {{ $partRequest->status === 'rejected'  ? 'selected' : '' }}>Отказана</option>
                    </select>
                    @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Цена (лв.) — може да бъде коригирана</label>
                    <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $partRequest->price) }}"
                        class="w-full border border-slate-300 rounded-lg py-2 px-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Бележки от администратора</label>
                    <textarea name="notes" rows="2" class="w-full border border-slate-300 rounded-lg py-2 px-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-300">{{ old('notes', $partRequest->notes) }}</textarea>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('part_requests.index') }}" class="text-slate-500 hover:text-slate-700 text-sm py-2 px-4 border border-slate-200 rounded-lg">Отказ</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 px-6 rounded-lg transition">
                        Запази промените
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
