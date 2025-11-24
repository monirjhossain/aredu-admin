<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Session;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StudentRequest;
use App\Models\Document;
use App\Models\Inquiry\Inquiry;
use App\Models\Invoice;
use App\Models\Post\Post;
use App\Models\Product;
use App\Models\SmsTemplate;
use App\Models\Student\Student;
use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BulkSmsController extends Controller
{

    use UploadAble;

    public function sendSingleSms(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        $student = Student::findOrFail($request->student_id);
        $template = SmsTemplate::findOrFail($request->template);
        if($student->mobile_no == null || $student->mobile_no == ''){
            Session::flash('error', 'Mobile no not found!');
            return redirect()->back();
        }
        if ($student) {
            $url = Env("API_URL");
            $data = [
                "api_key" => Env("API_KEY"),
                "senderid" => Env("SENDER_ID"),
                "number" => $student->mobile_no,
                "message" => strip_tags($template->message)
            ];
            Log::channel('sms')->info('===== start single sms'. ' - student_id:'. $request->student_id .' =====');
            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                curl_close($ch);

                Session::flash('success', 'SMS successfully Send');
                Log::channel('sms')->info('===== End single sms =====');
                return redirect()->route('students.index');
            } catch (\Exception $e) {
                    // return $e->getMessage();
                Log::channel('sms')->info($e->getMessage());
                Session::flash('error', 'Something went wrong!');
                return redirect()->back();
            }
        }

        Session::flash('error', 'Student not found');
        return redirect()->back();
    }
    public function sendBulkSms(Request $request)
    {
        // return $request;
        Log::channel('sms')->info('===== start bulk sms =====');

        $students = Student::orderby('id', 'DESC')->select('id', 'mobile_no')->get();
        $template = SmsTemplate::findOrFail($request->template);
        if (count($students) > 0) {
            $mbNumber = '';
            $count = 0;
            $student_ids = '';

            foreach ($students as $value) {
                if($value->mobile_no == null || $value->mobile_no == ''){
                    continue;
                }
                if(str_contains($mbNumber, $value->mobile_no)){
                    continue;
                }
                $mbNumber .= $value->mobile_no . ',';
                $count += 1;
                $student_ids .= $value->id . ',';
            }
            $url = Env("API_URL");
            $data = [
                "api_key" => Env("API_KEY"),
                "senderid" => Env("SENDER_ID"),
                "number" => $mbNumber,
                "message" => strip_tags($template->message)
            ];

            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                curl_close($ch);
                Log::channel('sms')->info('total: '. $count .' - student_ids:'. $student_ids);
                Session::flash('success', 'SMS successfully Send');
                Log::channel('sms')->info('===== End bulk sms =====');
                return redirect()->route('students.index');
            } catch (\Exception $e) {
                // return $e->getMessage();
                Log::channel('sms')->info($e->getMessage());
                Session::flash('error', 'Something went wrong!');
                return redirect()->back();
            }
        }

        Session::flash('error', 'Student not found');
        return redirect()->back();
    }
    public function sendBulkSmsMany(Request $request)
    {
        DB::beginTransaction();
        $students = Student::orderby('id', 'DESC')->select('id', 'mobile_no', 'first_name', 'last_name')->limit(3)->get();


        if (count($students) > 0) {
            $numberMsgs = [];
            foreach ($students as $value) {
                $numberMsgs[]= [
                    "to" => $value->mobile_no,
                    "message" => "Dear, ". $value->full_name .", amake amr moto thakte dao"
                ];
            }
            return $numberMsgs;
            $messages = json_encode( [
                [
                    "to" => "88016xxxxxxxx",
                    "message" => "test content"
                ],
                [
                    "to" => "88019xxxxxxxx",
                    "message" => "test 2nd content"
                ]
            ]);




            $url = Env("API_URL_MANY");
            $message = "Arnab, you are arested, quick call me 01856230550";
            $data = [
                "api_key" => Env("API_KEY"),
                "senderid" => Env("SENDER_ID"),
                // "number" => $mbNumber,
                "message" => $message
            ];
            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                curl_close($ch);

                Session::flash('success', 'SMS successfully Send');
                return redirect()->route('students.index');
            } catch (\Throwable $th) {
                DB::rollback();
                Session::flash('error', 'Something went wrong!');
                // return $th;
                return redirect()->back();
            }
        }

        Session::flash('error', 'Student not found');
        return redirect()->back();
    }
}
