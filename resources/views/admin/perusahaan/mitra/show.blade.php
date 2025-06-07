 <table class="table ">
     <tr>
         <th style="width: 30%;">Lokasi</th>
         <td>
             <a href="https://maps.google.com/?q={{ $perusahaan->lokasi->latitude }},{{ $perusahaan->lokasi->longitude }}"
                 target="_blank">
                 {{ $perusahaan->lokasi->alamat }}
             </a>
         </td>
     </tr>
     <tr>
         <th>Nama Perusahaan</th>
         <td>{{ $perusahaan->nama_perusahaan ?? '-' }}</td>
     </tr>
     <tr>
         <th>Bidang Industri</th>
         <td>{{ $perusahaan->bidangIndustri->nama ?? '-' }}</td>
     </tr>
     <tr>
         <th>Website</th>
         <td>{{ $perusahaan->website ?? '-' }}</td>
     </tr>
     <tr>
         <th>Email</th>
         <td>{{ $perusahaan->kontak_email ?? '-' }}</td>
     </tr>
     <tr>
         <th>Telepon</th>
         <td>{{ $perusahaan->kontak_telepon ?? '-' }}</td>
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
