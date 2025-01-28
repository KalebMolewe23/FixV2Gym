<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){

        $daily = DB::table('packets')->select('price')->where('id', 1)->first();
        $montly = DB::table('packets')->select('price')->where('id', 2)->first();
        $trainer = DB::table('packet_trainer')->select('price')->where('id', 1)->first();
        $review = DB::table('member_review')->join('member_information', 'member_information.id', '=', 'member_review.iduser')->where('member_review.status', 'Verified')->get();

        return view('home', [
            'daily' => $daily,
            'montly' => $montly,
            'trainer' => $trainer,
            'review' => $review,
        ]);
    }
}
