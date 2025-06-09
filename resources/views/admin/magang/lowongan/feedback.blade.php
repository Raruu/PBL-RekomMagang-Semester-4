<div class="modal fade" id="modalFeedbackMahasiswa" tabindex="-1" aria-labelledby="modalFeedbackMahasiswaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-feedback text-white">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper me-3">
                        <i class="fas fa-comments text-white fs-3"></i>
                    </div>
                    <div>
                        <h5 class="modal-title feedback fw-semibold mb-0" id="modalFeedbackMahasiswaLabel">Feedback Magang Mahasiswa</h5>
                        <small style="color: #f3e8ff; opacity: 0.8">Daftar dan detail feedback mahasiswa untuk lowongan ini</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="feedback-list-container">
                    <!-- isi -->
                </div>
                <div id="feedback-detail-container" style="display:none;">
                    <!-- isi -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-outline-primary" id="btn-back-feedback" style="display:none;">Kembali ke Daftar Mahasiswa</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    #modalFeedbackMahasiswa .modal-header.bg-gradient-feedback {
        background: linear-gradient(135deg, #a21caf 0%, #7c3aed 100%) !important;
    }
    #modalFeedbackMahasiswa .icon-wrapper .fa-comments {
        color: #a21caf !important;
    }
    .feedback-card {
        border: 1px solid #e0e0e0;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        padding: 1.25rem 1.5rem;
        cursor: pointer;
        transition: box-shadow 0.2s;
    }
    .feedback-card:hover {
        box-shadow: 0 4px 16px rgba(76, 70, 229, 0.10);
        background: #f8fafd;
        transition: ease-in 0.2s;
        color: #7c3aed;
    }
    .feedback-meta {
        font-size: 1.05rem;
        font-weight: 600;
    }
    .feedback-detail-section {
        margin-bottom: 1.25rem;
    }
    .feedback-detail-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    .feedback-detail-content {
        margin-bottom: 0.75rem;
    }
    .feedback-rating {
        font-size: 1.25rem;
        color: #f59e42;
        margin-bottom: 0.5rem;
    }
    .pointer {
        cursor: pointer !important;
    }
    #modalFeedbackMahasiswa .modal-title.feedback {
        color: #fff;
        letter-spacing: 0.5px;
    }
</style>
@endpush
