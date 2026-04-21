<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Списък със заявки
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
                    
                    @if(Auth::user()->role === 'client')
                        <div class="mb-4">
                            <a href="{{ route('service_requests.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                + Нова Заявка
                            </a>
                        </div>
                    @endif

                    @if($requests->isEmpty())
                        <p>Няма намерени заявки.</p>
                    @else
                        <ul class="list-disc pl-5">
                            @foreach($requests as $req)
                                <li class="mb-4 p-4 border rounded shadow-sm flex items-center justify-between bg-gray-50">
                                    <div>
                                        <strong>Автомобил:</strong> {{ $req->car->make ?? 'Неизвестна' }} {{ $req->car->model ?? 'кола' }}<br>
                                        <strong>Проблем:</strong> {{ $req->description }} <br>
                                        @if(Auth::user()->role !== 'client')
                                            <strong>От:</strong> {{ $req->user->name }} ({{ $req->user->email }}) <br>
                                        @endif
                                        <span class="text-sm text-gray-500">
                                            Статус: 
                                            @if($req->status === 'pending') 
                                                <span class="text-yellow-600 font-bold">Чакаща</span> 
                                            @elseif($req->status === 'approved') 
                                                <span class="text-green-600 font-bold">Одобрена</span> 
                                            @elseif($req->status === 'rejected') 
                                                <span class="text-red-600 font-bold">Отхвърлена</span> 
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center space-x-4">
                                        @if((Auth::user()->role === 'mechanic' || Auth::user()->role === 'admin') && $req->status === 'pending')
                                            <form action="{{ route('service_requests.approve', $req->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                    Одобри
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
