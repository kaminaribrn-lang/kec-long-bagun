{{-- Foto unggahan admin, atau kotak pengganti berlabel bila belum ada (PRD bagian 10 butir 3). --}}
@php $src = Situs::berkas($path ?? null); @endphp
@if ($src)
    <img class="cover" src="{{ $src }}" alt="{{ $alt ?? $label }}" loading="lazy" decoding="async">
@else
    <div class="ph-box {{ $bentuk ?? '' }}"><span class="phl">[FOTO: {{ $label }}]</span></div>
@endif
