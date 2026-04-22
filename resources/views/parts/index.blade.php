<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-lg text-slate-700">Склад — Наличности</h2>
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('parts.create') }}" class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition">
                    + Добави част
                </a>
            @endif
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto">
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-5 text-sm">{{ session('success') }}</div>
        @endif

        @if(Auth::user()->role === 'mechanic')
            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg mb-5 text-sm">
                Искате да заявите части? <a href="{{ route('part_requests.create') }}" class="font-semibold underline">Отиди към Заявки части</a>
            </div>
        @endif

        @if($parts->isEmpty())
            <div class="bg-white rounded-xl border border-slate-200 p-10 text-center text-slate-400">Складът е празен.</div>
        @else
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="py-3 px-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Наименование</th>
                            <th class="py-3 px-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Сериен №</th>
                            <th class="py-3 px-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Количество</th>
                            <th class="py-3 px-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Цена</th>
                            <th class="py-3 px-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Доставчик</th>
                            @if(Auth::user()->role === 'admin')
                                <th class="py-3 px-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Действия</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($parts as $part)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3 px-4 font-medium text-slate-800">{{ $part->name }}</td>
                                <td class="py-3 px-4 text-sm text-slate-500 font-mono">{{ $part->part_number ?? '—' }}</td>
                                <td class="py-3 px-4">
                                    <span class="text-sm font-semibold {{ $part->quantity == 0 ? 'text-red-500' : ($part->quantity <= 3 ? 'text-amber-500' : 'text-emerald-600') }}">
                                        {{ $part->quantity }} бр.
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm text-slate-700">{{ number_format($part->price, 2) }} лв.</td>
                                <td class="py-3 px-4 text-sm text-slate-500">{{ $part->supplier->name ?? '—' }}</td>
                                @if(Auth::user()->role === 'admin')
                                    <td class="py-3 px-4 text-right">
                                        <a href="{{ route('parts.edit', $part->id) }}" class="text-xs text-blue-600 hover:text-blue-800 border border-blue-200 rounded-lg py-1 px-3 transition">Редактирай</a>
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
