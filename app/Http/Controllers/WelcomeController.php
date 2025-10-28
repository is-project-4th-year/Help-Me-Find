<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class WelcomeController extends Controller
{
    // The method that retrieves and displays members
    public function welcome() {

        return view('welcome');
    }

}
