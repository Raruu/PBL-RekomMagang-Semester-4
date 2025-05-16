@props([
    'id' => '',
    'idSpinner' => 'btn-submit-spinner',
    'idText' => 'btn-submit-text',
    'size' => '',
    'class' => 'btn btn-primary',
    'disabled' => false,
    'wrapWithButton' => 'true',
    'style' => '',
])

@if ($wrapWithButton == 'true')
    <button id="{{ $id }}" type="submit" class="{{ $class }}" style="{{ $style }}"
        {{ $disabled ? 'disabled' : '' }}>
        <span id="{{ $idText }}">{{ $slot }}</span>
        <div id="{{ $idSpinner }}" class="spinner-border d-none" role="status"
            style="width: {{ $size }}px; height: {{ $size }}px;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </button>
@else
    <span id="{{ $idText }}">{{ $slot }}</span>
    <div id="{{ $idSpinner }}" class="spinner-border d-none" role="status"
        style="width: {{ $size }}px; height: {{ $size }}px;">
        <span class="visually-hidden">Loading...</span>
    </div>
@endif
