<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-slate-700">Завърши ремонт: {{ $repair->title }}</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <p class="text-sm text-slate-500 mb-6">Посочете кои части бяха използвани (незадължително). Наличността им ще бъде намалена автоматично.</p>

            <form method="POST" action="{{ route('repairs.complete', $repair->id) }}">
                @csrf

                <div id="parts-container" class="space-y-3 mb-4">
                    <div class="flex gap-3 items-center part-row">
                        <select name="parts[0][part_id]" class="flex-1 border border-slate-300 rounded-lg py-2 px-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <option value="">-- Изберете част --</option>
                            @foreach($parts as $part)
                                <option value="{{ $part->id }}">{{ $part->name }} ({{ $part->quantity }} бр. налични)</option>
                            @endforeach
                        </select>
                        <input type="number" name="parts[0][quantity]" min="1" value="1" placeholder="Бр." class="w-20 border border-slate-300 rounded-lg py-2 px-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <button type="button" onclick="this.closest('.part-row').remove()" class="text-red-400 hover:text-red-600 text-sm">✕</button>
                    </div>
                </div>

                <button type="button" onclick="addPartRow()" class="text-sm text-blue-600 hover:text-blue-800 underline mb-6 block">
                    + Добави още една част
                </button>

                <div class="flex gap-3 justify-end">
                    <a href="{{ route('repairs.index') }}" class="text-slate-500 hover:text-slate-700 text-sm py-2 px-4 border border-slate-200 rounded-lg">Отказ</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 px-6 rounded-lg transition">
                        Завърши ремонта
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let rowIndex = 1;
        const partsOptions = @json($parts->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'qty' => $p->quantity]));

        function addPartRow() {
            const container = document.getElementById('parts-container');
            const row = document.createElement('div');
            row.className = 'flex gap-3 items-center part-row';

            let options = '<option value="">-- Изберете част --</option>';
            partsOptions.forEach(p => {
                options += `<option value="${p.id}">${p.name} (${p.qty} бр. налични)</option>`;
            });

            row.innerHTML = `
                <select name="parts[${rowIndex}][part_id]" class="flex-1 border border-slate-300 rounded-lg py-2 px-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    ${options}
                </select>
                <input type="number" name="parts[${rowIndex}][quantity]" min="1" value="1" placeholder="Бр." class="w-20 border border-slate-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                <button type="button" onclick="this.closest('.part-row').remove()" class="text-red-400 hover:text-red-600 text-sm">✕</button>
            `;
            container.appendChild(row);
            rowIndex++;
        }
    </script>
</x-app-layout>
