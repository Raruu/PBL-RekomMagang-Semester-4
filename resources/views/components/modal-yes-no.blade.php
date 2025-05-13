@props([
    'id' => 'modal-yes-no',
    'idTrue' => 'btn-true-yes-no',
    'idFalse' => 'btn-false-yes-no',
    'dismiss' => true,
    'title' => 'Yakin?',
    'btnTrue' => 'Ya',
    'btnFalse' => 'Batal',
    'slot' => ''
])

<div class="modal fade" id="{{ $id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="btn-close"
                    onclick="document.getElementById('{{ $idFalse }}').click()"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" {{ $dismiss ? 'data-coreui-dismiss="modal"' : '' }}
                    id="{{ $idFalse }}">{{ $btnFalse }}</button>
                <button type="button" class="btn btn-primary" data-coreui-dismiss="modal"
                    {{ $dismiss ? 'data-coreui-dismiss="modal"' : '' }} id="{{ $idTrue }}">{{ $btnTrue }}</button>
            </div>
        </div>
    </div>
</div>
