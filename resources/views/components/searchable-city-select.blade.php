@props([
    'options' => [],
    'selected' => 'MEDAN',
    'id' => 'city',
])

@php($selectedCity = mb_strtoupper(trim((string) $selected)) ?: 'MEDAN')
<div class="city-select">
    <select
        id="{{ $id }}"
        name="city"
        data-city-select
        class="w-full rounded-lg border-slate-300 text-sm font-medium uppercase tracking-wide text-[#0B1C30] focus:border-teal-700 focus:ring-teal-700"
        aria-describedby="{{ $id }}-help"
    >
        @foreach ($options as $option)
            <option value="{{ $option['value'] }}" @selected($selectedCity === $option['value'])>{{ $option['label'] }}</option>
        @endforeach
    </select>
    <p id="{{ $id }}-help" class="mt-2 text-xs text-slate-500">Ketik untuk mencari kota/kabupaten. MEDAN berada di urutan pertama.</p>
</div>
