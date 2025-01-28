<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Trainer;
use App\Models\Country;
use App\Models\User;
use App\Models\ScheduleTrainer;
use App\Models\City;
use App\Models\State;
use Carbon\Carbon;

class TrainerController extends Controller
{
    public function index()
    {
        $memberActive = DB::table('users')
            ->select(
                DB::raw('users.id as id'),
                'users.name as name',
                'users.number_phone as number_phone',
                'users.gender as gender',
                'users.email as email',
                'users.password as password',
                'users.address as address',
                'users.photo as photo',
                'users.upload as upload',
                'users.created_at as created_at',
            )
            ->where('role', 'trainer')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.trainers.index', [
            'active' => $memberActive,
        ]);
    }

    public function add_trainer(Request $request)
    {
        $countries = Country::all();

        return view('admin.trainers.create', compact('countries')); // Form tambah trainer
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'number_phone' => 'required|string|max:15',
            'gender' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string',
            'address' => 'required|string',
            'portal_code' => 'required|string',
        ]);

        $barcode = 'MBR-' . strtoupper(uniqid());
        $photoPath = null;
        $uploadPath = null;

        // Proses simpan foto jika ada
        if ($request->photo) {
            $imageData = $request->photo;
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $fileName = 'photo_' . uniqid() . '.png';
            $photoPath = 'uploads/photos/' . $fileName;
            
            \Storage::disk('public')->put($photoPath, base64_decode($imageData));
        }

        // Proses simpan berkas upload jika ada
        if ($request->upload) {
            $fileData = $request->upload;
            $fileData = str_replace('data:application/pdf;base64,', '', $fileData); // Sesuaikan jenis MIME jika perlu
            $fileData = str_replace(' ', '+', $fileData);
            $fileName = 'upload_' . uniqid() . '.pdf'; // Sesuaikan ekstensi file
            $uploadPath = 'assets/images/upload/' . $fileName;
        
            \Storage::disk('public')->put($uploadPath, base64_decode($fileData));
        }

        $currentDate = Carbon::now()->startOfDay();

        User::create([
            'name' => $request->name,
            'full_name' => $request->full_name,
            'idcountry' => $request->idcountry,
            'idstate' => $request->idstate,
            'idcities' => $request->idcities,
            'number_phone' => $request->number_phone,
            'barcode' => $barcode,
            'address' => $request->address,
            'address_2' => $request->address_2,
            'portal_code' => $request->portal_code,
            'gender' => $request->gender,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'trainer',
            'photo' => $photoPath,
            'upload' => $uploadPath,
        ]);

        return redirect()->route('admin.data_trainer')->with('success', 'Trainer berhasil ditambahkan!');
    }

    public function scheduleTrainer()
    {
        $trainers = DB::table('users')->where('role', 'trainer')->get();
 
        $ptrainer = DB::table('packet_trainer')->get();

        $schedule = DB::table('schedule_trainer')
        ->join('member_gym', 'schedule_trainer.iduser', '=', 'member_gym.id')
        ->join('users as umember', 'member_gym.idmember', '=', 'umember.id')
        ->join('users as utrainer', 'schedule_trainer.idtrainer', '=', 'utrainer.id')
        ->select(
            'schedule_trainer.iduser',
            'umember.name as customer_name',
            DB::raw('GROUP_CONCAT(DISTINCT utrainer.name) as trainer_name'),  // Gabungkan nama pelatih untuk setiap user
            DB::raw('DATE_FORMAT(MIN(member_gym.start_training), "%d %M %Y") as training_date'), // Pilih tanggal pertama
            DB::raw('DATE_FORMAT(MIN(member_gym.end_training), "%d %M %Y") as training_end'), // Pilih tanggal pertama
            DB::raw('CASE 
                        WHEN member_gym.idpacket_trainer = 1 THEN 5
                        WHEN member_gym.idpacket_trainer = 2 THEN 20
                        WHEN member_gym.idpacket_trainer = 3 THEN 30
                        ELSE 0 
                    END as total_sessions'),
            DB::raw('(SELECT COUNT(*) FROM schedule_trainer WHERE schedule_trainer.iduser = member_gym.id) as sessions_taken'),
            DB::raw('(CASE 
                        WHEN member_gym.idpacket_trainer = 1 THEN 5
                        WHEN member_gym.idpacket_trainer = 2 THEN 20
                        WHEN member_gym.idpacket_trainer = 3 THEN 30
                        ELSE 0 
                    END) - (SELECT COUNT(*) FROM schedule_trainer WHERE schedule_trainer.iduser = member_gym.id) as remaining_sessions')
        )
        ->groupBy(
            'schedule_trainer.iduser',
            'umember.name',
            'member_gym.id',
            'member_gym.idpacket_trainer'
        )
        ->get();

        // Mengambil data schedule_trainer dan trainers
        $member_gym_schedule = DB::table('schedule_trainer')
            ->select('schedule_trainer.iduser as iduser', 'trainers.name as name', 'schedule_trainer.date_trainer as date_trainer')
            ->join('trainers', 'trainers.id', '=', 'schedule_trainer.idtrainer')
            ->get();

        // Mengambil data member_gym dengan informasi nama member dan tanggal training
        $member = DB::table('member_gym')
            ->join('member_information', 'member_information.id', '=', 'member_gym.idmember')
            ->select( 
                'member_gym.id as idmember', 
                'member_information.name as name',
                DB::raw('DATE_FORMAT(member_gym.start_training, "%Y-%m-%d") as start_training'),
                DB::raw('DATE_FORMAT(member_gym.end_training, "%Y-%m-%d") as end_training')
            )
            ->where('idpaket', 2)
            ->where('idpacket_trainer', 1)
            ->get(); 
            
        

        return view('admin.trainers.schedule', compact('ptrainer', 'trainers', 'member', 'schedule'));
    }
    
    public function getScheduleData()
    {
        $scheduleData = DB::table('member_gym')
            ->join('trainers', 'trainers.id', '=', 'member_gym.idtrainer')
            ->select('member_gym.start_training as start', 'member_gym.end_training as end', 'trainers.name as title')
            ->get()
            ->map(function ($event) {
                $event->className = "bg-primary";
                return $event;
            });

        return response()->json($scheduleData);
    }

    public function storeSchedule(Request $request)
    {
        $request->validate([
            'idtrainer' => 'required',
            'iduser' => 'required',
            'date_trainer' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    // Memeriksa apakah sudah ada jadwal pada tanggal yang sama untuk idtrainer dan iduser
                    $existingSchedule = ScheduleTrainer::where('date_trainer', $value)
                        ->where('iduser', $request->iduser) // Memastikan member yang sama tidak bisa memilih tanggal yang sama
                        ->exists();

                    if ($existingSchedule) {
                        $fail('Tanggal ini sudah dipilih oleh member yang sama. Silakan pilih tanggal lain.');
                    }
                },
            ],
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        // Mendapatkan member_gym untuk iduser yang dipilih
        $memberGym = DB::table('member_gym')->where('id', $request->iduser)->first();

        if (!$memberGym) {
            return back()->withErrors(['iduser' => 'Member tidak valid.'])->withInput();
        }

        // Tentukan batas maksimal jadwal berdasarkan idpacket_trainer
        $limit = 0;
        switch ($memberGym->idpacket_trainer) {
            case 1:
                $limit = 5;
                break;
            case 2:
                $limit = 20;
                break;
            case 3:
                $limit = 30;
                break;
            default:
                return back()->withErrors(['iduser' => 'Paket tidak valid.'])->withInput();
        }

        // Hitung jumlah jadwal yang sudah ada untuk member ini
        $scheduleCount = ScheduleTrainer::where('iduser', $request->iduser)->count();

        if ($scheduleCount >= $limit) {
            return back()->withErrors(['iduser' => 'Member ini sudah mencapai batas maksimal jadwal (' . $limit . ').'])->withInput();
        }

        // Jika validasi lolos, simpan data
        ScheduleTrainer::create([
            'idtrainer' => $request->idtrainer,
            'iduser' => $request->iduser,
            'date_trainer' => $request->date_trainer,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.schedule')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function getScheduleDetail($iduser)
    {
       $schedule = DB::table('schedule_trainer')
            ->join('member_gym', 'schedule_trainer.iduser', '=', 'member_gym.id')
            ->join('users as umember', 'member_gym.idmember', '=', 'umember.id')
            ->join('users as utrainer', 'schedule_trainer.idtrainer', '=', 'utrainer.id')
            ->where('schedule_trainer.iduser', $iduser)
            ->select(
                'schedule_trainer.id as id',
                'umember.name as customer_name',
                'utrainer.name as trainer_name',
                'schedule_trainer.idtrainer as idtrainer',
                DB::raw('DATE_FORMAT(schedule_trainer.date_trainer, "%d %M %Y") as training_date'),
                DB::raw('DATE_FORMAT(schedule_trainer.start_time, "%H:%i") as start_time'),
                DB::raw('DATE_FORMAT(schedule_trainer.end_time, "%H:%i") as end_time'),
                DB::raw('CASE 
                            WHEN member_gym.idpacket_trainer = 1 THEN 5
                            WHEN member_gym.idpacket_trainer = 2 THEN 20
                            WHEN member_gym.idpacket_trainer = 3 THEN 30
                            ELSE 0 
                        END as total_sessions'),
                DB::raw('(CASE 
                            WHEN member_gym.idpacket_trainer = 1 THEN 5
                            WHEN member_gym.idpacket_trainer = 2 THEN 20
                            WHEN member_gym.idpacket_trainer = 3 THEN 30
                            ELSE 0 
                        END) - COUNT(schedule_trainer.id) as remaining_sessions')
            )
            ->groupBy(
                'schedule_trainer.id',
                'umember.name',
                'utrainer.name',
                'schedule_trainer.idtrainer',
                'schedule_trainer.date_trainer',
                'schedule_trainer.start_time',
                'schedule_trainer.end_time',
                'member_gym.idpacket_trainer'
            )
            ->orderBy(
                'schedule_trainer.date_trainer', 'asc'
            )
            ->get(); // Mendapatkan semua data terkait iduser

        return response()->json($schedule);
    }

    public function updateScheduleDetail(Request $request, $id)
    {
        // Pastikan format tanggal yang diterima sesuai dengan yang diinginkan
        $trainingDate = Carbon::parse($request->training_date)->format('Y-m-d');

        // Validasi dan konversi start_time dan end_time untuk memastikan format yang tepat
        $startTime = Carbon::parse($request->start_time)->format('H:i:s'); // Menambahkan detik ":00"
        $endTime = Carbon::parse($request->end_time)->format('H:i:s'); // Menambahkan detik ":00"

        // Cari jadwal berdasarkan ID
        $schedule = ScheduleTrainer::find($id);

        if (!$schedule) {
            return response()->json(['message' => 'Schedule not found'], 404);
        }

        // Update jadwal dengan data baru
        $schedule->update([
            'start_time' => $startTime,
            'end_time' => $endTime,
            'date_trainer' => $trainingDate,
            'idtrainer' => $request->idtrainer,
        ]);

        return redirect()->route('admin.schedule')->with('success', 'Jadwal berhasil di update.');
    }

    public function destroy($id)
    {
        $member = ScheduleTrainer::findOrFail($id);
        $member->delete();

        return redirect()->route('admin.data_member')->with('success', 'Member berhasil dihapus!');
    }

}