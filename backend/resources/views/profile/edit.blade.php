<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(auth()->user()->customer)
            @php
            $customer = auth()->user()->customer;
            $nextTier = $customer->next_tier;
            @endphp
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Membership CleanWave</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1 flex items-center gap-2">
                            <i class="fa-solid fa-crown text-amber-500"></i>
                            {{ $customer->membership_label }}
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">
                            Saldo poin: <span class="font-bold text-gray-900">{{ number_format($customer->loyalty_points, 0, ',', '.') }} poin</span>
                        </p>
                    </div>
                    <div class="text-sm text-gray-600 sm:text-right">
                        @if($nextTier)
                        <p>Menuju <span class="font-bold text-gray-900">{{ $nextTier['name'] }}</span></p>
                        <p class="text-xs text-gray-500">{{ number_format($nextTier['points'] - $customer->loyalty_points, 0, ',', '.') }} poin lagi</p>
                        @else
                        <p class="font-bold text-amber-600">Tier tertinggi tercapai</p>
                        @endif
                    </div>
                </div>

                <div class="mt-5">
                    <div class="w-full h-2.5 rounded-full bg-gray-100 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-blue-600 to-sky-500" style="width: {{ $customer->membership_progress }}%"></div>
                    </div>
                    <p class="text-[11px] text-gray-500 mt-2">
                        Kumpulkan poin dari setiap pesanan selesai. Setiap 1 poin senilai Rp 1 dan dapat ditukar saat checkout.
                    </p>
                </div>
            </div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>