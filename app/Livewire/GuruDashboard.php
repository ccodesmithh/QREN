<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ajar;
use App\Models\QrCode;
use Illuminate\Support\Facades\Auth;

class GuruDashboard extends Component
{
    public $ajars = [];



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



    public function render()
    {
        return view('livewire.guru-dashboard');
    }
}
