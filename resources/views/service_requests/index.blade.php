<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-lg text-slate-700">Заявки за сервиз</h2>
            <div class="flex items-center gap-3">
                {{-- Live индикатор --}}
                <span id="live-indicator" class="inline-flex items-center gap-1.5 text-xs text-emerald-600 font-medium">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    На живо
                </span>
                @if(Auth::user()->role === 'client')
                    <a href="{{ route('service_requests.create') }}" class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition">
                        + Нова заявка
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto" id="requests-container">
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-5 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($requests->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-10 text-center text-slate-400">
                Няма чакащи заявки.
            </div>
        @else
            @foreach($groupedRequests as $date => $requestsForDay)
                <div class="flex items-center gap-3 my-5">
                    <div class="h-px flex-1 bg-slate-200"></div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider bg-slate-50 px-3 py-1 rounded-full border border-slate-200">
                        {{ $date }}
                    </span>
                    <div class="h-px flex-1 bg-slate-200"></div>
                </div>

                <div class="space-y-3">
                    @foreach($requestsForDay as $req)
                        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <span class="font-semibold text-slate-800">
                                        {{ $req->car->make ?? 'Неизвестна' }} {{ $req->car->model ?? 'кола' }}
                                    </span>
                                    @php
                                        $statusMap = [
                                            'pending'  => ['label' => 'Чакаща',     'class' => 'bg-amber-100 text-amber-700'],
                                            'approved' => ['label' => 'Одобрена',   'class' => 'bg-emerald-100 text-emerald-700'],
                                            'rejected' => ['label' => 'Отхвърлена','class' => 'bg-red-100 text-red-700'],
                                        ];
                                        $s = $statusMap[$req->status] ?? ['label' => $req->status, 'class' => 'bg-slate-100 text-slate-600'];
                                    @endphp
                                    <span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full {{ $s['class'] }}">{{ $s['label'] }}</span>
                                </div>

                                <p class="text-sm text-slate-600 mb-1">{{ $req->description }}</p>

                                <div class="text-xs text-slate-400 flex flex-wrap gap-x-4">
                                    @if(Auth::user()->role !== 'client')
                                        <span>От: <span class="text-slate-600">{{ $req->user->name }}</span></span>
                                    @endif
                                    <span>Дата: <span class="text-slate-600">{{ $req->created_at->format('d.m.Y H:i') }}</span></span>
                                </div>
                            </div>

                            <div class="flex-shrink-0">
                                @if((Auth::user()->role === 'mechanic' || Auth::user()->role === 'admin') && $req->status === 'pending')
                                    <form action="{{ route('service_requests.approve', $req->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold py-1.5 px-4 rounded-lg transition">
                                            Одобри
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        @endif
    </div>

    {{-- Toast за ново съдържание --}}
    <div id="refresh-toast" class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-50">
        <button onclick="window.location.reload()" class="flex items-center gap-2 bg-slate-800 text-white text-sm font-medium py-2.5 px-5 rounded-full shadow-lg hover:bg-slate-700 transition">
            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Има нови промени — натисни за обновяване
        </button>
    </div>

    <script>
        (function () {
            // Вземаме hash-а при зареждане на страницата
            let currentHash = null;
            const pollUrl  = '{{ route("service_requests.poll") }}';
            const toast    = document.getElementById('refresh-toast');
            const indicator = document.getElementById('live-indicator');
            let failCount  = 0;

            async function poll() {
                try {
                    const res  = await fetch(pollUrl, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (!res.ok) throw new Error('bad response');
                    const data = await res.json();
                    failCount  = 0;

                    if (currentHash === null) {
                        // Първи отговор – запомняме baseline-а
                        currentHash = data.hash;
                    } else if (data.hash !== currentHash) {
                        // Промяна засечена – показваме toast
                        toast.classList.remove('hidden');
                        // Спираме индикатора "на живо" докато потребителят не реагира
                        clearInterval(timer);
                    }
                } catch (e) {
                    failCount++;
                    if (failCount >= 3) {
                        // Прекратяваме polling-а при 3 поредни грешки
                        indicator.innerHTML = '<span class="w-2 h-2 rounded-full bg-slate-300"></span> Офлайн';
                        indicator.classList.replace('text-emerald-600', 'text-slate-400');
                        clearInterval(timer);
                    }
                }
            }

            // Първи poll веднага, после на всеки 10 секунди
            poll();
            const timer = setInterval(poll, 10000);
        })();
    </script>
</x-app-layout>
