<div class="card card-custom student-info-card gutter-b">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
        <h3 class="card-title mb-0">
            <i class="fas fa-history text-info mr-2"></i>Intake History
        </h3>
        <div class="card-toolbar">
            @if(!empty($canCarryOverIntake))
                <button type="button" class="btn btn-sm btn-light-primary font-weight-bold" data-toggle="modal" data-target="#carryOverIntakeModal">
                    <i class="fas fa-exchange-alt mr-1"></i> Carry to Next Intake
                </button>
            @endif
        </div>
    </div>
    <div class="card-body pt-2 pb-4">
        <div class="mb-4">
            <span class="text-muted font-size-sm d-block">Permanent Student ID</span>
            <span class="font-weight-bolder text-dark">{{ $student->st_office_id ?: '—' }}</span>
            <span class="text-muted mx-2">·</span>
            <span class="text-muted font-size-sm">Current intake:</span>
            <span class="font-weight-bold">{{ $student->intake ?: '—' }}</span>
        </div>

        <div class="table-responsive mb-6">
            <table class="table table-head-custom table-vertical-center mb-0">
                <thead>
                    <tr>
                        <th>Intake</th>
                        <th>Status</th>
                        <th>Outcome</th>
                        <th>Started</th>
                        <th>Closed</th>
                        <th>Active</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($intakeAttempts ?? collect()) as $attempt)
                        <tr class="{{ $attempt->is_active ? 'table-light' : '' }}">
                            <td class="font-weight-bold">{{ $attempt->intake ?: '—' }}</td>
                            <td>{{ optional($attempt->status)->name ?: '—' }}</td>
                            <td>{{ $attempt->outcome_label }}</td>
                            <td>{{ $attempt->started_at ? $attempt->started_at->format('d M Y') : '—' }}</td>
                            <td>{{ $attempt->closed_at ? $attempt->closed_at->format('d M Y') : '—' }}</td>
                            <td>
                                @if($attempt->is_active)
                                    <span class="label label-light-success label-inline">Yes</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted text-center py-6">No intake attempts yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
            <h5 class="font-weight-bolder mb-0">Attempt Notes Timeline</h5>
            @if(!empty($canCarryOverIntake) && ($intakeAttempts ?? collect())->isNotEmpty())
                <button type="button" class="btn btn-sm btn-light-info font-weight-bold" data-toggle="modal" data-target="#addIntakeNoteModal">
                    <i class="fas fa-plus mr-1"></i> Add Note
                </button>
            @endif
        </div>

        @php
            $timelineNotes = collect($intakeAttempts ?? [])
                ->flatMap(function ($attempt) {
                    return collect($attempt->notes ?? [])->map(function ($note) use ($attempt) {
                        $note->setRelation('attempt', $attempt);
                        return $note;
                    });
                })
                ->sortByDesc(function ($note) {
                    return optional($note->created_at)->timestamp ?? 0;
                })
                ->values();
        @endphp

        @forelse($timelineNotes as $note)
            <div class="d-flex mb-4">
                <div class="symbol symbol-35 symbol-light-info mr-4 flex-shrink-0">
                    <span class="symbol-label"><i class="fas fa-sticky-note text-info"></i></span>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex flex-wrap align-items-center mb-1">
                        <span class="font-weight-bold mr-2">{{ optional($note->attempt)->intake ?: 'Intake' }}</span>
                        <span class="label label-light-primary label-inline mr-2">{{ optional($note->attempt)->outcome_label }}</span>
                        <span class="text-muted font-size-sm">
                            {{ $note->created_at ? $note->created_at->format('d M Y, h:i A') : '' }}
                            @if(optional($note->createdBy)->name)
                                · {{ $note->createdBy->name }}
                            @endif
                        </span>
                    </div>
                    <div class="text-dark-75">{{ $note->body }}</div>
                </div>
            </div>
        @empty
            <p class="text-muted mb-0">No attempt notes yet.</p>
        @endforelse
    </div>
</div>

@if(!empty($canCarryOverIntake))
<div class="modal fade" id="carryOverIntakeModal" tabindex="-1" role="dialog" aria-labelledby="carryOverIntakeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('students.carry-over-intake', $student) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="carryOverIntakeModalLabel">Carry to Next Intake</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-muted font-size-sm">
                        Permanent ID <strong>{{ $student->st_office_id }}</strong> stays the same.
                        Current attempt will be closed and a new active attempt will start.
                    </p>
                    <div class="form-group">
                        <label for="new_intake">New intake <span class="text-danger">*</span></label>
                        <select name="new_intake" id="new_intake" class="form-control" required>
                            <option value="">Select intake</option>
                            @foreach(($intakeOptions ?? []) as $option)
                                @continue($option === ($student->intake ?? null))
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="carry_outcome">Close current as</label>
                        <select name="outcome" id="carry_outcome" class="form-control">
                            @foreach(($intakeOutcomeLabels ?? []) as $value => $label)
                                @if($value !== \App\Models\Student\StudentIntakeAttempt::OUTCOME_IN_PROGRESS)
                                    <option value="{{ $value }}" @if($value === 'deferred') selected @endif>{{ $label }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label for="carry_note">Note (optional)</label>
                        <textarea name="note" id="carry_note" rows="3" class="form-control" placeholder="Reason for carry-over"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">Confirm Carry Over</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if(($intakeAttempts ?? collect())->isNotEmpty())
<div class="modal fade" id="addIntakeNoteModal" tabindex="-1" role="dialog" aria-labelledby="addIntakeNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('students.intake-attempt-notes.store', $student) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addIntakeNoteModalLabel">Add Attempt Note</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="note_attempt_id">Attempt</label>
                        <select name="attempt_id" id="note_attempt_id" class="form-control" required>
                            @foreach(($intakeAttempts ?? []) as $attempt)
                                <option value="{{ $attempt->id }}" @if($attempt->is_active) selected @endif>
                                    {{ $attempt->intake ?: 'Intake' }}
                                    @if($attempt->is_active) (active) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label for="note_body">Note <span class="text-danger">*</span></label>
                        <textarea name="body" id="note_body" rows="4" class="form-control" required maxlength="2000" placeholder="Write a note for this intake attempt"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info font-weight-bold">Save Note</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@endif
