<table class="table">
    <tr>
        <th>Keahlian ID</th>
        <td>{{ $tag_keahlian->keahlian_id }}</td>
    </tr>
    <tr>
        <th style="width: 30%;">Nama Keahlian</th>
        <td>{{ $tag_keahlian->nama_keahlian }}</td>
    </tr>
    <tr>
        <th>Nama Kategori</th>
        <td>{{ $tag_keahlian->kategori->nama_kategori }}</td>
    </tr>
    <tr>
        <th>Deskripsi</th>
        <td>
            @if ($tag_keahlian->deskripsi)
                {{ $tag_keahlian->deskripsi }}
            @else
                <span class="text-muted"><em>Tidak ada deskripsi</em></span>
            @endif
        </td>
    </tr>
</table>
