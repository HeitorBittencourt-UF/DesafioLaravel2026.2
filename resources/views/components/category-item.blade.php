@props(['label', 'href' => '#'])

<a href="{{ $href }}" class="category-item">
    <div class="category-icon-wrapper">
        {{-- O slot receberá o componente do ícone SVG --}}
        {{ $slot }}
    </div>
    <span class="category-label">{{ $label }}</span>
</a>