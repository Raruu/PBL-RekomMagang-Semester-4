@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Detail Perusahaan</h5>
                        <a href="{{ url('/admin/perusahaan/') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                            <div class="col-md-8">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 30%;">Lokasi</th>
                                        <td>{{ $perusahaan->lokasi->alamat }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nama Perusahaan</th>
                                        <td>{{ $perusahaan->nama_perusahaan }}</td>
                                    </tr>
                                    <tr>
                                        <th>Bidang Industri</th>
                                        <td>{{ $perusahaan->bidang_industri }}</td>
                                    </tr>
                                    <tr>
                                        <th>Website</th>
                                        <td>{{ $perusahaan->website }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $perusahaan->kontak_email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Telepon</th>
                                        <td>{{ $perusahaan->kontak_telepon }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span class="badge bg-{{ $perusahaan->is_active ? 'success' : 'danger' }}">
                                                {{ $perusahaan->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
