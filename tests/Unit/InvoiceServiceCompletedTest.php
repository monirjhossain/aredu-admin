<?php

namespace Tests\Unit;

use App\Models\Account\Invoice;
use App\Models\Student\Student;
use App\Models\Student\StudentUniversity;
use App\Services\ServiceCompletedDocumentService;
use ReflectionMethod;
use Tests\TestCase;

class InvoiceServiceCompletedTest extends TestCase
{
    public function test_fully_paid_invoice_can_generate_service_completed_button(): void
    {
        $invoice = new Invoice();
        $invoice->due = '0.00';

        $this->assertTrue($invoice->canGenerateServiceCompleted());
    }

    public function test_invoice_with_outstanding_due_cannot_generate_service_completed_button(): void
    {
        $invoice = new Invoice();
        $invoice->due = '125.50';

        $this->assertFalse($invoice->canGenerateServiceCompleted());
    }

    public function test_falls_back_to_latest_application_when_configured_status_names_do_not_match(): void
    {
        $student = new Student();
        $application = new StudentUniversity();
        $application->id = 99;
        $application->status_id = null;
        $application->updated_at = now()->subDay();

        $student->setRelation('universitiesAppStatus', collect([$application]));

        $method = new ReflectionMethod(ServiceCompletedDocumentService::class, 'findAdmittedApplication');
        $method->setAccessible(true);

        $result = $method->invoke(new ServiceCompletedDocumentService(), $student);

        $this->assertSame($application->id, $result?->id);
    }
}
