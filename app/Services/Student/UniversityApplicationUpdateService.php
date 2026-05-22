<?php

namespace App\Services\Student;

use App\Http\Requests\Student\UpdateUniversityApplicationRequest;
use App\Models\Student\StudentUniversity;
use App\Support\StudentUniversitySchema;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UniversityApplicationUpdateService
{
    /** @var UniversityApplicationStatusService */
    private $applicationStatusService;

    /** @var array<int, string> */
    private const FILE_FIELDS = ['admission_notice', 'jw_copy', 'visa_copy'];

    private const STORAGE_DISK = 'public';

    private const STORAGE_DIR = 'student_applications';

    public function __construct(UniversityApplicationStatusService $applicationStatusService)
    {
        $this->applicationStatusService = $applicationStatusService;
    }

    public function update(StudentUniversity $application, UpdateUniversityApplicationRequest $request): void
    {
        $oldStatus = (int) $application->status_id;
        $oldStatusName = optional($application->statusUni)->name;
        $oldNote = trim((string) ($application->note ?? ''));
        $oldRemarks = trim((string) ($application->remarks ?? ''));

        $application->status_id = (int) $request->input('status_id');
        $application->application_no = $request->input('application_no');
        $application->note = $request->input('note');
        $application->remarks = $request->input('remarks');
        $application->updated_by = Auth::id();

        $this->applyPortalFields($application, $request);
        $this->storeUploadedFiles($application, $request);

        $application->save();
        $application->load('statusUni');

        $newNote = trim((string) ($application->note ?? ''));
        $newRemarks = trim((string) ($application->remarks ?? ''));
        $statusChanged = $oldStatus !== (int) $application->status_id;
        $noteOrRemarksChanged = $oldNote !== $newNote || $oldRemarks !== $newRemarks;

        $filesUploaded = $request->hasFile('admission_notice')
            || $request->hasFile('jw_copy')
            || $request->hasFile('visa_copy');

        if (!$statusChanged && !$noteOrRemarksChanged && !$filesUploaded) {
            return;
        }

        if ($filesUploaded && !$statusChanged && !$noteOrRemarksChanged) {
            return;
        }

        $newStatusName = optional($application->statusUni)->name;

        if ($statusChanged) {
            $this->applicationStatusService->recordHistory($application, (int) $application->status_id);
            $this->applicationStatusService->notifyChange(
                $application,
                $oldStatusName,
                $newStatusName,
                'status_changed',
                true
            );

            return;
        }

        $this->applicationStatusService->notifyChange(
            $application,
            $oldStatusName,
            $newStatusName,
            'note_remarks_updated',
            false
        );
    }

    private function applyPortalFields(StudentUniversity $application, UpdateUniversityApplicationRequest $request): void
    {
        if (StudentUniversitySchema::hasPortalType()) {
            $application->portal_type = (int) $request->input('portal_type');
        }

        if (StudentUniversitySchema::hasLoginId()) {
            $application->login_id = $request->input('login_id');
        }

        if (StudentUniversitySchema::hasLoginPassword()) {
            $application->login_password = $request->input('login_password');
        }
    }

    private function storeUploadedFiles(StudentUniversity $application, UpdateUniversityApplicationRequest $request): void
    {
        foreach (self::FILE_FIELDS as $field) {
            if (!$request->hasFile($field)) {
                continue;
            }

            $file = $request->file($field);

            if (!$file instanceof UploadedFile) {
                continue;
            }

            if ($application->{$field}) {
                Storage::disk(self::STORAGE_DISK)->delete(
                    self::STORAGE_DIR . '/' . $application->{$field}
                );
            }

            $filename = uniqid($field . '_') . '.' . $file->getClientOriginalExtension();
            $file->storeAs(self::STORAGE_DIR, $filename, self::STORAGE_DISK);
            $application->{$field} = $filename;
        }
    }
}
