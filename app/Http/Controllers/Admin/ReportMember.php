<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportMember extends Controller
{
    public function reportDays(){
        $data = DB::table('member_gym')
            ->select(
                'member_information.name as name_member', 
                'packet_trainer.pertemuan as pertemuan', 
                'member_gym.start_training as start_training', 
                'member_gym.end_training as end_training', 
                'packet_trainer.price as price_member', 
                'member_gym.total_price as total_price'
            )
            ->where('idpaket', 1)
            ->whereDate('member_gym.end_training', '>=', now())
            ->leftJoin('member_information', 'member_information.id', '=', 'member_gym.idmember')
            ->leftJoin('packet_trainer', 'packet_trainer.id', '=', 'member_gym.idpacket_trainer')
            ->orderBy('member_gym.start_training', 'desc')
            ->paginate(10);
        
        return view('admin.report.reportday', [
            'data' => $data,
        ]);
    }

    public function reportmemberDays(){
        $data = DB::table('member_gym')
        ->select(
            'member_information.name as name_member', 
            'member_gym.created_at as start_training', 
            'member_gym.end_training as end_training',
            DB::raw('SUM(packet_trainer.price) as price_member'), // Menghitung total harga paket
            DB::raw('SUM(member_gym.total_price) as total_price'), // Menghitung total harga member
            DB::raw('COUNT(member_gym.id) as total_visits') // Menghitung total kunjungan
        )
        ->leftJoin('member_information', 'member_information.id', '=', 'member_gym.idmember')
        ->leftJoin('packet_trainer', 'packet_trainer.id', '=', 'member_gym.idpacket_trainer')
        ->groupBy(
            'member_gym.idmember', 
            'member_information.name', 
            'packet_trainer.pertemuan', 
            'member_gym.created_at', 
            'member_gym.end_training'
        ) // Group hanya berdasarkan kolom non-agregat
        ->orderBy('member_gym.created_at', 'desc')
        ->paginate(10);

        return view('admin.report.reportallmember', [
            'data' => $data,
        ]);
    }    

    public function reportmemberActive(){
        $data = DB::table('member_gym')
            ->select(
                'member_information.name as name_member', 
                'packet_trainer.pertemuan as pertemuan', 
                'member_gym.start_training as start_training', 
                'member_gym.end_training as end_training', 
                'packet_trainer.price as price_member', 
                'member_gym.total_price as total_price'
            )
            ->where('idpaket', 2)
            ->whereDate('member_gym.end_training', '>=', now()) // Kondisi tanggal
            ->leftJoin('member_information', 'member_information.id', '=', 'member_gym.idmember')
            ->leftJoin('packet_trainer', 'packet_trainer.id', '=', 'member_gym.idpacket_trainer')
            ->groupBy(
                'member_gym.idmember',
                'member_information.name',
                'packet_trainer.pertemuan',
                'member_gym.start_training',
                'member_gym.end_training',
                'packet_trainer.price',
                'member_gym.total_price'
            ) // Tambahkan semua kolom ke GROUP BY
            ->orderBy('member_gym.start_training', 'desc')
            ->paginate(10);
            
        return view('admin.report.reportmemberactive', [
            'data' => $data,
        ]);
    }        

    public function reportmembernonActive(){
        $data = DB::table('member_gym')
            ->select(
                'member_information.name as name_member', 
                'packet_trainer.pertemuan as pertemuan', 
                'member_gym.start_training as start_training', 
                'member_gym.end_training as end_training', 
                'packet_trainer.price as price_member', 
                'member_gym.total_price as total_price'
            )
            ->where('idpaket', 2)
            ->whereDate('member_gym.end_training', '<=', now())
            ->leftJoin('member_information', 'member_information.id', '=', 'member_gym.idmember')
            ->leftJoin('packet_trainer', 'packet_trainer.id', '=', 'member_gym.idpacket_trainer')
            ->groupBy(
                'member_gym.idmember',
                'member_information.name',
                'packet_trainer.pertemuan',
                'member_gym.start_training',
                'member_gym.end_training',
                'packet_trainer.price',
                'member_gym.total_price'
            ) // Tambahkan semua kolom ke GROUP BY
            ->orderBy('member_gym.start_training', 'desc')
            ->paginate(10);
            
        return view('admin.report.reportmembernonactive', [
            'data' => $data,
        ]);
    }
}
