@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full rounded-xl border-slate-300 bg-white text-sm text-slate-900 placeholder:text-slate-400 shadow-sm focus:border-blue-500 focus:ring-blue-500']) }}>
