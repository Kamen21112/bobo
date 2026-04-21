<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Списък с ремонти
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    
                    {{-- Бутон за добавяне (Скрит за механици) --}}
                    @if(Auth::user()->role !== 'client')
                        <div class="mb-4">
                            <a href="{{ route('repairs.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                + Добави ремонт
                            </a>
                        </div>
                    @endif

                    {{-- Списък с ремонти --}}
                    @if($repairs->isEmpty())
                        <p>Все още нямате добавени ремонти.</p>
                    @else
                        <ul class="list-disc pl-5">
                            @foreach($repairs as $repair)
                                <li class="mb-4 p-4 border rounded shadow-sm flex items-center justify-between bg-gray-50">
                                    <div>
                                        <strong>{{ $repair->title ?? 'Ремонт' }}</strong> 
                                        @if($repair->car)
                                            <span class="text-gray-600">- {{ $repair->car->make }} {{ $repair->car->model }} ({{ $repair->car->plate_number }})</span>
                                        @else
                                            <span class="text-gray-600">- (Нерегистриран автомобил от заявка)</span>
                                        @endif
                                        <br>
                                        <span class="text-sm text-gray-500">
                                            Статус: 
                                            @if($repair->status === 'pending') 
                                                <span class="text-yellow-600 font-bold">Чакащ</span> 
                                            @elseif($repair->status === 'in_progress') 
                                                <span class="text-blue-600 font-bold">В процес ({{ $repair->mechanic->name ?? 'Неизвестен' }})</span> 
                                            @elseif($repair->status === 'completed') 
                                                <span class="text-green-600 font-bold">Завършен</span> 
                                            @else
                                                {{ $repair->status }}
                                            @endif
                                            | Цена: {{ $repair->price ? $repair->price . ' лв.' : 'Не е посочена' }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center space-x-4">
                                        
                                        {{-- Бутон "Поеми" (Само за механици при чакащ ремонт) --}}
                                        @if(Auth::user()->role === 'mechanic' && $repair->status === 'pending')
                                            <form action="{{ route('repairs.claim', $repair->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                    Поеми
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Бутон "Завърши" (Само за механика, който го е поел) --}}
                                        @if(Auth::id() === $repair->mechanic_id && $repair->status === 'in_progress')
                                            <form action="{{ route('repairs.complete', $repair->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                    Завърши
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Бутони за редактиране и изтриване --}}
                                        @if(Auth::user()->role === 'admin' || (Auth::user()->role === 'mechanic' && $repair->mechanic_id === Auth::id()))
                                            <a href="{{ route('repairs.edit', $repair->id) }}" class="text-sm text-yellow-600 hover:text-yellow-800 underline">
                                                Редактирай
                                            </a>

                                            <form method="POST" action="{{ route('repairs.destroy', $repair->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm text-red-500 hover:text-red-700 underline" onclick="return confirm('Сигурни ли сте, че искате да изтриете този ремонт?')">
                                                    Изтрий
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>