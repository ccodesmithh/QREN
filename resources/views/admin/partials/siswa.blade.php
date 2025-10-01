<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="card-title mb-0">Manajemen Siswa</h5>
    <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary btn-sm">Tambah Siswa</a>
</div>
<form method="GET" action="{{ route('admin.manage') }}" class="mb-3">
    <input type="hidden" name="tab" value="siswa">
    <div class="row mb-3">
        <div class="col-md-12">
            <label for="search">Cari Siswa</label>
            <input type="text" name="search" id="search" class="form-control" placeholder="Search by NISN, Name, Kelas, Jurusan..." value="{{ $request->search }}">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <label for="kelas_id">Filter by Kelas</label>
            <select name="kelas_id" id="kelas_id" class="form-control">
                <option value="">Semua Kelas</option>
                @foreach($kelas as $k)
                    <option value="{{ $k->id }}" {{ $request->kelas_id == $k->id ? 'selected' : '' }}>{{ $k->kelas }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label for="jurusan_id">Filter by Jurusan</label>
            <select name="jurusan_id" id="jurusan_id" class="form-control">
                <option value="">Semua Jurusan</option>
                @foreach($jurusans as $j)
                    <option value="{{ $j->id }}" {{ $request->jurusan_id == $j->id ? 'selected' : '' }}>{{ $j->jurusan }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-secondary mr-2">Apply Filters</button>
            <a href="{{ route('admin.manage') }}#siswa" class="btn btn-outline-secondary">Reset</a>
        </div>
    </div>
</form>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>NISN</th>
                <th>Name</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswas as $siswa)
            <tr>
                <td>{{ $siswa->nisn }}</td>
                <td>{{ $siswa->name }}</td>
                <td>{{ $siswa->kelas->kelas ?? '-' }}</td>
                <td>{{ $siswa->jurusan->jurusan ?? '-' }}</td>
                <td>
                    <a href="{{ route('admin.siswa.edit', $siswa) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form method="POST" action="{{ route('admin.siswa.destroy', $siswa) }}" style="display:inline;">
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
