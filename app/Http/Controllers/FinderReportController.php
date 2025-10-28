<?php

namespace App\Http\Controllers;
use App\Models\User;


class FinderReportController extends Controller
{
    public function showReportForm($token){

        $owner = User::where('qr_code_token', $token)->firstOrFail();

        return view('report', compact('owner'));
    }
}
