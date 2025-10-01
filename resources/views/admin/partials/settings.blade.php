<h5 class="card-title">Settings</h5>
<p class="text-danger small">QREN Menggunakan teknologi Geolocation, yang mungkin tidak sepenuhnya akurat terutama pada lingkup indoor. Toleransi nilai: 40-50 meter</p>
<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf
    @method('PATCH')
    <div class="row">
        <div class="col-md-6"><div class="form-group mb-3"><label for="radius">Radius Scanning (meters)</label><input type="number" class="form-control" id="radius" name="radius" value="{{ $settings['radius'] }}" required></div></div>
        <div class="col-md-6"><div class="form-group mb-3"><label for="geolocation_timeout">Geolocation Timeout (ms)</label><input type="number" class="form-control" id="geolocation_timeout" name="geolocation_timeout" value="{{ $settings['geolocation_timeout'] }}" required></div></div>
        <div class="col-md-6"><div class="form-group mb-3"><label for="max_age">Max Age (ms)</label><input type="number" class="form-control" id="max_age" name="max_age" value="{{ $settings['max_age'] }}" required></div></div>
        <div class="col-md-6"><div class="form-group mb-3"><label for="enable_high_accuracy">Enable High Accuracy</label><select class="form-control" id="enable_high_accuracy" name="enable_high_accuracy" required><option value="true" {{ $settings['enable_high_accuracy'] == 'true' ? 'selected' : '' }}>True</option><option value="false" {{ $settings['enable_high_accuracy'] == 'false' ? 'selected' : '' }}>False</option></select></div></div>
        <div class="col-md-6"><div class="form-group mb-3"><label for="scan_cooldown">Scan Cooldown (seconds)</label><input type="number" class="form-control" id="scan_cooldown" name="scan_cooldown" value="{{ $settings['scan_cooldown'] }}" required></div></div>
    </div>
    <button type="submit" class="btn btn-primary">Update Settings</button>
</form>
