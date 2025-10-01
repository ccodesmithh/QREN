<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ajar;
use App\Models\QrCode;
use Illuminate\Support\Facades\Auth;

class GuruDashboard extends Component
{
    public $ajars = [];

    protected $listeners = [
        'qrCodeUpdated' => 'refreshQrCodes',
    ];

    protected function getListeners()
    {
        return [
            'qrCodeUpdated' => 'refreshQrCodes',
            'echo:guru.' . Auth::guard('guru')->id() . ',GeolocationUpdateNotification' => 'refreshQrCodes',
        ];
    }

    public function mount()
    {
        $this->loadAjars();
    }

    public function loadAjars()
    {
        $guru = Auth::guard('guru')->user();
        $this->ajars = $guru->ajars()
            ->with('kelas', 'jurusan', 'mapel', 'qrcode')
            ->whereHas('qrcode')
            ->get()
            ->toArray();
    }

    public function refreshQrCodes()
    {
        $this->loadAjars();
        // Emit event to frontend to refresh QR codes
        $this->dispatch('qrCodeUpdated');
    }

    public function render()
    {
        return view('livewire.guru-dashboard');
    }
}
