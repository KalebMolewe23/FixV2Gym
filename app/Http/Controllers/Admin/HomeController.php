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

        $package = Packets::where('is_active', 1)->orderBy('id', 'DESC')->get();
        dd($package);

        // return view('home', [
        //     'daily' => $daily
        // ]);
    }
}
