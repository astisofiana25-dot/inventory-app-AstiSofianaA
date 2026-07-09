@props(['color' => 'green', 'small' => true])
@php
    $base = 'inline-flex items-center whitespace-nowrap '.($small ? 'text-xs px-2 py-1' : 'px-3 py-1.5').' rounded-full font-medium';
    $colors = [
        'green' => 'bg-green-50 text-green-700 border border-green-100',
        'red' => 'bg-red-50 text-red-700 border border-red-100',
        // Make yellow follow same tone/contrast as green for consistency
        'yellow' => 'bg-yellow-50 text-yellow-700 border border-yellow-100',
        'gray' => 'bg-gray-100 text-gray-800 border border-gray-200',
        'brand' => 'bg-brand-50 text-brand-700 border border-brand-100',
    ];
    $classes = $base . ' ' . ($colors[$color] ?? $colors['gray']);
@endphp
<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
