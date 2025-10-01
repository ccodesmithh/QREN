@extends('layouts.dashboard.index')

@section('sidebar')
    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link active" id="dashboard-tab" onclick="showTab('settings')">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Settings -->
    <li class="nav-item">
        <a class="nav-link" id="settings-link" onclick="showTab('settings')">
            <i class="fas fa-fw fa-cog"></i>
            <span>Settings</span></a>
    </li>

    <!-- Siswa -->
    <li class="nav-item">
        <a class="nav-link" id="siswa-link" onclick="showTab('siswa')">
            <i class="fas fa-fw fa-user-graduate"></i>
            <span>Siswa</span></a>
    </li>

    <!-- Guru -->
    <li class="nav-item">
        <a class="nav-link" id="guru-link" onclick="showTab('guru')">
            <i class="fas fa-fw fa-chalkboard-teacher"></i>
            <span>Guru</span></a>
    </li>

    <!-- Kelas -->
    <li class="nav-item">
        <a class="nav-link" id="kelas-link" onclick="showTab('kelas')">
            <i class="fas fa-fw fa-building"></i>
            <span>Kelas</span></a>
    </li>

    <!-- Jurusan -->
    <li class="nav-item">
        <a class="nav-link" id="jurusan-link" onclick="showTab('jurusan')">
            <i class="fas fa-fw fa-graduation-cap"></i>
            <span>Jurusan</span></a>
    </li>

    <!-- Attendance -->
    <li class="nav-item">
        <a class="nav-link" id="attendance-link" onclick="showTab('attendance')">
            <i class="fas fa-fw fa-clipboard-list"></i>
            <span>Attendance</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Logout -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.logout') }}">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Logout</span></a>
    </li>
