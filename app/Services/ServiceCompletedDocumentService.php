<?php

namespace App\Services;

use App\Models\Account\Invoice;
use App\Models\Student\Student;
use App\Models\Student\StudentUniversity;
use App\Models\StudentStatus;
use App\Models\UniversityStatus;
use App\Models\User;
use App\Support\StudentDisplay;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use RuntimeException;

class ServiceCompletedDocumentService
{
    /**
     * @return array<string, mixed>
     */
    public function build(Invoice $invoice, User $currentUser): array
    {
        $this->assertBranchAccess($invoice, $currentUser);

        if (!$invoice->student_id) {
            throw new RuntimeException('This invoice is not linked to a student.');
        }

        $student = Student::with([
            'branchInfo',
            'student_status',
            'sncounselor',
            'jncounselor',
            'universitiesAppStatus.university',
            'universitiesAppStatus.subject',
            'universitiesAppStatus.statusUni',
        ])->findOrFail($invoice->student_id);

        $lastInvoice = Invoice::lastForStudent((int) $student->id);

        if (!$lastInvoice || (int) $lastInvoice->id !== (int) $invoice->id) {
            throw new RuntimeException('Service Completed certificate is only available on the student\'s last invoice.');
        }

        if ((float) $invoice->due !== 0.0) {
            throw new RuntimeException('The last invoice must be fully paid (due = 0) before generating this certificate.');
        }

        $admittedApplication = $this->findAdmittedApplication($student);

        if (!$admittedApplication) {
            throw new RuntimeException('No admitted university application found for this student.');
        }

        $displayIntake = $this->resolveIntake($student, $admittedApplication);

        $invoices = Invoice::query()
            ->where('student_id', $student->id)
            ->orderByRaw('COALESCE(invoice_date, DATE(created_at)) ASC')
            ->orderBy('id')
            ->get();

        $branch = $student->branchInfo;
        $certificateNo = $this->buildCertificateNumber($student, $branch);

        return [
            'student' => $student,
            'branch' => $branch,
            'invoice' => $invoice,
            'admitted_application' => $admittedApplication,
            'display_intake' => $displayIntake,
            'invoices' => $invoices,
            'invoice_summary' => $this->summarizeInvoices($invoices),
            'certificate_no' => $certificateNo,
            'generated_at' => Carbon::now(),
            'office_student_id' => StudentDisplay::officeStudentId($student),
            'student_address' => StudentDisplay::addressLine($student),
            'declaration' => (string) config('service_completed.declaration', ''),
        ];
    }

    public function resolveOfficeStatusId(): int
    {
        $name = (string) config('service_completed.office_status_name', 'Service Complete');
        $status = StudentStatus::query()->where('name', $name)->first();

        if (!$status) {
            throw new RuntimeException('Office status "' . $name . '" is not configured in student_statuses.');
        }

        return (int) $status->id;
    }

    public function canShowButton(Invoice $invoice): bool
    {
        if (!$invoice->student_id || (float) $invoice->due !== 0.0) {
            return false;
        }

        $lastInvoice = Invoice::lastForStudent((int) $invoice->student_id);

        return $lastInvoice && (int) $lastInvoice->id === (int) $invoice->id;
    }

    private function assertBranchAccess(Invoice $invoice, User $currentUser): void
    {
        if ((int) $invoice->branch_id !== (int) $currentUser->branch) {
            throw new RuntimeException('You do not have permission to access this invoice.');
        }
    }

    private function findAdmittedApplication(Student $student): ?StudentUniversity
    {
        $admittedNames = (array) config('service_completed.admitted_status_names', []);
        $statusIds = UniversityStatus::query()
            ->whereIn('name', $admittedNames)
            ->pluck('id');

        if ($statusIds->isEmpty()) {
            return null;
        }

        $applications = $student->universitiesAppStatus
            ->whereIn('status_id', $statusIds->all());

        if ($applications->isEmpty()) {
            return null;
        }

        $rank = array_flip($admittedNames);

        return $applications
            ->sort(function (StudentUniversity $a, StudentUniversity $b) use ($rank) {
                $rankA = $rank[optional($a->statusUni)->name ?? ''] ?? -1;
                $rankB = $rank[optional($b->statusUni)->name ?? ''] ?? -1;

                if ($rankA !== $rankB) {
                    return $rankB <=> $rankA;
                }

                return $b->updated_at <=> $a->updated_at;
            })
            ->first();
    }

    private function resolveIntake(Student $student, StudentUniversity $application): string
    {
        $applicationIntake = trim((string) ($application->intake ?? ''));
        if ($applicationIntake !== '') {
            return $applicationIntake;
        }

        $studentIntake = trim((string) ($student->intake ?? ''));

        return $studentIntake !== '' ? $studentIntake : '—';
    }

    private function buildCertificateNumber(Student $student, $branch): string
    {
        $prefix = (string) config('service_completed.certificate_prefix', 'SC');
        $branchCode = strtoupper((string) optional($branch)->short_name ?: 'BR');
        $year = date('Y');

        return sprintf('%s-%s-%s-%04d', $prefix, $branchCode, $year, (int) $student->id);
    }

    /**
     * @param Collection<int, Invoice> $invoices
     * @return array{subtotal: float, discount: float, grandtotal: float, paid: float, due: float}
     */
    private function summarizeInvoices(Collection $invoices): array
    {
        return [
            'subtotal' => (float) $invoices->sum('subtotal'),
            'discount' => (float) $invoices->sum('discount'),
            'grandtotal' => (float) $invoices->sum('grandtotal'),
            'paid' => (float) $invoices->sum('paid'),
            'due' => (float) $invoices->sum('due'),
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function invoiceServiceLines(Invoice $invoice): array
    {
        $products = $invoice->products;

        if (!is_array($products) || empty($products['product'])) {
            return [];
        }

        return array_values(array_filter((array) $products['product'], static function ($name) {
            return trim((string) $name) !== '';
        }));
    }
}
