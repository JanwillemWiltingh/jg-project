<div class="form-group">
    <label
        class="black-label-text"
        for="{{ $name }}">
        {{ __('general.'.$name) }}
    </label>
    <input
        type="{{ $type }}"
        class=" @if($readonly) form-control-plaintext @else form-control @endif "
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ old($name) ?? $value ?? null }}"
        aria-describedby="{{ $name }}"
        placeholder="{{ __('general.'.$name) }}"
        @if($disabled) disabled @endif
        @if($readonly) readonly @endif
    >

    @if($errors->has($name))
        <div class="error">
            <label class="warning-label">
                {{ $errors->first($name) }}
            </label>
        </div>
    @endif
</div>
