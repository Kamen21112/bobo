<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Добави нов доставчик</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('suppliers.store') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label>Име на фирмата:</label>
                        <input type="text" name="name" class="border rounded w-full py-2 px-3" required>
                    </div>

                    <div class="mb-4">
                        <label>Телефон:</label>
                        <input type="text" name="phone" class="border rounded w-full py-2 px-3">
                    </div>

                    <div class="mb-4">
                        <label>Лице за контакт:</label>
                        <input type="text" name="contact_person" class="border rounded w-full py-2 px-3">
                    </div>

                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Запази</button>
                    <a href="{{ route('suppliers.index') }}" class="text-gray-500 ml-4">Отказ</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>