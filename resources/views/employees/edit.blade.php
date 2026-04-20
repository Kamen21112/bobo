<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Редактиране на служител: {{ $employee->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('employees.update', $employee->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <p class="block text-gray-700 text-sm font-bold mb-2">Имейл на служител</p>
                            <p class="text-gray-600 mb-4">{{ $employee->email }}</p>
                        </div>

                        {{-- Избор на роля --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="role">Роля</label>
                            <select name="role" id="role" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="mechanic" {{ old('role', $employee->role) == 'mechanic' ? 'selected' : '' }}>Механик</option>
                                <option value="client" {{ old('role', $employee->role) == 'client' ? 'selected' : '' }}>Клиент</option>
                                <option value="admin" {{ old('role', $employee->role) == 'admin' ? 'selected' : '' }}>Администратор</option>
                            </select>
                            @error('role')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Въвеждане на заплата --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="salary">Заплата (лв.)</label>
                            <input type="number" step="0.01" name="salary" id="salary" value="{{ old('salary', $employee->salary) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            @error('salary')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Запази промените
                            </button>
                            <a href="{{ route('employees.index') }}" class="text-gray-500 hover:text-gray-800 underline">
                                Отказ
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>