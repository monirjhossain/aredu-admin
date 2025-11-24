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

class CommonController extends Controller
{

    use UploadAble;
    
    public function getSmsTemplate(Request $request, $id)
    {
        $template = SmsTemplate::findOrFail($id);
        return response()->json([
            'status' => 1, 
            'template' =>$template, 
        ], 200);
    }
    public function getSmsTemplates(Request $request)
    {
        $templates = SmsTemplate::all();
        return response()->json([
            'status' => 1, 
            'templates' =>$templates, 
        ], 200);
    }
}
