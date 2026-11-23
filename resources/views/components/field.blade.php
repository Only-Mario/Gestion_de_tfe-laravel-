@props(['name', 'label', 'type' => 'text', 'value' => null, 'hint' => null, 'id' => null, 'full' => false])
@php($id = $id ?: 'f-' . $name)
<div class="field {{ $full ? 'full' : '' }}">
    <label for="{{ $id }}">{{ $label }}</label>
    @if ($type === 'textarea')
        <textarea id="{{ $id }}" name="{{ $name }}" @error($name) aria-invalid="true" @enderror {{ $attributes }}>{{ old($name, $value) }}</textarea>
    @elseif ($type === 'select')
        <select id="{{ $id }}" name="{{ $name }}" @error($name) aria-invalid="true" @enderror {{ $attributes }}>{{ $slot }}</select>
    @elseif ($type === 'password')
        <span class="secret">
            <input id="{{ $id }}" type="password" name="{{ $name }}" @error($name) aria-invalid="true" @enderror {{ $attributes }}>
            <button class="icon-btn" type="button" data-voir aria-controls="{{ $id }}" aria-pressed="false" aria-label="Afficher le mot de passe" title="Afficher le mot de passe"><i class="fas fa-eye" aria-hidden="true"></i></button>
        </span>
    @else
        <input id="{{ $id }}" type="{{ $type }}" name="{{ $name }}" @if (!in_array($type, ['password', 'file'])) value="{{ old($name, $value) }}" @endif
            @error($name) aria-invalid="true" @enderror {{ $attributes }}>
    @endif
    @if ($hint)<span class="hint">{{ $hint }}</span>@endif
    @error($name)<span class="error" role="alert">{{ $message }}</span>@enderror
</div>
