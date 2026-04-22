<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-lg text-slate-700">Ремонти</h2>
            @if(Auth::user()->role !== 'client')
                <a href="{{ route('repairs.create') }}" class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition">
                    + Нов ремонт
                </a>
            @endif
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto">
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-5 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($repairs->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-10 text-center text-slate-400">
                Няма намерени ремонти.
            </div>
        @else
            @foreach($groupedRepairs as $date => $repairsForDay)
                <div class="flex items-center gap-3 my-5">
                    <div class="h-px flex-1 bg-slate-200"></div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider bg-slate-50 px-3 py-1 rounded-full border border-slate-200">
                        {{ $date }}
                    </span>
                    <div class="h-px flex-1 bg-slate-200"></div>
                </div>

                <div class="space-y-3">
                    @foreach($repairsForDay as $repair)
                        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <span class="font-semibold text-slate-800">{{ $repair->title ?? 'Ремонт' }}</span>
                                    @php
                                        $statusMap = [
                                            'pending'     => ['label' => 'Чакащ',    'class' => 'bg-amber-100 text-amber-700'],
                                            'in_progress' => ['label' => 'В процес', 'class' => 'bg-blue-100 text-blue-700'],
                                            'completed'   => ['label' => 'Завършен', 'class' => 'bg-emerald-100 text-emerald-700'],
                                        ];
                                        $s = $statusMap[$repair->status] ?? ['label' => $repair->status, 'class' => 'bg-slate-100 text-slate-600'];
                                    @endphp
                                    <span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full {{ $s['class'] }}">{{ $s['label'] }}</span>
                                </div>

                                @if($repair->car)
                                    @php
                                        $plate  = $repair->car->plate_number ?? '';
                                        $masked = strlen($plate) > 4
                                            ? substr($plate, 0, 2) . str_repeat('*', max(strlen($plate) - 4, 2)) . substr($plate, -2)
                                            : $plate;
                                    @endphp
                                    <p class="text-sm text-slate-500">{{ $repair->car->make }} {{ $repair->car->model }} <span class="font-mono text-slate-400">({{ $masked }})</span></p>
                                @else
                                    <p class="text-sm text-slate-400 italic">Нерегистриран автомобил</p>
                                @endif

                                <div class="mt-1 flex flex-wrap gap-x-4 text-xs text-slate-400">
                                    @if($repair->mechanic)<span>Механик: <span class="text-slate-600">{{ $repair->mechanic->name }}</span></span>@endif
                                    @if($repair->price)<span>Цена: <span class="text-slate-600">{{ number_format($repair->price, 2) }} лв.</span></span>@endif
                                    @if($repair->claimed_at)<span>Прието: <span class="text-slate-600">{{ $repair->claimed_at->format('d.m.Y H:i') }}</span></span>@endif
                                    @if($repair->completed_at)<span>Завършено: <span class="text-slate-600">{{ $repair->completed_at->format('d.m.Y H:i') }}</span></span>@endif
                                </div>

                                @if($repair->parts->isNotEmpty())
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        @foreach($repair->parts as $part)
                                            <span class="bg-slate-100 text-slate-600 text-xs px-2 py-0.5 rounded-full">{{ $part->name }} x{{ $part->pivot->quantity }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 flex-shrink-0 flex-wrap">
                                @if(Auth::user()->role === 'mechanic' && $repair->status === 'pending')
                                    <form action="{{ route('repairs.claim', $repair->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold py-1.5 px-3 rounded-lg transition">Поеми</button>
                                    </form>
                                @endif

                                @if(Auth::user()->role === 'admin' && $repair->status === 'in_progress')
                                    <a href="{{ route('repairs.complete.form', $repair->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold py-1.5 px-3 rounded-lg transition">Завърши</a>
                                @endif

                                @if(Auth::user()->role === 'admin' || (Auth::user()->role === 'mechanic' && $repair->mechanic_id === Auth::id()))
                                    <a href="{{ route('repairs.edit', $repair->id) }}" class="text-xs text-slate-500 hover:text-slate-700 border border-slate-200 rounded-lg py-1.5 px-3 transition">Редактирай</a>
                                    <form method="POST" action="{{ route('repairs.destroy', $repair->id) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Изтриване на ремонта?')" class="text-xs text-red-400 hover:text-red-600 border border-red-100 hover:border-red-300 rounded-lg py-1.5 px-3 transition">Изтрий</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        @endif
    </div>
</x-app-layout>
