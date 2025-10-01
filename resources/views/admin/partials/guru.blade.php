<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="card-title mb-0">Manajemen Guru</h5>
    <a href="{{ route('admin.guru.create') }}" class="btn btn-primary btn-sm">Tambah Guru</a>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID Guru</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gurus as $guru)
            <tr>
                <td>{{ $guru->idguru }}</td>
                <td>{{ $guru->name }}</td>
                <td>
                    <a href="{{ route('admin.guru.edit', $guru) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form method="POST" action="{{ route('admin.guru.destroy', $guru) }}" style="display:inline;">
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
