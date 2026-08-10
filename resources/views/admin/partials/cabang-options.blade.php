@php
    $groupedCabangs = ($cabangs ?? \App\Models\Cabang::orderBy('kelompok')->orderBy('nama')->get())->groupBy('kelompok');
@endphp

@foreach($groupedCabangs->get('HO', collect()) as $cabang)
    <option value="{{ $cabang->id }}" class="font-bold">{{ $cabang->nama }}</option>
@endforeach

@if($groupedCabangs->has('KPW'))
<optgroup label="KPW">
    @foreach($groupedCabangs->get('KPW') as $cabang)
        <option value="{{ $cabang->id }}">{{ $cabang->nama }}</option>
    @endforeach
</optgroup>
@endif

@if($groupedCabangs->has('Pabrik'))
<optgroup label="Pabrik">
    @foreach($groupedCabangs->get('Pabrik') as $cabang)
        <option value="{{ $cabang->id }}">{{ $cabang->nama }}</option>
    @endforeach
</optgroup>
@endif

@if($groupedCabangs->has('Lainnya'))
<optgroup label="Lainnya">
    @foreach($groupedCabangs->get('Lainnya') as $cabang)
        <option value="{{ $cabang->id }}">{{ $cabang->nama }}</option>
    @endforeach
</optgroup>
@endif
