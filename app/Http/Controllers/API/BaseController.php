<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;


class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message, $cacheSeconds = 0)
    {
    	$response = [
            'success' => true,
            'message' => $message,
            'err_res' => '',
            'status_code' => 200,
            'data'    => $result,
        ];
        $json = response()->json($response, 200);
        if ($cacheSeconds > 0) {
            $json->header('Cache-Control', 'public, max-age=' . (int) $cacheSeconds . ', stale-while-revalidate=' . ((int) $cacheSeconds * 2));
        }
        return $json;
    }

    public function NewSendResponse($result, $message)
    {
    	$response = [
            'success' => true,
            'message' => $message,
            'err_res' => '',
            'status_code' => 200,
            'data'    => $result,
            'total' => $result->total(),
            'prev'  => $result->previousPageUrl(),
            'next'  => $result->nextPageUrl(),
            'current_page'  => $result->CurrentPage(),
            'per_page'  => $result->PerPage(),
        ];
        return response()->json($response, 200);
    }

    public function groupUserResponse($result, $message, $case = [])
    {
    	$response = [
            'success' => true,
            'message' => $message,
            'err_res' => '',
            'status_code' => 200,
            'case_info' =>$case,
            'data'    => $result,
            'total' => $result->total(),
            'prev'  => $result->previousPageUrl(),
            'next'  => $result->nextPageUrl(),
            'current_page'  => $result->CurrentPage(),
            'per_page'  => $result->PerPage(),
        ];
        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'message' => $error,
            'err_res' => null,
            'status_code' => $code,
            'data' => null,
        ];

        if(!empty($errorMessages)){
            $response['err_res'] = $errorMessages['error'];
        }

        return response()->json($response, $code);
    }
}
