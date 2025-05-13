@props(['id' => 'btn-submit-spinner', 'size' => ''])

<div id="{{ $id }}" class="spinner-border d-none" role="status" style="width: {{ $size }}px; height: {{ $size }}px;">
    <span class="visually-hidden">Loading...</span>
</div>
