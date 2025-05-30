<div class="d-flex flex-row align-items-center justify-content-center gap-1 flex-wrap">
    <button type="button" class="btn btn-info btn-sm btn_show" data-perusahaan_id=":id">
        <i class="fas fa-eye"></i>
    </button>
    <button type="button" class="btn btn-warning btn-sm btn_edit" data-perusahaan_id=":id">
        <i class="fas fa-edit"></i>
    </button>
    <button type="button" class="btn btn-sm toggle-status-btn ${isActive ? 'btn-success' : 'btn-secondary'}"
        data-perusahaan_id=":id" data-nama_perusahaan="${nama_perusahaan}">
        <i class="fas ${isActive ? 'fa-toggle-on' : 'fa-toggle-off'}"></i>
    </button>
    <form action="{{ route('admin.perusahaan.destroy', ['id' => ':id']) }}" method="POST" class="d-inline delete-form">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
    </form>
</div>
