@extends('layouts.app')
@section('content')
    <div class="d-flex flex-row gap-4 pb-4 position-relative container-fluid">
        <div class="d-flex flex-column text-start gap-3 w-100">
            <div class="d-flex flex-row flex-wrap card px-3 py-4">
                <div class="icon-wrapper me-3">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-0">Dashboard</h>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .icon-wrapper {
            width: 50px;
            height: 50px;
            background-color: var(--cui-primary-bg-subtle);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-wrapper i {
            font-size: 24px;
            color: var(--cui-primary);
        }
    </style>
@endpush

@push('end')
<script>

</script>
@endpush