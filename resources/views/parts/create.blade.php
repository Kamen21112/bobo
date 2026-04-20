<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Добави част в склада</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('parts.store') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Име на частта:</label>
                        <input type="text" name="name" class="border rounded w-full py-2 px-3" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Сериен номер / SKU:</label>
                        <input type="text" name="part_number" class="border rounded w-full py-2 px-3">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Количество (бр.):</label>
                        <input type="number" name="quantity" class="border rounded w-full py-2 px-3" value="1" min="0" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Цена (лв.):</label>
                        <input type="number" step="0.01" name="price" class="border rounded w-full py-2 px-3" min="0" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Доставчик:</label>
                        <select name="supplier_id" class="border rounded w-full py-2 px-3">
                            <option value="">-- Избери доставчик (опционално) --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Запази в склада</button>
                    <a href="{{ route('parts.index') }}" class="text-gray-500 ml-4">Отказ</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>