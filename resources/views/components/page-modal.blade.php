@props(['id' => 'page-modal', 'title' => 'Modal Title', 'message' => '', 'class' => '', 'slot' => ''])

<div class="modal fade" id="{{ $id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable {{ $class }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-wrap" style="max-width: 70%">{{ $title }}</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $message }}{{ $slot }}
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-primary" data-coreui-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
