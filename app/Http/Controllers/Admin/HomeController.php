<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Cms;
use App\Models\Packets;

class HomeController extends Controller
{
    public function index(){

        return view('home', [
            'daily' => $daily,
            'montly' => $montly,
            'trainer' => $trainer,
            'review' => $review,
        ]);
    }
}
