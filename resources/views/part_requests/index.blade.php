<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-lg text-slate-700">Заявки за части</h2>
            @if(Auth::user()->role !== 'client')
                <a href="{{ route('part_requests.create') }}" class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition">
                    + Нова заявка
                </a>
            @endif
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto">
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-5 text-sm">{{ session('success') }}</div>
        @endif

        {{-- Филтри и търсене --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 mb-5">
            <form method="GET" action="{{ route('part_requests.index') }}" class="flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-xs text-slate-500 mb-1">Търсене</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Търси по части или механик..." class="border border-slate-300 rounded-lg py-2 px-3 text-sm text-slate-700 w-64 focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
                <div>
                    <label class="block text-xs text-slate-500 mb-1">Статус</label>
                    <select name="status" class="border border-slate-300 rounded-lg py-2 px-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <option value="">Всички</option>
                        <option value="pending"   {{ request('status') == 'pending'   ? 'selected' : '' }}>Чакащи</option>
                        <option value="ordered"   {{ request('status') == 'ordered'   ? 'selected' : '' }}>Поръчани</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Доставени</option>
                        <option value="rejected"  {{ request('status') == 'rejected'  ? 'selected' : '' }}>Отказани</option>
                    </select>
                </div>
                <button type="submit" class="bg-slate-700 hover:bg-slate-800 text-white text-sm py-2 px-4 rounded-lg transition">Търси</button>
                <a href="{{ route('part_requests.index') }}" class="text-sm text-slate-400 hover:text-slate-600 py-2">Изчисти</a>

                @if(Auth::user()->role === 'admin')
                    <div class="ml-auto flex gap-2">
                        <a href="{{ route('part_requests.index', array_merge(request()->except('export'), ['export' => 'xlsx'])) }}"
                           class="text-sm text-emerald-600 hover:text-emerald-800 border border-emerald-200 hover:border-emerald-400 rounded-lg py-2 px-3 transition">
                            ↓ Excel (.xlsx)
                        </a>
                        <a href="{{ route('part_requests.index', array_merge(request()->except('export'), ['export' => 'xls'])) }}"
                           class="text-sm text-blue-600 hover:text-blue-800 border border-blue-200 hover:border-blue-400 rounded-lg py-2 px-3 transition">
                            ↓ Excel (.xls)
                        </a>
                    </div>
                @endif
            </form>
        </div>

        @if($partRequests->isEmpty())
            <div class="bg-white rounded-xl border border-slate-200 p-10 text-center text-slate-400">
                Няма намерени заявки за части.
            </div>
        @else
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="py-3 px-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                            @if(Auth::user()->role === 'admin')
                                <th class="py-3 px-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Механик</th>
                            @endif
                            <th class="py-3 px-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Част</th>
                            <th class="py-3 px-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Количество</th>
                            <th class="py-3 px-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Цена</th>
                            <th class="py-3 px-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Статус</th>
                            <th class="py-3 px-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Дата</th>
                            @if(Auth::user()->role === 'admin')
                                <th class="py-3 px-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Действия</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($partRequests as $pr)
                            @php
                                $statusColors = [
                                    'pending'   => 'bg-amber-100 text-amber-700',
                                    'ordered'   => 'bg-blue-100 text-blue-700',
                                    'delivered' => 'bg-emerald-100 text-emerald-700',
                                    'rejected'  => 'bg-red-100 text-red-600',
                                ];
                                $sc = $statusColors[$pr->status] ?? 'bg-slate-100 text-slate-600';
                            @endphp
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3 px-4 text-sm text-slate-400">{{ $pr->id }}</td>
                                @if(Auth::user()->role === 'admin')
                                    <td class="py-3 px-4 text-sm text-slate-700">{{ $pr->mechanic->name ?? '—' }}</td>
                                @endif
                                <td class="py-3 px-4 font-medium text-slate-800">{{ $pr->part->name ?? '—' }}</td>
                                <td class="py-3 px-4 text-sm text-slate-700">{{ $pr->quantity }} бр.</td>
                                <td class="py-3 px-4 text-sm text-slate-700">{{ $pr->price ? number_format($pr->price, 2) . ' лв.' : '—' }}</td>
                                <td class="py-3 px-4">
                                    <span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full {{ $sc }}">{{ $pr->status_label }}</span>
                                    @if($pr->status_changed_at)
                                        <div class="text-xs text-slate-400 mt-0.5">{{ $pr->status_changed_at->format('d.m.Y H:i') }}</div>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-xs text-slate-400">{{ $pr->created_at->format('d.m.Y H:i') }}</td>
                                @if(Auth::user()->role === 'admin')
                                    <td class="py-3 px-4 text-right">
                                        <a href="{{ route('part_requests.edit', $pr->id) }}" class="text-xs text-blue-600 hover:text-blue-800 border border-blue-200 rounded-lg py-1 px-3 transition">Управлявай</a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
