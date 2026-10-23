@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.cleanwave')

@section('title', 'Notifikasi')

@section('content')
<div class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading">Notifikasi</h1>
                <p class="text-slate-500 text-sm mt-1">
                    Informasi terbaru seputar pesanan, pembayaran, dan promo Anda.
                </p>
            </div>

            @if($unreadCount > 0)
            <form action="{{ route('notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="px-5 py-2.5 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-check-double"></i> Tandai Semua Dibaca ({{ $unreadCount }})
                </button>
            </form>
            @endif
        </div>

        @if($notifications->count() > 0)
        <div class="space-y-3">
            @foreach($notifications as $notification)
            @php
            $icon = match($notification->type) {
                'order' => 'fa-box',
                'payment' => 'fa-credit-card',
                'promo' => 'fa-tag',
                default => 'fa-bell',
            };
            $color = match($notification->type) {
                'order' => 'bg-blue-50 text-brand-600',
                'payment' => 'bg-emerald-50 text-emerald-600',
                'promo' => 'bg-amber-50 text-amber-600',
                default => 'bg-slate-100 text-slate-600',
            };
            @endphp

            <div class="bg-white rounded-2xl p-5 border {{ $notification->is_read ? 'border-slate-200' : 'border-brand-200 shadow-sm' }} flex items-start gap-4">
                <div class="w-11 h-11 rounded-xl {{ $color }} flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid {{ $icon }}"></i>
                </div>

                <div class="flex-grow min-w-0">
                    <div class="flex items-start justify-between gap-3">
                        <h3 class="font-bold text-sm text-slate-900">
                            {{ $notification->title }}
                            @if(! $notification->is_read)
                            <span class="inline-block w-2 h-2 rounded-full bg-brand-600 ml-1 align-middle"></span>
                            @endif
                        </h3>
                        <span class="text-[11px] text-slate-400 whitespace-nowrap">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $notification->message }}</p>

                    @if(! $notification->is_read)
                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="text-[11px] font-bold text-brand-600 hover:text-brand-800">
                            Tandai sudah dibaca
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach

            <div class="pt-6">
                {{ $notifications->links() }}
            </div>
        </div>
        @else
        <div class="text-center py-16 bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
            <div class="w-20 h-20 rounded-full bg-blue-50 text-brand-600 flex items-center justify-center text-3xl mx-auto mb-4">
                <i class="fa-regular fa-bell"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900">Belum Ada Notifikasi</h3>
            <p class="text-slate-500 text-sm max-w-sm mx-auto mt-1">
                Semua informasi pesanan dan pembayaran Anda akan muncul di sini.
            </p>
        </div>
        @endif

    </div>
</div>
@endsection
