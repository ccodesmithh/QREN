<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="card-title mb-0">Manajemen Kelas</h5>
    <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary btn-sm">Tambah Kelas</a>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Kelas</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kelas as $k)
            <tr>
                <td>{{ $k->kelas }}</td>
                <td>
                    <a href="{{ route('admin.kelas.edit', $k) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form method="POST" action="{{ route('admin.kelas.destroy', $k) }}" style="display:inline;">
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
