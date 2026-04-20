<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Склад - Наличности</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
                @endif
                
                <a href="{{ route('parts.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">+ Добави част</a>

                @if($parts->isEmpty())
                    <p>Складът е празен.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100 border-b">
                                    <th class="py-2 px-4 text-left">Име на частта</th>
                                    <th class="py-2 px-4 text-left">Сериен №</th>
                                    <th class="py-2 px-4 text-left">Количество</th>
                                    <th class="py-2 px-4 text-left">Цена</th>
                                    <th class="py-2 px-4 text-left">Доставчик</th>
                                    <th class="py-2 px-4 text-right">Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($parts as $part)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-2 px-4"><strong>{{ $part->name }}</strong></td>
                                        <td class="py-2 px-4 text-sm text-gray-600">{{ $part->part_number ?? '-' }}</td>
                                        <td class="py-2 px-4">
                                            <span class="{{ $part->quantity == 0 ? 'text-red-500 font-bold' : 'text-green-600 font-bold' }}">
                                                {{ $part->quantity }} бр.
                                            </span>
                                        </td>
                                        <td class="py-2 px-4">{{ $part->price }} лв.</td>
                                        <td class="py-2 px-4 text-sm">{{ $part->supplier->name ?? 'Неизвестен' }}</td>
                                        <td class="py-2 px-4 text-right">
                                            <a href="{{ route('parts.edit', $part->id) }}" class="text-sm text-blue-600 hover:text-blue-900 underline">Редактирай</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>