<?php

namespace App\Http\Controllers;

use App\Imports\StudentImportWithPayment;
use Illuminate\Http\Request;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class TestController extends Controller
{
    public function create()
    {
        try {
            Excel::import(new StudentImportWithPayment(), 'sample.xlsx');
            // return 'dd';
            return $this->respond([
                'status' => 1,
                'message' => "Import Success"
            ]);
        }catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
