<table class="table">
    <tr>
        <th style="width: 30%;">Nama Kategori</th>
        <td>{{ $kategori->nama_kategori }}</td>
    </tr>
    <tr>
        <th>Deskripsi</th>
        <td>
            @if ($kategori->deskripsi)
                {{ $kategori->deskripsi }}
            @else
                <span class="text-muted"><em>Tidak ada deskripsi</em></span>
            @endif
        </td>
    </tr>
</div>
