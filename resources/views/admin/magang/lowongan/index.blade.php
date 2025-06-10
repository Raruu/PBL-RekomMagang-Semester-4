@extends('layouts.app')

@section('title', $page->title)
@push('start')
    @vite(['resources/js/import/tagify.js'])
@endpush

@section('content-top')
    <div class="container-fluid px-4">
        <div class="d-flex flex-column mb-3 header-lowongan">
            <div class="card shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3">
                                <i class="fas fa-briefcase text-primary fs-3"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 fw-bold">{{ $page->title }}</h2>
                                <p class="text-body-secondary mb-0">Kelola semua lowongan magang dengan mudah</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="badge bg-primary fs-6 px-3 py-2">
                                <i class="fas fa-chart-bar me-1"></i>
                                Total: <span id="record-count" class="fw-bold">0</span> lowongan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3 mb-3">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <a href="{{ route('admin.magang.lowongan.create') }}"
                    class="btn btn-primary btn-action d-flex align-items-center" id="btn-create">
                    <i class="fas fa-plus me-2"></i>
                    <span>Tambah Lowongan</span>
                </a>
                <button type="button" class="btn btn-success btn-action d-flex align-items-center" id="btn-refresh">
                    <i class="fas fa-sync-alt me-2"></i>
                    <span>Refresh</span>
                </button>
            </div>

            <div class="d-flex flex-wrap gap-2 align-items-center">
                <div class="btn-group" role="group">
                    <button type="button"
                        class="btn btn-outline-secondary btn-action dropdown-toggle d-flex align-items-center"
                        data-coreui-toggle="dropdown">
                        <i class="fas fa-filter me-2"></i>
                        <span>Filter Status</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" id="filter-status">
                        <li>
                            <h6 class="dropdown-header"><i class="fas fa-filter me-1"></i>Filter berdasarkan Status</h6>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item active d-flex align-items-center" data-status=" ">
                                <i class="fas fa-stream me-2 text-info"></i>Semua Status
                            </a></li>
                        <li><a class="dropdown-item d-flex align-items-center" data-status="Aktif">
                                <i class="fas fa-check-circle me-2 text-success"></i>Aktif
                            </a></li>
                        <li><a class="dropdown-item d-flex align-items-center" data-status="Nonaktif">
                                <i class="fas fa-times-circle me-2 text-danger"></i>Nonaktif
                            </a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Konten Utama -->
        <div class="d-flex flex-column pb-4">
            <div class="card shadow-sm table-card">
                <div class="card-header border-bottom">
                    <div
                        class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-table me-2 text-primary"></i>
                            <h5 class="mb-0 fw-semibold">Daftar Lowongan Magang</h5>
                        </div>
                    </div>
                </div>

                <div class="card-body p-3">
                    <div class="table-responsive table-container">
                        <table class="table table-hover table-bordered table-striped mb-0" id="lowonganMagangTable"
                            style="width: 100%">
                            <thead class="table-header">
                                <tr>
                                    <th class="text-center" style="width: 60px;">No</th>
                                    <th>Judul Lowongan</th>
                                    <th>Posisi</th>
                                    <th>Perusahaan</th>
                                    <th>Lokasi</th>
                                    <th class="text-center" style="width: 150px;">Tipe Kerja</th>
                                    <th class="text-center" style="width: 220px;">Batas Pendaftaran</th>
                                    <th class="text-center" style="width: 100px;">Status</th>
                                    <th class="text-center" style="width: 200px;">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <x-modal-yes-no id="editLowonganModal" dismiss="false" static="true" class="modal-xl">
        <x-slot name="btnTrue">
            <x-btn-submit-spinner size="22" wrapWithButton="false">
                Simpan
            </x-btn-submit-spinner>
        </x-slot>
    </x-modal-yes-no>
    @include('admin.magang.lowongan.modal-detail')
    @include('admin.magang.lowongan.feedback')
@endsection

@push('styles')
    @vite (['resources/css/lowongan/index.css'])
    <style>

    </style>
@endpush

