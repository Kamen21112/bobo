<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Статистика на сервиза</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-100 p-6 rounded-lg shadow-sm border border-blue-200">
                            <h4 class="text-blue-800 font-semibold mb-1">Общо автомобили</h4>
                            <p class="text-3xl font-bold text-blue-900">{{ $carsCount }}</p>
                        </div>

                        <div class="bg-yellow-100 p-6 rounded-lg shadow-sm border border-yellow-200">
                            <h4 class="text-yellow-800 font-semibold mb-1">Чакащи ремонти</h4>
                            <p class="text-3xl font-bold text-yellow-900">{{ $pendingRepairs }}</p>
                        </div>

                        <div class="bg-green-100 p-6 rounded-lg shadow-sm border border-green-200">
                            <h4 class="text-green-800 font-semibold mb-1">Завършени ремонти</h4>
                            <p class="text-3xl font-bold text-green-900">{{ $completedRepairs }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
