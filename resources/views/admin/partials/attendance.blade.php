<h5 class="card-title">Daftar Absensi</h5>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Siswa</th>
                <th>Ajar</th>
                <th>Status</th>
                <th>Distance</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $att)
            <tr>
                <td>{{ $att->siswa->name ?? '-' }}</td>
                <td>{{ $att->ajar->mapel->name ?? '-' }} - {{ $att->ajar->guru->name ?? '-' }}</td>
                <td>{{ $att->status }}</td>
                <td>{{ $att->distance }}</td>
                <td>{{ $att->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
