<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Управление на служители
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">Име</th>
                                <th class="py-3 px-6 text-left">Имейл</th>
                                <th class="py-3 px-6 text-center">Роля</th>
                                <th class="py-3 px-6 text-center">Заплата</th>
                                <th class="py-3 px-6 text-center">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @foreach($employees as $employee)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="py-3 px-6 text-left whitespace-nowrap font-bold">
                                        {{ $employee->name }}
                                    </td>
                                    <td class="py-3 px-6 text-left">
                                        {{ $employee->email }}
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        @if($employee->role == 'admin')
                                            <span class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs">Администратор</span>
                                        @elseif($employee->role == 'client')
                                            <span class="bg-purple-200 text-purple-600 py-1 px-3 rounded-full text-xs">Клиент</span>
                                        @else
                                            <span class="bg-blue-200 text-blue-600 py-1 px-3 rounded-full text-xs">Механик</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        {{ $employee->salary ? $employee->salary . ' лв.' : 'Не е зададена' }}
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <a href="{{ route('employees.edit', $employee->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded text-xs">
                                            Редактирай
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