@endsection
@section('content')
<div class="container">
    <h1>Admin Dashboard</h1>
    <p>Kelola data siswa, guru, kelas, jurusan, dan pengaturan sistem.</p>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    

    <div class="tab-content" id="adminTabsContent">
        <div class="tab-pane fade show active" id="settings" role="tabpanel" aria-labelledby="settings-tab" style="display: block;">
            <div class="card mt-3">
                <div class="card-header">
                    <h5>Settings</h5>
                    <p class="text-danger">QREN Menggunakan teknologi Geolocation, yang mungkin tidak sepenuhnya akurat terutama pada lingkup indoor. Toleransi nilai: 40-50 meter</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <h6>QR Code Regeneration Countdown:</h6>
                            <div id="qrCountdown" style="font-size: 1.5rem; font-weight: bold; color: #007bff;">Loading...</div>
                            <button type="button" id="regenerateNowBtn" class="btn btn-sm btn-primary mt-2">Regenerate Now</button>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="radius">Radius Scanning (meters)</label>
                                    <input type="number" class="form-control" id="radius" name="radius" value="{{ $settings['radius'] }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="geolocation_timeout">Geolocation Timeout (ms)</label>
                                    <input type="number" class="form-control" id="geolocation_timeout" name="geolocation_timeout" value="{{ $settings['geolocation_timeout'] }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="max_age">Max Age (ms)</label>
                                    <input type="number" class="form-control" id="max_age" name="max_age" value="{{ $settings['max_age'] }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="enable_high_accuracy">Enable High Accuracy</label>
                                    <select class="form-control" id="enable_high_accuracy" name="enable_high_accuracy" required>
                                        <option value="true" {{ $settings['enable_high_accuracy'] == 'true' ? 'selected' : '' }}>True</option>
                                        <option value="false" {{ $settings['enable_high_accuracy'] == 'false' ? 'selected' : '' }}>False</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="scan_cooldown">Scan Cooldown (seconds)</label>
                                    <input type="number" class="form-control" id="scan_cooldown" name="scan_cooldown" value="{{ $settings['scan_cooldown'] }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="geolocation_update_interval">Geolocation Update Interval (minutes)</label>
                                    <input type="number" class="form-control" id="geolocation_update_interval" name="geolocation_update_interval" value="{{ $settings['geolocation_update_interval'] }}" required min="1">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Settings</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="siswa" role="tabpanel" aria-labelledby="siswa-tab" style="display: none;">
            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between">
                    <h5>Siswa</h5>
                    <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary">Tambah Siswa</a>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-3">
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
                                <a href="{{ route('admin.dashboard') }}#siswa" class="btn btn-outline-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <div class="card-body">
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
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="guru" role="tabpanel" aria-labelledby="guru-tab" style="display: none;">
            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between">
                    <h5>Guru</h5>
                    <a href="{{ route('admin.guru.create') }}" class="btn btn-primary">Tambah Guru</a>
                </div>
                <div class="card-body">
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
            </div>
        </div>

        <div class="tab-pane fade" id="kelas" role="tabpanel" aria-labelledby="kelas-tab" style="display: none;">
            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between">
                    <h5>Kelas</h5>
                    <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary">Tambah Kelas</a>
                </div>
                <div class="card-body">
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
            </div>
        </div>

        <div class="tab-pane fade" id="jurusan" role="tabpanel" aria-labelledby="jurusan-tab" style="display: none;">
            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between">
                    <h5>Jurusan</h5>
                    <a href="{{ route('admin.jurusan.create') }}" class="btn btn-primary">Tambah Jurusan</a>
                </div>
                <div class="card-body">
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
            </div>
        </div>

        <div class="tab-pane fade" id="attendance" role="tabpanel" aria-labelledby="attendance-tab" style="display: none;">
            <div class="card mt-3">
                <div class="card-header">
                    <h5>Attendance</h5>
                </div>
                <div class="table-responsive">
                    <div class="card-body">
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
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabId) {
            // Hide all tab panes
            var tabPanes = document.querySelectorAll('.tab-pane');
            tabPanes.forEach(function(pane) {
                pane.classList.remove('show', 'active');
                pane.style.display = 'none';
            });

            // Show selected tab pane
            var selectedPane = document.getElementById(tabId);
            if (selectedPane) {
                selectedPane.classList.add('show', 'active');
                selectedPane.style.display = 'block';
            }

            // Update sidebar active state
            var sidebarLinks = document.querySelectorAll('.nav-item .nav-link');
            sidebarLinks.forEach(function(link) {
                link.classList.remove('active');
            });

            // Add active to clicked link
            event.target.classList.add('active');
        }

        // Set initial active sidebar link
        document.addEventListener('DOMContentLoaded', function() {
            var settingsLink = document.getElementById('settings-link');
            var siswaLink = document.getElementById('siswa-link');
            if ({{ $request->has('kelas_id') || $request->has('jurusan_id') || $request->has('search') ? 'true' : 'false' }}) {
                showTab('siswa');
                if (siswaLink) {
                    siswaLink.classList.add('active');
                }
            } else {
                if (settingsLink) {
                    settingsLink.classList.add('active');
                }
            }

            // Countdown timer for QR code regeneration
            var countdownElement = document.getElementById('qrCountdown');
            var regenerateBtn = document.getElementById('regenerateNowBtn');
            var geolocationUpdateInterval = {{ $settings['geolocation_update_interval'] ?? 5 }}; // minutes
            var timeLeft = geolocationUpdateInterval * 60; // convert to seconds

            function updateCountdown() {
                var minutes = Math.floor(timeLeft / 60);
                var seconds = timeLeft % 60;
                countdownElement.textContent = minutes + ':' + (seconds < 10 ? '0' : '') + seconds + ' until next QR regeneration';
                if (timeLeft <= 0) {
                    // Trigger regeneration event
                    regenerateQrCode();
                    timeLeft = geolocationUpdateInterval * 60; // reset timer
                } else {
                    timeLeft--;
                }
            }

            function regenerateQrCode() {
                // Make AJAX call to regenerate QR codes
                fetch('{{ route("admin.regenerate.qr-codes") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('QR codes regenerated successfully:', data.message);
                        // Show success message
                        alert('QR codes regenerated successfully! ' + data.count + ' codes updated.');
                    } else {
                        console.error('Failed to regenerate QR codes:', data.error);
                        alert('Failed to regenerate QR codes. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error regenerating QR codes:', error);
                    alert('Error regenerating QR codes. Please check the console for details.');
                });
            }

            regenerateBtn.addEventListener('click', function() {
                regenerateQrCode();
                timeLeft = geolocationUpdateInterval * 60; // reset timer on manual trigger
            });

            updateCountdown();
            setInterval(updateCountdown, 1000);
        });
    </script>
</div>
@endsection