@push('end')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const table = $('#lowonganMagangTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.magang.lowongan.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'judul_lowongan',
                        name: 'judul_lowongan',
                        searchable: true
                    },
                    {
                        data: 'judul_posisi',
                        name: 'judul_posisi',
                        searchable: true
                    },
                    {
                        data: 'perusahaan',
                        name: 'perusahaan',
                        searchable: true
                    },
                    {
                        data: 'lokasi',
                        name: 'lokasi',
                        searchable: true
                    },
                    {
                        data: 'tipe_kerja_lowongan',
                        name: 'tipe_kerja_lowongan',
                        searchable: false
                    },
                    {
                        data: 'batas_pendaftaran',
                        name: 'batas_pendaftaran',
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        searchable: true,
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        searchable: false
                    }
                ],
                columnDefs: [{
                    targets: [0, 5, 6, 7, 8],
                    className: 'text-center'
                }, ],
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                language: {
                    processing: '<div class="d-flex align-items-center justify-content-center"><div class="spinner-border spinner-border-sm me-2"></div>Memuat data...</div>',
                    search: "Search:",
                    infoEmpty: "Tidak ada data yang tersedia",
                    emptyTable: "Tidak ada data lowongan yang tersedia",
                },
                drawCallback: function(settings) {
                    $('#record-count').text(settings._iRecordsDisplay);
                    $(this.api().table().body()).find('tr').each(function(index) {
                        $(this).css('animation',
                            `fadeInUp 0.3s ease forwards ${index * 0.05}s`);
                    });
                },
            });

            $('#btn-refresh').on('click', function() {
                const $btn = $(this);
                const originalHtml = $btn.html();

                $btn.html('<i class="fas fa-spinner fa-spin me-2"></i><span>Refreshing...</span>');
                $btn.prop('disabled', true);

                table.ajax.reload(function() {
                    setTimeout(() => {
                        $btn.html(originalHtml);
                        $btn.prop('disabled', false);
                    }, 500);
                });
            });

            $('#filter-status').on('click', '.dropdown-item', function(e) {
                e.preventDefault();
                const selected = $(this).data('status').trim();
                $('#filter-status .dropdown-item').removeClass('active');
                $(this).addClass('active');
                if (selected === '' || selected === undefined) {
                    table.column(7).search('').draw();
                } else if (selected === 'Aktif') {
                    table.column(7).search('id="1"').draw();
                } else if (selected === 'Nonaktif' || selected === 'Non-aktif') {
                    table.column(7).search('id="0"').draw();
                }
            });

            $(document).on('click', '.toggle-status-btn', function() {
                const lowonganId = $(this).data('lowongan-id');
                const judulLowongan = $(this).data('judul');

                Swal.fire({
                    title: 'Ubah Status Lowongan?',
                    text: `Anda yakin ingin mengubah status lowongan "${judulLowongan}"?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, ubah',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'animated fadeIn'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        swalLoading('Mengirim data ke server...');
                        $.ajax({
                            url: `{{ url('/admin/magang/lowongan') }}/${lowonganId}/toggle-status`,
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(res) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: res.message,
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false,
                                    customClass: {
                                        popup: 'animated fadeIn'
                                    }
                                });
                                table.ajax.reload(null, false);
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: xhr.responseJSON?.error ||
                                        'Terjadi kesalahan',
                                    icon: 'error',
                                    customClass: {
                                        popup: 'animated fadeIn'
                                    }
                                });
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.delete-btn', function() {
                const url = $(this).data('url');
                const judulLowongan = $(this).data('judul');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Data lowongan "${judulLowongan}" akan dihapus permanen!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'animated fadeIn'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        swalLoading('Mengirim data ke server...');
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message ||
                                        'Data berhasil dihapus',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false,
                                    customClass: {
                                        popup: 'animated fadeIn'
                                    }
                                });
                                table.ajax.reload(null, false);
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    xhr.responseJSON?.error ||
                                    'Gagal menghapus data',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.view-btn', function() {
                const url = $(this).data('url');
                const modal = new coreui.Modal('#modalDetailLowongan');

                modal.show();

                $.get(url)
                    .done(response => {
                        if (response.success) {
                            displayLowonganData(response.data);
                        } else {
                            showError(response.message || 'Gagal memuat data lowongan');
                        }
                    })
                    .fail(xhr => {
                        showError(xhr.responseJSON?.message || 'Terjadi kesalahan saat memuat data');
                    });
            });

            function displayLowonganData(data) {
                const formatters = {
                    date: date => new Date(date).toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    }),

                    workType: type => {
                        const types = {
                            remote: 'Remote',
                            onsite: 'On-site',
                            hybrid: 'Hybrid'
                        };
                        return types[type] || type;
                    },

                    workTypeBadge: type => {
                        const colors = {
                            remote: 'primary',
                            onsite: 'success',
                            hybrid: 'warning'
                        };
                        return `<span class="badge bg-${colors[type] || 'secondary'} px-3 py-2"> ${formatters.workType(type)} </span>`;
                    },

                    status: isActive => isActive ?
                        '<span class="badge bg-success px-3 py-2">Aktif</span>' :
                        '<span class="badge bg-danger px-3 py-2">Nonaktif</span>',

                    salary: amount => amount ?
                        `Rp ${Number(amount).toLocaleString('id-ID')}` : 'Tidak disebutkan',

                    quota: amount => amount ?
                        `${amount} orang` : 'Tidak terbatas',

                    requirements: req => {
                        if (!req) {
                            return `<div class="text-body-secondary fst-italic">
                                        Belum ada persyaratan yang ditetapkan
                                    </div>`;
                        }

                        let html = '<div class="persyaratan-detail">';

                        if (req.minimum_ipk) {
                            html += `<div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-graduation-cap me-3 text-primary"></i>
                                        <span><strong>Minimum IPK:</strong> ${req.minimum_ipk}</span>
                                    </div>`;
                        }

                        if (req.pengalaman !== null) {
                            const exp = req.pengalaman ? 'Diperlukan' : 'Tidak diperlukan';
                            const iconClass = req.pengalaman ? 'fa-user-tie text-success' :
                                'fa-user-graduate text-danger';
                            html += `<div class="d-flex align-items-center mb-3">
                                        <i class="fas ${iconClass} me-3"></i>
                                        <span><strong>Pengalaman:</strong> ${exp}</span>
                                    </div>`;
                        }

                        if (req.deskripsi_persyaratan) {
                            html += `<div class="mt-3">
                                        <div class="mb-2">
                                            <strong>Deskripsi:</strong>
                                        </div>
                                        <div class="text-body-secondary">
                                            ${req.deskripsi_persyaratan.replace(/\n/g, '<br>')}
                                        </div>
                                    </div>`;
                        }

                        return html + '</div>';
                    },

                    skills: skills => {
                        if (!skills?.length) {
                            return `<div class="text-body-secondary fst-italic">
                                        Belum ada keahlian yang ditentukan
                                    </div>`;
                        }

                        const colors = {
                            pemula: 'secondary',
                            menengah: 'info',
                            mahir: 'warning',
                            ahli: 'success'
                        };

                        const badges = skills.map(item =>
                            `<span class="badge bg-${colors[item.kemampuan_minimum] || 'secondary'} me-2 mb-2 px-3 py-2">
                                                ${item.keahlian.nama_keahlian}
                                                <small class="ms-1">(${item.kemampuan_minimum})</small>
                                            </span>`
                        ).join('');

                        return `<div class="keahlian-badges">${badges}</div>`;
                    }
                };

                const updates = {
                    '#detail-judul-lowongan': data.judul_lowongan || '-',
                    '#detail-posisi': data.judul_posisi || '-',
                    '#detail-perusahaan': data.perusahaan_mitra?.nama_perusahaan || '-',
                    '#detail-lokasi': data.lokasi?.alamat || '-',
                    '#detail-batas': formatters.date(data.batas_pendaftaran),
                    '#detail-gaji': formatters.salary(data.gaji),
                    '#detail-kuota': formatters.quota(data.kuota),
                    '.modal-title-detail': `Detail Lowongan - ${data.judul_lowongan || 'Tidak Diketahui'}`
                };

                Object.entries(updates).forEach(([selector, value]) => {
                    $(selector).text(value);
                });

                $('#detail-tipe-kerja').html(formatters.workTypeBadge(data.tipe_kerja_lowongan));
                $('#detail-status').html(formatters.status(data.is_active));
                $('#detail-deskripsi').html(
                    data.deskripsi ?
                    data.deskripsi.replace(/\n/g, '<br>') :
                    '<em class="text-body-secondary">Tidak ada deskripsi</em>'
                );
                $('#detail-persyaratan').html(formatters.requirements(data.persyaratan_magang));
                $('#detail-keahlian').html(formatters.skills(data.keahlian_lowongan));

                const dokumenList = (data.persyaratan_magang?.dokumen_persyaratan || '').split(';').map(s => s
                .trim()).filter(Boolean);
                const dokumenUl = $('#detail-dokumen-persyaratan');
                dokumenUl.empty();
                if (dokumenList.length > 0) {
                    dokumenList.forEach(dokumen => {
                        dokumenUl.append(
                            `<li><i class=\"fas fa-file-alt me-2 text-success\"></i>${dokumen}</li>`);
                    });
                    $('#dokumen-persyaratan-wrapper').show();
                } else {
                    dokumenUl.append(
                        '<li class="text-body-secondary fst-italic">Tidak ada dokumen persyaratan</li>');
                    $('#dokumen-persyaratan-wrapper').show();
                }
            }

            $(document).on('click', '.edit-btn', function() {
                const url = $(this).data('url');
                const modalElement = document.querySelector('#editLowonganModal');
                const modalHeader = modalElement.querySelector('.modal-header');
                if (modalHeader) {
                    modalHeader.classList.add('text-white', 'header-edit-lowongan');
                    modalHeader.style.background =
                        'linear-gradient(90deg, #f0ac24 0%, #d9951f 60%, #b8791a 100%)';
                    if (!modalHeader.querySelector('.icon-wrapper')) {
                        const iconWrapper = document.createElement('div');
                        iconWrapper.className =
                            'icon-wrapper d-flex align-items-center justify-content-center me-2 text-white';
                        iconWrapper.style.width = '3rem';
                        iconWrapper.style.height = '3rem';
                        iconWrapper.style.background = '#d9951f';
                        iconWrapper.style.borderRadius = '25%';
                        iconWrapper.innerHTML = '<i class="fas fa-pen fa-lg"></i>';
                        modalHeader.insertBefore(iconWrapper, modalHeader.firstChild);
                    }
                }
                modalElement.querySelector('.modal-title').textContent = 'Edit Lowongan Magang';
                const modal = new coreui.Modal(modalElement);

                axios.get(url)
                    .then(response => {
                        const data = response.data;
                        const body = modalElement.querySelector(".modal-body");
                        body.innerHTML = '';
                        body.innerHTML = data.data;

                        const initTagify = () => {
                            const skillLevels = data.tingkat_kemampuan;
                            const skillTags = data.keahlianList;

                            const tagifyInstances = [];
                            const selectedSkills = new Set();

                            skillLevels.forEach(level => {
                                const element = document.getElementById(
                                    `keahlian-${level}`);
                                if (element) {
                                    const tagify = new Tagify(element, {
                                        whitelist: skillTags,
                                        dropdown: {
                                            position: "input",
                                            maxItems: Infinity,
                                            enabled: 0,
                                        },
                                        templates: {
                                            dropdownItemNoMatch() {
                                                return `Nothing Found`;
                                            }
                                        },
                                        enforceWhitelist: true,
                                    });

                                    const initialTags = tagify.value.map(tag => tag.value);
                                    initialTags.forEach(skill => selectedSkills.add(skill));
                                    tagifyInstances.push(tagify);
                                    tagify.on('add', function(e) {
                                        const skill = e.detail.data.value;
                                        selectedSkills.add(skill);
                                        updateAllWhitelists();
                                    });
                                    tagify.on('remove', function(e) {
                                        const skill = e.detail.data.value;
                                        selectedSkills.delete(skill);
                                        updateAllWhitelists();
                                    });
                                }
                            });

                            const updateAllWhitelists = () => {
                                tagifyInstances.forEach(instance => {
                                    const currentTags = instance.value.map(tag => tag
                                        .value);
                                    const currentTagSet = new Set(currentTags);
                                    const availableSkills = skillTags.filter(skill =>
                                        !selectedSkills.has(skill) || currentTagSet
                                        .has(skill)
                                    );

                                    instance.settings.whitelist = availableSkills;
                                    instance.loading(true);
                                    instance.dropdown.show.call(instance,
                                        availableSkills[0] || '');
                                    instance.loading(false);
                                });
                            }
                            updateAllWhitelists();
                        };
                        initTagify();

                        const btnTrue = modalElement.querySelector('#btn-true-yes-no');
                        btnTrue.onclick = () => {
                            const form = modalElement.querySelector('form');
                            form.querySelectorAll('.is-invalid').forEach(input => {
                                input.classList.remove('is-invalid');
                            });

                            axios.put(form.action, new FormData(form), {
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(response => {
                                    modal.hide();
                                    $('#lowonganMagangTable').DataTable().ajax.reload();
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.data.message,
                                        showConfirmButton: true,
                                        timer: 1500
                                    });
                                })
                                .catch(error => {
                                    console.error(error.response);
                                    if (error.response.status === 422) {
                                        let errors = error.response.data.errors;
                                        for (const field in errors) {
                                            const input = $(form).find(
                                                `[name="${field}"]`);
                                            input.addClass('is-invalid');
                                            input.next('.invalid-feedback').text(errors[
                                                field][0]);
                                        }
                                    }
                                    Swal.fire({
                                        icon: 'error',
                                        title: `Error!`,
                                        text: error.response.data.message,
                                    });
                                });
                        };

                        const btnFalse = modalElement.querySelector('#btn-false-yes-no');
                        btnFalse.onclick = () => {
                            modal.hide();
                        };

                        modal.show();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        Swal.fire(`Error!`, error.response.data.message, 'error').then(
                            () => {
                                if (error.status === 406) {
                                    window.location.href =
                                        "{{ route('admin.magang.lowongan.lanjutan', ['id' => ':id']) }}"
                                        .replace(':id', error.response.data.id);
                                }
                            });
                    });
            });

            function showError(message) {
                Swal.fire({
                    title: 'Error!',
                    text: message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }

            let currentLowonganId = null;
            let feedbackData = [];

            $(document).on('click', '#btn-show-feedback', function() {
                currentLowonganId = $('#modalDetailLowongan').data('lowongan-id');
                if (!currentLowonganId) {
                    k
                    const lastViewBtn = $('.view-btn.active');
                    if (lastViewBtn.length) {
                        currentLowonganId = lastViewBtn.data('lowongan-id');
                    }
                }
                if (!currentLowonganId) {
                    Swal.fire('Gagal', 'ID lowongan tidak ditemukan.', 'error');
                    return;
                }
                const detailModal = coreui.Modal.getOrCreateInstance('#modalDetailLowongan');
                detailModal.hide();
                const feedbackModal = coreui.Modal.getOrCreateInstance('#modalFeedbackMahasiswa');
                feedbackModal.show();
                $('#feedback-list-container').show();
                $('#feedback-detail-container').hide();
                $('#btn-back-feedback').hide();
                $('#feedback-list-container').html(
                    '<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i><div>Memuat feedback...</div></div>'
                    );
                $.get(`{{ url('/admin/magang/lowongan') }}/${currentLowonganId}/feedback`, function(res) {
                    feedbackData = res.data || [];
                    if (feedbackData.length > 0) {
                        let html = '';
                        feedbackData.forEach(function(item, idx) {
                            html += `<div class='feedback-card pointer' data-feedback-id='${item.feedback_id}'>
                        <div class='feedback-meta'><i class="fas fa-user-circle me-2"></i>${item.mahasiswa}</div>
                        <div class='small'>${item.created_at ?? ''}</div>
                    </div>`;
                        });
                        $('#feedback-list-container').html(html);
                    } else {
                        $('#feedback-list-container').html(
                            '<div class="alert alert-warning">Belum ada feedback dari mahasiswa.</div>'
                            );
                    }
                }).fail(function(xhr) {
                    $('#feedback-list-container').html(
                        '<div class="alert alert-danger">Gagal memuat feedback.</div>');
                });
            });

            $(document).on('click', '.feedback-card', function() {
                const feedbackId = $(this).data('feedback-id');
                const item = feedbackData.find(f => f.feedback_id == feedbackId);
                if (!item) return;
                let html = '';
                html += `<div class='card mb-3 shadow-sm border-1'>
                    <div class='card-body'>
                        <div class='feedback-detail-title fw-semibold mb-1'><i class="fas fa-user-circle me-2"></i>Nama Mahasiswa</div>
                        <div class='feedback-detail-content'>${item.mahasiswa}</div>
                    </div>
                </div>`;
                html += `<div class='card mb-3 shadow-sm border-1'>
                    <div class='card-body'>
                        <div class='feedback-detail-title mb-1'><i class="fas fa-star me-2"></i>Rating</div>
                        <div class='feedback-detail-content'>${renderStars(item.rating)}</div>
                    </div>
                </div>`;
                html += `<div class='card mb-3 shadow-sm border-1'>
                    <div class='card-body'>
                        <div class='feedback-detail-title text-info mb-1'><i class="fas fa-comment-dots me-2"></i>Komentar</div>
                        <div class='feedback-detail-content'>${item.komentar || '-'}</div>
                    </div>
                </div>`;
                html += `<div class='card mb-3 shadow-sm border-1'>
                    <div class='card-body'>
                        <div class='feedback-detail-title text-success mb-1'><i class="fas fa-graduation-cap me-2"></i>Pengalaman Belajar</div>
                        <div class='feedback-detail-content'>${item.pengalaman_belajar || '-'}</div>
                    </div>
                </div>`;
                html += `<div class='card mb-3 shadow-sm border-1'>
                    <div class='card-body'>
                        <div class='feedback-detail-title text-danger mb-1'><i class="fas fa-exclamation-triangle me-2"></i>Kendala</div>
                        <div class='feedback-detail-content'>${item.kendala || '-'}</div>
                    </div>
                </div>`;
                html += `<div class='card mb-3 shadow-sm border-0'>
                    <div class='card-body'>
                        <div class='feedback-detail-title text-secondary mb-1'><i class="fas fa-lightbulb me-2"></i>Saran</div>
                        <div class='feedback-detail-content'>${item.saran || '-'}</div>
                    </div>
                </div>`;

                $('#feedback-detail-container').html(html).show();
                $('#feedback-list-container').hide();
                $('#btn-back-feedback').show();
            });

            $(document).on('click', '#btn-back-feedback', function() {
                $('#feedback-detail-container').hide();
                $('#feedback-list-container').show();
                $('#btn-back-feedback').hide();
            });

            $(document).on('click', '.view-btn', function() {
                const url = $(this).data('url');
                const id = url.match(/\/(\d+)$/);
                if (id && id[1]) {
                    $('#modalDetailLowongan').data('lowongan-id', id[1]);
                }
                $('.view-btn').removeClass('active');
                $(this).addClass('active');
            });
        });

        function renderStars(rating) {
            const labels = {
                1: 'Sangat Tidak Puas',
                2: 'Tidak Puas',
                3: 'Netral',
                4: 'Puas',
                5: 'Sangat Puas'
            };
            let html = '<span title="' + (labels[rating] || '') + '">';
            for (let i = 1; i <= 5; i++) {
                html += `<i class=\"fas fa-star${i <= rating ? ' text-warning' : ' text-secondary'}\"></i>`;
            }
            html += ` <span class='ms-2 small text-muted'>${labels[rating] || ''}</span></span>`;
            return html;
        }
    </script>
@endpush
