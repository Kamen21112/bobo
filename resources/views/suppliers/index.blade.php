<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Доставчици</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
                @endif
                
                <a href="{{ route('suppliers.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">+ Добави доставчик</a>

                @if($suppliers->isEmpty())
                    <p>Няма добавени доставчици.</p>
                @else
                    <ul class="list-none p-0">
                        @foreach($suppliers as $supplier)
                            <li class="mb-4 p-4 border rounded shadow-sm flex items-center justify-between bg-gray-50">
                                <div>
                                    <strong>{{ $supplier->name }}</strong> 
                                    <br>
                                    <span class="text-sm text-gray-600">Телефон: {{ $supplier->phone ?? 'Няма' }} | Контакт: {{ $supplier->contact_person ?? 'Няма' }}</span>
                                </div>
                                
                                <div>
                                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Сигурни ли сте, че искате да изтриете този доставчик?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-900 underline">Изтрий</button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>