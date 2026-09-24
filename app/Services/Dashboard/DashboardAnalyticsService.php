<?php

namespace App\Services\Dashboard;

use App\Models\Account\FinanceEntry;
use App\Models\Account\Invoice;
use App\Models\Branch;
use App\Models\Student\Student;
use App\Models\Student\StudentUniversity;
use App\Models\StudentStatus;
use App\Models\UniversityStatus;
use App\Models\User;
use App\Support\DashboardAccess;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardAnalyticsService
{
    /** @var array<string, array<int, int>>|null */
    private ?array $statusGroupIds = null;

    /**
     * @return array<string, mixed>
     */
    public function forUser(User $user): array
    {
        $access = DashboardAccess::for($user);
        $branchId = (int) $user->branch;
        $counselorOnly = $access['counselor_only'];

        $cards = [];
        if ($access['sections']['kpi_students'] ?? false) {
            $cards = array_merge($cards, $this->studentKpiCards($user, $branchId, $counselorOnly));
        }
        if ($access['sections']['kpi_applications'] ?? false) {
            $cards = array_merge($cards, $this->applicationKpiCards($user, $branchId, $counselorOnly));
        }
        if ($access['sections']['kpi_revenue'] ?? false) {
            $cards = array_merge($cards, $this->revenueKpiCards($branchId));
        }

        $charts = [];
        if ($access['sections']['charts_applications'] ?? false) {
            $charts['applications_by_status'] = $this->applicationsByStatus($user, $branchId, $counselorOnly);
        }
        if ($access['sections']['charts_students'] ?? false) {
            $charts['monthly_students'] = $this->monthlyNewStudents($user, $branchId, $counselorOnly);
            $charts['office_student_status'] = $this->officeStudentStatus($user, $branchId, $counselorOnly);
            $charts['students_by_intake'] = $this->studentsByIntake($user, $branchId, $counselorOnly);
            $charts['intake_attempt_outcomes'] = $this->intakeAttemptOutcomes($user, $branchId, $counselorOnly);
        }
        if ($access['sections']['charts_branch'] ?? false) {
            $charts['students_by_branch'] = $this->studentsByBranch();
        }
        if ($access['sections']['charts_counselor'] ?? false) {
            $charts['counselor_wise_students'] = $this->counselorWiseStudents($user, $branchId, $counselorOnly);
        }
        if ($access['sections']['charts_finance'] ?? false) {
            $charts['monthly_revenue'] = $this->monthlyRevenue($branchId);
            $charts['paid_vs_due'] = $this->paidVsDueMonthly($branchId);
            $charts['income_vs_expense'] = $this->incomeVsExpenseMonthly($branchId);
            $charts['monthly_invoice_count'] = $this->monthlyInvoiceCount($branchId);
        }

        return [
            'access' => $access,
            'cards' => $cards,
            'charts' => $charts,
            'counselor_only' => $counselorOnly,
        ];
    }

    public function seesBranchOverview(User $user): bool
    {
        return DashboardAccess::seesBranchOverview($user);
    }

    /**
     * @return array<string, int|float>
     */
    private function studentKpiCards(User $user, int $branchId, bool $counselorOnly): array
    {
        $studentQuery = $this->studentsQuery($user, $branchId, $counselorOnly);
        $now = Carbon::now();

        return [
            'total_students' => (clone $studentQuery)->count(),
            'new_students_month' => (clone $studentQuery)
                ->whereYear('created_at', $now->year)
                ->whereMonth('created_at', $now->month)
                ->count(),
            'carry_over_students' => (clone $studentQuery)
                ->whereHas('intakeAttempts', null, '>=', 2)
                ->count(),
            'deferred_attempts' => $this->countAttemptsByOutcome($user, $branchId, $counselorOnly, 'deferred'),
            'file_back_attempts' => $this->countAttemptsByOutcome($user, $branchId, $counselorOnly, 'file_back'),
        ];
    }

    /**
     * @return array<string, int>
     */
    private function applicationKpiCards(User $user, int $branchId, bool $counselorOnly): array
    {
        $applicationQuery = $this->applicationsQuery($user, $branchId, $counselorOnly);
        $groups = $this->statusGroupIds();

        return [
            'total_applications' => (clone $applicationQuery)->count(),
            'pending_applications' => $this->countApplicationsByStatusIds($applicationQuery, $groups['pending']),
            'approved_applications' => $this->countApplicationsByStatusIds($applicationQuery, $groups['approved']),
            'rejected_applications' => $this->countApplicationsByStatusIds($applicationQuery, $groups['rejected']),
        ];
    }

    /**
     * @return array<string, float>
     */
    private function revenueKpiCards(int $branchId): array
    {
        $now = Carbon::now();

        return [
            'total_revenue' => (float) Invoice::query()->where('branch_id', $branchId)->sum('paid'),
            'revenue_this_month' => (float) Invoice::query()
                ->where('branch_id', $branchId)
                ->whereYear('invoice_date', $now->year)
                ->whereMonth('invoice_date', $now->month)
                ->sum('paid'),
        ];
    }

    /**
     * @return array<int, array{name: string, y: int}>
     */
    private function applicationsByStatus(User $user, int $branchId, bool $counselorOnly): array
    {
        $query = $this->applicationsQuery($user, $branchId, $counselorOnly);

        $rows = $query
            ->select('student_university.status_id', DB::raw('COUNT(*) as total'))
            ->groupBy('student_university.status_id')
            ->get();

        $statusNames = UniversityStatus::query()
            ->whereIn('id', $rows->pluck('status_id')->filter())
            ->pluck('name', 'id');

        $series = [];
        foreach ($rows as $row) {
            $name = $row->status_id
                ? ($statusNames[$row->status_id] ?? 'Unknown')
                : 'No Status';
            $series[] = [
                'name' => $name,
                'y' => (int) $row->total,
            ];
        }

        usort($series, static fn ($a, $b) => $b['y'] <=> $a['y']);

        return $series;
    }

    /**
     * @return array{categories: string[], data: int[]}
     */
    private function monthlyNewStudents(User $user, int $branchId, bool $counselorOnly): array
    {
        $months = (int) config('dashboard.chart_months', 12);
        $start = Carbon::now()->startOfMonth()->subMonths($months - 1);

        $rows = $this->studentsQuery($user, $branchId, $counselorOnly)
            ->where('created_at', '>=', $start)
            ->select(
                DB::raw('YEAR(created_at) as y'),
                DB::raw('MONTH(created_at) as m'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('y', 'm')
            ->orderBy('y')
            ->orderBy('m')
            ->get()
            ->keyBy(static fn ($row) => $row->y . '-' . str_pad((string) $row->m, 2, '0', STR_PAD_LEFT));

        return $this->buildMonthlySeries($start, $months, $rows);
    }

    /**
     * @return array{categories: string[], data: float[]}
     */
    private function monthlyRevenue(int $branchId): array
    {
        $months = (int) config('dashboard.chart_months', 12);
        $start = Carbon::now()->startOfMonth()->subMonths($months - 1);

        $rows = Invoice::query()
            ->where('branch_id', $branchId)
            ->where('invoice_date', '>=', $start->toDateString())
            ->select(
                DB::raw('YEAR(invoice_date) as y'),
                DB::raw('MONTH(invoice_date) as m'),
                DB::raw('SUM(paid) as total')
            )
            ->groupBy('y', 'm')
            ->orderBy('y')
            ->orderBy('m')
            ->get()
            ->keyBy(static fn ($row) => $row->y . '-' . str_pad((string) $row->m, 2, '0', STR_PAD_LEFT));

        $series = $this->buildMonthlySeries($start, $months, $rows, true);

        return [
            'categories' => $series['categories'],
            'data' => array_map('floatval', $series['data']),
        ];
    }

    /**
     * @return array<int, array{name: string, y: int}>
     */
    private function officeStudentStatus(User $user, int $branchId, bool $counselorOnly): array
    {
        $rows = $this->studentsQuery($user, $branchId, $counselorOnly)
            ->select('students.status', DB::raw('COUNT(*) as total'))
            ->groupBy('students.status')
            ->orderByDesc('total')
            ->get();

        $statusNames = StudentStatus::query()
            ->whereIn('id', $rows->pluck('status')->filter())
            ->pluck('name', 'id');

        $series = [];
        foreach ($rows as $row) {
            $series[] = [
                'name' => $row->status
                    ? ($statusNames[$row->status] ?? 'Unknown')
                    : 'No Status',
                'y' => (int) $row->total,
            ];
        }

        return $series;
    }

    /**
     * All branches — Super Admin / Admin / Manager only.
     *
     * @return array{categories: string[], data: int[]}
     */
    private function studentsByBranch(): array
    {
        $rows = Student::query()
            ->select('students.branch_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('students.branch_id')
            ->groupBy('students.branch_id')
            ->orderByDesc('total')
            ->get();

        $branchNames = Branch::query()
            ->whereIn('id', $rows->pluck('branch_id'))
            ->pluck('name', 'id');

        $categories = [];
        $data = [];
        foreach ($rows as $row) {
            $categories[] = $branchNames[$row->branch_id] ?? 'Branch #' . $row->branch_id;
            $data[] = (int) $row->total;
        }

        return ['categories' => $categories, 'data' => $data];
    }

    /**
     * @return array{categories: string[], data: int[]}
     */
    private function studentsByIntake(User $user, int $branchId, bool $counselorOnly): array
    {
        $limit = (int) config('dashboard.intake_chart_limit', 12);

        $rows = $this->studentsQuery($user, $branchId, $counselorOnly)
            ->select('students.intake', DB::raw('COUNT(*) as total'))
            ->whereNotNull('students.intake')
            ->where('students.intake', '!=', '')
            ->groupBy('students.intake')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();

        return [
            'categories' => $rows->pluck('intake')->map(static fn ($v) => (string) $v)->all(),
            'data' => $rows->pluck('total')->map(static fn ($v) => (int) $v)->all(),
        ];
    }

    /**
     * @return array{categories: string[], data: int[]}
     */
    private function intakeAttemptOutcomes(User $user, int $branchId, bool $counselorOnly): array
    {
        $labels = \App\Models\Student\StudentIntakeAttempt::outcomeLabels();
        $studentIds = $this->studentsQuery($user, $branchId, $counselorOnly)->pluck('id');

        $counts = \App\Models\Student\StudentIntakeAttempt::query()
            ->select('outcome', DB::raw('COUNT(*) as total'))
            ->whereIn('student_id', $studentIds)
            ->whereNotNull('outcome')
            ->groupBy('outcome')
            ->pluck('total', 'outcome');

        $categories = [];
        $data = [];
        foreach ($labels as $key => $label) {
            $categories[] = $label;
            $data[] = (int) ($counts[$key] ?? 0);
        }

        return ['categories' => $categories, 'data' => $data];
    }

    private function countAttemptsByOutcome(User $user, int $branchId, bool $counselorOnly, string $outcome): int
    {
        $studentIds = $this->studentsQuery($user, $branchId, $counselorOnly)->pluck('id');

        if ($studentIds->isEmpty()) {
            return 0;
        }

        return (int) \App\Models\Student\StudentIntakeAttempt::query()
            ->whereIn('student_id', $studentIds)
            ->where('outcome', $outcome)
            ->count();
    }

    /**
     * @return array{categories: string[], data: int[]}
     */
    private function counselorWiseStudents(User $user, int $branchId, bool $counselorOnly): array
    {
        $limit = (int) config('dashboard.counselor_chart_limit', 10);

        $rows = $this->studentsQuery($user, $branchId, $counselorOnly)
            ->select('students.junior_counselor', DB::raw('COUNT(*) as total'))
            ->whereNotNull('students.junior_counselor')
            ->groupBy('students.junior_counselor')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();

        $counselorNames = User::query()
            ->whereIn('id', $rows->pluck('junior_counselor'))
            ->pluck('name', 'id');

        $categories = [];
        $data = [];
        foreach ($rows as $row) {
            $categories[] = $counselorNames[$row->junior_counselor] ?? 'User #' . $row->junior_counselor;
            $data[] = (int) $row->total;
        }

        return ['categories' => $categories, 'data' => $data];
    }

    /**
     * @return array{categories: string[], paid: float[], due: float[]}
     */
    private function paidVsDueMonthly(int $branchId): array
    {
        $months = (int) config('dashboard.chart_months', 12);
        $start = Carbon::now()->startOfMonth()->subMonths($months - 1);

        $rows = Invoice::query()
            ->where('branch_id', $branchId)
            ->where('invoice_date', '>=', $start->toDateString())
            ->select(
                DB::raw('YEAR(invoice_date) as y'),
                DB::raw('MONTH(invoice_date) as m'),
                DB::raw('SUM(paid) as paid_total'),
                DB::raw('SUM(due) as due_total')
            )
            ->groupBy('y', 'm')
            ->orderBy('y')
            ->orderBy('m')
            ->get()
            ->keyBy(static fn ($row) => $row->y . '-' . str_pad((string) $row->m, 2, '0', STR_PAD_LEFT));

        $categories = [];
        $paid = [];
        $due = [];
        $cursor = $start->copy();

        for ($i = 0; $i < $months; $i++) {
            $key = $cursor->format('Y-m');
            $categories[] = $cursor->format('M Y');
            $row = $rows->get($key);
            $paid[] = $row ? (float) $row->paid_total : 0.0;
            $due[] = $row ? (float) $row->due_total : 0.0;
            $cursor->addMonth();
        }

        return ['categories' => $categories, 'paid' => $paid, 'due' => $due];
    }

    /**
     * @return array{categories: string[], income: float[], expense: float[]}
     */
    private function incomeVsExpenseMonthly(int $branchId): array
    {
        $months = (int) config('dashboard.chart_months', 12);
        $start = Carbon::now()->startOfMonth()->subMonths($months - 1);

        $rows = FinanceEntry::query()
            ->where('branch_id', $branchId)
            ->where('transaction_date', '>=', $start->toDateString())
            ->select(
                DB::raw('YEAR(transaction_date) as y'),
                DB::raw('MONTH(transaction_date) as m'),
                'type',
                DB::raw('SUM(amount) as total')
            )
            ->whereIn('type', ['income', 'expense'])
            ->groupBy('y', 'm', 'type')
            ->orderBy('y')
            ->orderBy('m')
            ->get();

        $indexed = [];
        foreach ($rows as $row) {
            $key = $row->y . '-' . str_pad((string) $row->m, 2, '0', STR_PAD_LEFT);
            $indexed[$key][$row->type] = (float) $row->total;
        }

        $categories = [];
        $income = [];
        $expense = [];
        $cursor = $start->copy();

        for ($i = 0; $i < $months; $i++) {
            $key = $cursor->format('Y-m');
            $categories[] = $cursor->format('M Y');
            $income[] = $indexed[$key]['income'] ?? 0.0;
            $expense[] = $indexed[$key]['expense'] ?? 0.0;
            $cursor->addMonth();
        }

        return ['categories' => $categories, 'income' => $income, 'expense' => $expense];
    }

    /**
     * @return array{categories: string[], data: int[]}
     */
    private function monthlyInvoiceCount(int $branchId): array
    {
        $months = (int) config('dashboard.chart_months', 12);
        $start = Carbon::now()->startOfMonth()->subMonths($months - 1);

        $rows = Invoice::query()
            ->where('branch_id', $branchId)
            ->where('invoice_date', '>=', $start->toDateString())
            ->select(
                DB::raw('YEAR(invoice_date) as y'),
                DB::raw('MONTH(invoice_date) as m'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('y', 'm')
            ->orderBy('y')
            ->orderBy('m')
            ->get()
            ->keyBy(static fn ($row) => $row->y . '-' . str_pad((string) $row->m, 2, '0', STR_PAD_LEFT));

        return $this->buildMonthlySeries($start, $months, $rows);
    }

    /**
     * @param Collection<string, mixed> $rows
     * @return array{categories: string[], data: array<int, int|float>}
     */
    private function buildMonthlySeries(Carbon $start, int $months, Collection $rows, bool $asFloat = false): array
    {
        $categories = [];
        $data = [];
        $cursor = $start->copy();

        for ($i = 0; $i < $months; $i++) {
            $key = $cursor->format('Y-m');
            $categories[] = $cursor->format('M Y');
            $value = $rows->has($key) ? $rows[$key]->total : 0;
            $data[] = $asFloat ? (float) $value : (int) $value;
            $cursor->addMonth();
        }

        return ['categories' => $categories, 'data' => $data];
    }

    private function studentsQuery(User $user, int $branchId, bool $counselorOnly): Builder
    {
        $query = Student::query()->where('branch_id', $branchId);

        if ($counselorOnly) {
            $userId = (int) $user->id;
            $query->where(function (Builder $q) use ($userId) {
                $q->where('junior_counselor', $userId)
                    ->orWhere('senior_counselor', $userId);
            });
        }

        return $query;
    }

    private function applicationsQuery(User $user, int $branchId, bool $counselorOnly): Builder
    {
        $query = StudentUniversity::query()
            ->join('students', 'students.id', '=', 'student_university.student_id')
            ->where('students.branch_id', $branchId);

        if ($counselorOnly) {
            $userId = (int) $user->id;
            $query->where(function (Builder $q) use ($userId) {
                $q->where('students.junior_counselor', $userId)
                    ->orWhere('students.senior_counselor', $userId);
            });
        }

        return $query;
    }

    /**
     * @param Builder $applicationQuery
     * @param array<int, int> $statusIds
     */
    private function countApplicationsByStatusIds(Builder $applicationQuery, array $statusIds): int
    {
        if ($statusIds === []) {
            return 0;
        }

        return (clone $applicationQuery)
            ->whereIn('student_university.status_id', $statusIds)
            ->count();
    }

    /**
     * @return array{pending: array<int, int>, approved: array<int, int>, rejected: array<int, int>}
     */
    private function statusGroupIds(): array
    {
        if ($this->statusGroupIds !== null) {
            return $this->statusGroupIds;
        }

        $config = config('dashboard.application_status_groups', []);
        $allNamesLower = collect($config)->flatten()
            ->map(static fn ($n) => strtolower((string) $n))
            ->unique()
            ->flip();

        $byLowerName = UniversityStatus::query()
            ->get()
            ->filter(static fn ($s) => $allNamesLower->has(strtolower((string) $s->name)))
            ->keyBy(static fn ($s) => strtolower((string) $s->name));

        $resolve = static function (array $names) use ($byLowerName): array {
            $ids = [];
            foreach ($names as $name) {
                $status = $byLowerName->get(strtolower($name));
                if ($status) {
                    $ids[] = (int) $status->id;
                }
            }

            return $ids;
        };

        return $this->statusGroupIds = [
            'pending' => $resolve($config['pending'] ?? []),
            'approved' => $resolve($config['approved'] ?? []),
            'rejected' => $resolve($config['rejected'] ?? []),
        ];
    }
}
