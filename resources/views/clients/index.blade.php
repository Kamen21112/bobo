<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Списък с клиенти
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Бутон за добавяне на нов клиент --}}
                    <div class="mb-6">
                        <a href="{{ route('clients.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Добави нов клиент
                        </a>
                    </div>

                    {{-- Таблица с клиенти --}}
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="border-b-2 py-3">Име и Фамилия</th>
                                <th class="border-b-2 py-3">Телефон</th>
                                <th class="border-b-2 py-3">Имейл</th>
                                <th class="border-b-2 py-3 text-right">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clients as $client)
                                <tr class="hover:bg-gray-50">
                                    <td class="border-b py-3">{{ $client->first_name }} {{ $client->last_name }}</td>
                                    <td class="border-b py-3">{{ $client->phone ?? 'Няма' }}</td>
                                    <td class="border-b py-3">{{ $client->email ?? 'Няма' }}</td>
                                    <td class="border-b py-3 text-right">
                                        {{-- Линк към пълния CRM профил, който направихме по-рано! --}}
                                        <a href="{{ route('clients.show', $client->id) }}" class="text-blue-600 hover:underline font-semibold">
                                            Виж профила
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>