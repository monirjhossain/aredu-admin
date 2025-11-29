<?php

namespace App\Http\Controllers;

use App\Imports\IncomeImport;
use App\Imports\InquireImport;
use App\Imports\StudentImportWithPayment;
use App\Imports\StudentImportWithPaymentCost;
use Illuminate\Http\Request;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class TestController extends Controller
{
    public function create()
    {
        try {
            Excel::import(new StudentImportWithPayment(), 'student2025.xlsx');
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
    public function testIncomeCost()
    {
        try {
            // Excel::import(new StudentImportWithPaymentCost(), 'DhakaStudent50.xlsx');
            Excel::import(new StudentImportWithPaymentCost(), 'DhakaStudentAll.xlsx');
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
    public function testIncome()
    {
        try {
            // Excel::import(new StudentImportWithPaymentCost(), 'DhakaStudent50.xlsx');
            Excel::import(new IncomeImport(), 'DhakaSeptemberOtherIncome.xlsx');
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
        
    public function importInquiry()
    {
        try {
            Excel::import(new InquireImport(), 'excel/SBStudentDataHSC2025.xlsx');
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
