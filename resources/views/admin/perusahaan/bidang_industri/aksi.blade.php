<div class="d-flex flex-row align-items-center justify-content-center gap-1 flex-wrap">
    <button type="button" class="btn btn-info btn-sm btn_show" data-bidang_id=":id">
        <i class="fas fa-eye"></i>
    </button>
    <button type="button" class="btn btn-warning btn-sm btn_edit" data-bidang_id=":id">
        <i class="fas fa-edit"></i>
    </button>
    <form action="{{ route('admin.bidang_industri.destroy', ['id' => ':id']) }}" method="POST"
        class="d-inline delete-form">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
    </form>
</div>
