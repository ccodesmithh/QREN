<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Journal;
use App\Models\Ajar;

class JournalController extends Controller
{
    public function index()
    {
        $guru = Auth::guard('guru')->user();
        $journals = Journal::where('guru_id', $guru->id)
            ->with('ajar.mapel', 'ajar.kelas', 'ajar.jurusan')
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('guru.journal.index', compact('journals'));
    }

    public function create(Request $request)
    {
        $guru = Auth::guard('guru')->user();
        $ajars = $guru->ajars()->with('mapel', 'kelas', 'jurusan')->get();

        $selectedAjar = null;
        $selectedDate = $request->date ?? now()->toDateString();

        if ($request->ajar_id) {
            $selectedAjar = Ajar::find($request->ajar_id);
        }

        return view('guru.journal.create', compact('ajars', 'selectedAjar', 'selectedDate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ajar_id' => 'required|exists:ajars,id',
            'date' => 'required|date',
            'content' => 'required|string',
        ]);

        $guru = Auth::guard('guru')->user();

        // Check if journal already exists for this guru, ajar, and date
        $existing = Journal::where('guru_id', $guru->id)
            ->where('ajar_id', $request->ajar_id)
            ->where('date', $request->date)
            ->first();

        if ($existing) {
            return back()->withErrors(['date' => 'Journal sudah ada untuk mata pelajaran dan tanggal ini.']);
        }

        Journal::create([
            'guru_id' => $guru->id,
            'ajar_id' => $request->ajar_id,
            'date' => $request->date,
            'content' => $request->content,
        ]);

        return redirect()->route('guru.journal.index')->with('success', 'Journal berhasil dibuat.');
    }

    public function show(Journal $journal)
    {
        $this->authorize('view', $journal); // Ensure guru owns the journal

        return view('guru.journal.show', compact('journal'));
    }

    public function edit(Journal $journal)
    {
        $this->authorize('update', $journal); // Ensure guru owns the journal

        $guru = Auth::guard('guru')->user();
        $ajars = $guru->ajars()->with('mapel', 'kelas', 'jurusan')->get();

        return view('guru.journal.edit', compact('journal', 'ajars'));
    }

    public function update(Request $request, Journal $journal)
    {
        $this->authorize('update', $journal); // Ensure guru owns the journal

        $request->validate([
            'ajar_id' => 'required|exists:ajars,id',
            'date' => 'required|date',
            'content' => 'required|string',
        ]);

        $guru = Auth::guard('guru')->user();

        // Check if another journal exists for this guru, ajar, and date (excluding current journal)
        $existing = Journal::where('guru_id', $guru->id)
            ->where('ajar_id', $request->ajar_id)
            ->where('date', $request->date)
            ->where('id', '!=', $journal->id)
            ->first();

        if ($existing) {
            return back()->withErrors(['date' => 'Journal sudah ada untuk mata pelajaran dan tanggal ini.']);
        }

        $journal->update([
            'ajar_id' => $request->ajar_id,
            'date' => $request->date,
            'content' => $request->content,
        ]);

        return redirect()->route('guru.journal.index')->with('success', 'Journal berhasil diperbarui.');
    }

    public function destroy(Journal $journal)
    {
        $this->authorize('delete', $journal); // Ensure guru owns the journal

        $journal->delete();

        return redirect()->route('guru.journal.index')->with('success', 'Journal berhasil dihapus.');
    }
}
