<?php


use App\Models\CaseActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

if (!function_exists('user_email')) {
	function user_email() {
		$user = Auth::user();
		return $user->email;
	}
}
