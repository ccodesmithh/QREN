<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="card-title mb-0">Manajemen Jurusan</h5>
    <a href="{{ route('admin.jurusan.create') }}" class="btn btn-primary btn-sm">Tambah Jurusan</a>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Jurusan</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jurusans as $j)
            <tr>
                <td>{{ $j->jurusan }}</td>
                <td>
                    <a href="{{ route('admin.jurusan.edit', $j) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form method="POST" action="{{ route('admin.jurusan.destroy', $j) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
