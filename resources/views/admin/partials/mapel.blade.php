<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="card-title mb-0">Manajemen Mata Pelajaran</h5>
    <a href="{{ route('admin.mapel.create') }}" class="btn btn-primary btn-sm">Tambah Mata Pelajaran</a>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Mata Pelajaran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mapels as $mapel)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $mapel->nama_mapel }}</td>
                    <td>
                        <a href="{{ route('admin.mapel.edit', $mapel->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.mapel.destroy', $mapel->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Tidak ada data mata pelajaran.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
