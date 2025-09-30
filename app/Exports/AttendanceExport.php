<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\Journal;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $filters;
    protected $guruId;
    protected $summary = [
        'hadir' => 0,
        'sakit' => 0,
        'izin' => 0,
        'alpha' => 0,
    ];
    protected $journals = [];

    public function __construct($filters, $guruId)
    {
        $this->filters = $filters;
        $this->guruId = $guruId;
    }

    public function collection()
    {
        $query = Attendance::with(['siswa.kelas', 'siswa.jurusan', 'qrcode.ajar.kelas', 'qrcode.ajar.jurusan', 'qrcode.ajar.mapel'])
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
            $query->whereIn(DB::raw('DATE(scanned_at)'), $dates);
        } elseif (isset($this->filters['start_date']) && $this->filters['start_date']) {
            $query->whereDate('scanned_at', '>=', $this->filters['start_date']);
        }

        if (isset($this->filters['end_date']) && $this->filters['end_date']) {
            $query->whereDate('scanned_at', '<=', $this->filters['end_date']);
        }

        $collection = $query->orderBy('scanned_at', 'asc')->get();

        // Prepare journals keyed by ajar_id and date
        $ajarIds = $collection->pluck('qrcode.ajar.id')->unique()->toArray();
        $dates = $collection->pluck('scanned_at')->filter()->map->toDateString()->unique()->toArray();

        $journals = Journal::where('guru_id', $this->guruId)
            ->whereIn('ajar_id', $ajarIds)
            ->whereIn('date', $dates)
            ->get()
            ->keyBy(function($j) {
                return $j->ajar_id . '-' . $j->date->toDateString();
            });

        $this->journals = $journals;

        // Add row_number and count summary
        $collection->transform(function ($item, $key) {
            $item->row_number = $key + 1;

            // Count status for summary
            $status = strtolower($item->status);
            if (array_key_exists($status, $this->summary)) {
                $this->summary[$status]++;
            }

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
            'Waktu Scan',
            'Jurnal'
        ];
    }

    public function map($row): array
    {
        $ajar = $row->qrcode->ajar ?? null;
        $key = $ajar ? $ajar->id . '-' . ($row->scanned_at ? $row->scanned_at->format('Y-m-d') : '') : null;
        $journalContent = $key && isset($this->journals[$key]) ? $this->journals[$key]->content : '-';

        return [
            $row->row_number,
            $row->siswa->name ?? '-',
            ($row->siswa->kelas ? $row->siswa->kelas->kelas : ($ajar->kelas->kelas ?? '-')),
            ($row->siswa->jurusan ? $row->siswa->jurusan->jurusan : ($ajar->jurusan->jurusan ?? '-')),
            $ajar->mapel->nama_mapel ?? '-',
            ($ajar->jam_awal ?? '-') . ' - ' . ($ajar->jam_akhir ?? '-'),
            ucfirst($row->status),
            $row->distance ? number_format($row->distance, 2) : '-',
            $row->scanned_at ? $row->scanned_at->format('d-m-Y') : '-',
            $row->scanned_at ? $row->scanned_at->format('H:i:s') : '-',
            $journalContent
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow() + 1;

                // Add summary row label
                $sheet->setCellValue('A' . $lastRow, 'Rekapitulasi:');

                // Add summary counts
                $sheet->setCellValue('B' . $lastRow, 'Hadir: ' . $this->summary['hadir']);
                $sheet->setCellValue('C' . $lastRow, 'Sakit: ' . $this->summary['sakit']);
                $sheet->setCellValue('D' . $lastRow, 'Izin: ' . $this->summary['izin']);
                $sheet->setCellValue('E' . $lastRow, 'Alpha: ' . $this->summary['alpha']);
            },
        ];
    }
}
