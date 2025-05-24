@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Program Studi</h1>
    <button class="btn btn-primary mb-3" id="btn-tambah">Tambah Program Studi</button>
    <table class="table" id="programTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Program</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="modalProgram" tabindex="-1" aria-labelledby="modalProgramLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formProgram">
      @csrf
      <input type="hidden" name="program_id" id="program_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalProgramLabel">Tambah Program Studi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="nama_program" class="form-label">Nama Program</label>
            <input type="text" class="form-control" id="nama_program" name="nama_program" required>
            <div class="invalid-feedback" id="error-nama_program"></div>
          </div>
          <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi"></textarea>
            <div class="invalid-feedback" id="error-deskripsi"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('end')
<script>
$(function() {
    let modal = new bootstrap.Modal(document.getElementById('modalProgram'));
    let table = $('#programTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('program_studi.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'nama_program', name: 'nama_program' },
            { data: 'deskripsi', name: 'deskripsi' },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
        ]
    });

    $('#btn-tambah').click(function() {
        $('#formProgram')[0].reset();
        $('#program_id').val('');
        $('#modalProgramLabel').text('Tambah Program Studi');
        $('.invalid-feedback').text('');
        $('.form-control').removeClass('is-invalid');
        modal.show();
    });

    // Submit form (create/update)
    $('#formProgram').submit(function(e) {
        e.preventDefault();
        let id = $('#program_id').val();
        let url = id ? `/admin/program_studi/${id}` : `/admin/program_studi`;
        let method = id ? 'PUT' : 'POST';
        let formData = $(this).serialize();

        $.ajax({
            url: url,
            type: method,
            data: formData,
            success: function(res) {
                Swal.fire('Berhasil!', res.message, 'success');
                modal.hide();
                table.ajax.reload();
            },
            error: function(xhr) {
                $('.invalid-feedback').text('');
                $('.form-control').removeClass('is-invalid');
                if(xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    for(let key in errors) {
                        $(`#${key}`).addClass('is-invalid');
                        $(`#error-${key}`).text(errors[key][0]);
                    }
                } else {
                    Swal.fire('Gagal!', xhr.responseJSON.message || 'Terjadi kesalahan.', 'error');
                }
            }
        });
    });

    // Edit
    $(document).on('click', '.btn-edit', function() {
        let id = $(this).data('id');
        $.get(`/admin/program_studi/${id}/edit`, function(res) {
            $('#program_id').val(res.program.program_id);
            $('#nama_program').val(res.program.nama_program);
            $('#deskripsi').val(res.program.deskripsi);
            $('#modalProgramLabel').text('Edit Program Studi');
            $('.invalid-feedback').text('');
            $('.form-control').removeClass('is-invalid');
            modal.show();
        });
    });

    // Delete
    $(document).on('click', '.btn-delete', function() {
        let id = $(this).data('id');
        Swal.fire({
            title: 'Hapus Program Studi?',
            text: 'Data akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: `/admin/program_studi/${id}`,
                    type: 'DELETE',
                    data: {_token: '{{ csrf_token() }}'},
                    success: function(res) {
                        Swal.fire('Berhasil!', res.message, 'success');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal!', xhr.responseJSON.message || 'Terjadi kesalahan.', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endpush
