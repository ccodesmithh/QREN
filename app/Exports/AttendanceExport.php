<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;
    protected $guruId;

    public function __construct($filters, $guruId)
    {
        $this->filters = $filters;
        $this->guruId = $guruId;
    }

    public function collection()
    {
        $query = Attendance::with(['siswa', 'qrcode.ajar.kelas', 'qrcode.ajar.jurusan', 'qrcode.ajar.mapel'])
            ->where('guru_id', $this->guruId);

        // Apply filters
        if (isset($this->filters['kelas_id']) && $this->filters['kelas_id']) {
            $query->whereHas('qrcode.ajar', function($q) {
                $q->where('kelas_id', $this->filters['kelas_id']);
            });
        }

        if (isset($this->filters['jurusan_id']) && $this->filters['jurusan_id']) {
            $query->whereHas('qrcode.ajar', function($q) {
                $q->where('jurusan_id', $this->filters['jurusan_id']);
            });
        }

        if (isset($this->filters['nama_siswa']) && $this->filters['nama_siswa']) {
            $query->whereHas('siswa', function($q) {
                $q->where('name', 'like', '%' . $this->filters['nama_siswa'] . '%');
            });
        }

        if (isset($this->filters['selected_dates']) && $this->filters['selected_dates']) {
            $dates = explode(',', $this->filters['selected_dates']);
            $query->whereIn(\DB::raw('DATE(scanned_at)'), $dates);
        } elseif (isset($this->filters['start_date']) && $this->filters['start_date']) {
            $query->whereDate('scanned_at', '>=', $this->filters['start_date']);
        }

        if (isset($this->filters['end_date']) && $this->filters['end_date']) {
            $query->whereDate('scanned_at', '<=', $this->filters['end_date']);
        }

        $collection = $query->orderBy('scanned_at', 'asc')->get();
        $collection->transform(function ($item, $key) {
            $item->row_number = $key + 1;
            return $item;
        });

        return $collection;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'Kelas',
            'Jurusan',
            'Mata Pelajaran',
            'Jam Awal - Akhir',
            'Status',
            'Radius (m)',
            'Tanggal Scan',
            'Waktu Scan'
        ];
    }

    public function map($row): array
    {
        return [
            $row->row_number,
            $row->siswa->name ?? '-',
            $row->qrcode->ajar->kelas->nama_kelas ?? '-',
            $row->qrcode->ajar->jurusan->nama_jurusan ?? '-',
            $row->qrcode->ajar->mapel->nama_mapel ?? '-',
            ($row->qrcode->ajar->jam_awal ?? '-') . ' - ' . ($row->qrcode->ajar->jam_akhir ?? '-'),
            ucfirst($row->status),
            $row->distance ? number_format($row->distance, 2) : '-',
            $row->scanned_at ? $row->scanned_at->format('d-m-Y') : '-',
            $row->scanned_at ? $row->scanned_at->format('H:i:s') : '-'
        ];
    }
}
