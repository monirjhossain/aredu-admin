<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\Inquiry\WebsiteInquiryRequest;
use App\Models\Inquiry\Inquiry;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InquiryController extends BaseController
{
    /**
     * Website apply / contact form submit.
     */
    public function store(WebsiteInquiryRequest $request)
    {
        DB::beginTransaction();

        try {
            $payload = [
                'type' => $request->input('type', 'From Web - Inquiry'),
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'mobile_no' => $request->input('mobile_no'),
                'country' => $request->input('country'),
                'country_preferred' => $request->input('country_preferred'),
                'course' => $request->input('course'),
                'last_education' => $request->input('last_education'),
                'message' => $request->input('message'),
                'oppurtunity' => $request->input('oppurtunity'),
                'status' => 1,
                'view' => 0,
            ];

            if ($request->filled('branch_id') && Schema::hasColumn('inquiries', 'branch_id')) {
                $payload['branch_id'] = (int) $request->input('branch_id');
            }

            $payload = array_filter($payload, function ($value) {
                return $value !== null && $value !== '';
            });

            // Keep status/view even if 0/1 filtered incorrectly
            $payload['status'] = 1;
            $payload['view'] = 0;

            $inquiry = Inquiry::create($payload);

            DB::commit();

            return $this->sendResponse(
                [
                    'inquiry' => [
                        'id' => $inquiry->id,
                        'tracking_id' => $inquiry->tracking_id ?? null,
                        'full_name' => $inquiry->full_name,
                    ],
                ],
                'Successfully Submitted'
            );
        } catch (\Throwable $th) {
            DB::rollBack();
            report($th);

            return $this->sendError(
                'Sorry, Operation Failed',
                ['error' => config('app.debug') ? $th->getMessage() : 'Operation failed'],
                500
            );
        }
    }
}
