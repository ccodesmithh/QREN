<?php

namespace App\Policies;

use App\Models\Guru;
use App\Models\Journal;
use Illuminate\Auth\Access\HandlesAuthorization;

class JournalPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the guru can view the journal.
     */
    public function view(Guru $guru, Journal $journal): bool
    {
        return $guru->id === $journal->guru_id;
    }

    /**
     * Determine whether the guru can update the journal.
     */
    public function update(Guru $guru, Journal $journal): bool
    {
        return $guru->id === $journal->guru_id;
    }

    /**
     * Determine whether the guru can delete the journal.
     */
    public function delete(Guru $guru, Journal $journal): bool
    {
        return $guru->id === $journal->guru_id;
    }
}
