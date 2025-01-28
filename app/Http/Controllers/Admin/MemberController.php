<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MemberInformation;
use App\Models\Member_gym;
use App\Models\Country;
use App\Models\City;
use App\Models\State;
use App\Models\User;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MemberController extends Controller
{
    public function index()
    {
        $memberActive = DB::table('member_gym')
            ->select('member_gym.id as id', 
            'member_gym.iduser as iduser', 
            'users.name as name_member', 
            'users.barcode as barcode', 
            'users.photo as photo', 
            'users.upload as upload', 
            'users.number_phone as number_phone', 
            'packets.packet_name as packet_name', 
            'packets.days as days', 
            'member_gym.end_training as end_training',
            'member_gym.start_training as start_training',
            'packet_trainer.pertemuan as pertemuan')
            ->leftJoin('users', 'users.id', '=', 'member_gym.idmember')
            ->leftJoin('packet_trainer', 'packet_trainer.id', '=', 'member_gym.idpacket_trainer')
            ->leftJoin('packets', 'packets.id', '=', 'member_gym.idpaket')
            ->orderBy('member_gym.end_training', 'desc')
            ->paginate(10);

        foreach ($memberActive as $member) {
            // Generate QR Code
            $qrCode = new QrCode(route('information.member', ['id' => $member->id])); // URL dinamis
            $writer = new PngWriter();
            $qrCodeImage = $writer->write($qrCode);
            $member->qrcode = base64_encode($qrCodeImage->getString());

            // Tentukan status berdasarkan tanggal
            $endDate = Carbon::parse($member->end_training);
            $currentDate = Carbon::now();

            if ($currentDate->lessThanOrEqualTo($endDate)) {
                $member->status_label = 'Active';
                $member->status_class = 'btn bgl-success text-success';
            }
            else {
                $member->status_label = 'Non-Active';
                $member->status_class = 'btn bgl-danger text-danger';
            }

            $remainingDays = $currentDate->diffInDays($endDate, false);
            $member->time_remaining = $currentDate->diffForHumans($endDate, [
                'parts' => 3, // Menampilkan hingga 3 bagian waktu (misalnya "1 bulan, 2 minggu, 3 hari")
                'syntax' => Carbon::DIFF_RELATIVE_TO_NOW,
            ]);

            $member->show_button = $remainingDays <= 3 && $remainingDays >= 0;
            if (isset($member->number_phone)) {
                $formattedPhoneNumber = preg_replace('/^0/', '62', $member->number_phone); // Ubah awalan 0 menjadi 62
            } else {
                $formattedPhoneNumber = null; // Jika nomor tidak ada
            }
        
            if ($member->show_button && $formattedPhoneNumber) {
                $message = urlencode("Halo, ini dari Elite Fitness Kediri. Kami ingin mengingatkan bahwa keanggotaan Anda akan segera berakhir pada tanggal " . $endDate->format('d-m-Y') . ". Mohon konfirmasi untuk perpanjangan member Anda. Terima kasih!");
                $member->wa_link = "https://wa.me/{$formattedPhoneNumber}?text={$message}";
            } else {
                $member->wa_link = null;
            }
        }

        $paket = DB::table('packets')->get();
        $paket_trainer = DB::table('packet_trainer')->get();
        $trainer = DB::table('trainers')->where('status', 'Aktif')->get();
        $member = DB::table('users')->where('role', 'member')->get();

        return view('admin.member.index', [
            'paket' => $paket,
            'paket_trainer' => $paket_trainer,
            'trainer' => $trainer,
            'member' => $member,
            'active' => $memberActive,
        ]);
    }

    public function add_member(Request $request)
    {
        $countries = Country::all();
        return view('admin.member.add', compact('countries'));
    }

    public function getStates(Request $request)
    {
        $states = State::where('country_id', $request->country_id)->get();
        return response()->json($states);
    }

    public function getCities(Request $request)
    {
        $cities = City::where('state_id', $request->state_id)->get();
        return response()->json($cities);
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
            'role' => 'member',
            'photo' => $photoPath,
            'upload' => $uploadPath,
        ]);

        return redirect()->route('admin.data_member');
    }

    public function update(Request $request, $id)
    {
        $member = Member_gym::findOrFail($id);
        $member->update([
            'idmember' => $request->idmember,
            'idpacket_trainer' => $request->idpacket_trainer,
            'idpaket' => $request->idpaket,
            'total_price' => $request->total_price,
            'start_training' => $request->start_training,
            'end_training' => $request->end_training,
        ]);

        return redirect()->route('admin.data_member');
    }

    public function perpanjangan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'notelp' => 'required|string|max:15',
            'idpaket' => 'required',
            'idtrainer' => 'nullable',
            'gender' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string',
            'pertemuan' => 'required|string',
            'status' => 'required|string',
        ]);

        $barcode = 'MBR-' . strtoupper(uniqid());

        $member = MemberInformation::create([
            'iduser' => auth()->id(),
            'idpaket' => $request->idpaket,
            'idtrainer' => $request->idtrainer ?? null,
            'name' => $request->name,
            'notelp' => $request->notelp,
            'barcode' => $barcode,
            'status' => $request->status,
            'gender' => $request->gender,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'pertemuan' => $request->pertemuan,
        ]);

        $barcodePath = public_path('assets/images/barcode');
        if (!file_exists($barcodePath)) {
            mkdir($barcodePath, 0777, true);
        }

        $qrCode = new QrCode($barcode);
        $writer = new PngWriter();
        $qrCodeImage = $writer->write($qrCode);

        $qrCodeImage->saveToFile($barcodePath . '/' . $barcode . '_qrcode.png');

        return redirect()->route('admin.members.data_member')->with('success', 'Member berhasil ditambahkan!');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('member_gym')
        ->select('member_gym.id as id', 
        'member_gym.iduser as iduser', 
        'users.name as name_member', 
        'users.barcode as barcode', 
        'users.photo as photo', 
        'users.upload as upload', 
        'users.number_phone as number_phone', 
        'packets.packet_name as packet_name', 
        'packets.days as days', 
        'member_gym.end_training as end_training',
        'member_gym.start_training as start_training',
        'packet_trainer.pertemuan as pertemuan')
        ->leftJoin('users', 'users.id', '=', 'member_gym.idmember')
        ->leftJoin('packet_trainer', 'packet_trainer.id', '=', 'member_gym.idpacket_trainer')
        ->leftJoin('packets', 'packets.id', '=', 'member_gym.idpaket')
        ->orderBy('member_gym.end_training', 'desc');

        if ($search) {
            $query->where('users.name', 'like', '%' . $search . '%');
        }

        $memberActive = $query->paginate(10); // Mendapatkan data yang sudah difilter dan dipaginasi

        foreach ($memberActive as $member) {
            // Generate QR Code
            $qrCode = new QrCode(route('information.member', ['id' => $member->id])); // URL dinamis
            $writer = new PngWriter();
            $qrCodeImage = $writer->write($qrCode);
            $member->qrcode = base64_encode($qrCodeImage->getString());

            // Tentukan status berdasarkan tanggal
            $endDate = Carbon::parse($member->end_training);
            $currentDate = Carbon::now();

            if ($currentDate->lessThanOrEqualTo($endDate)) {
                $member->status_label = 'Active';
                $member->status_class = 'btn bgl-success text-success';
            }
            else {
                $member->status_label = 'Non-Active';
                $member->status_class = 'btn bgl-danger text-danger';
            }

            $remainingDays = $currentDate->diffInDays($endDate, false);
            $member->time_remaining = $currentDate->diffForHumans($endDate, [
                'parts' => 3, // Menampilkan hingga 3 bagian waktu (misalnya "1 bulan, 2 minggu, 3 hari")
                'syntax' => Carbon::DIFF_RELATIVE_TO_NOW,
            ]);

            $member->show_button = $remainingDays <= 3 && $remainingDays >= 0;
            if (isset($member->number_phone)) {
                $formattedPhoneNumber = preg_replace('/^0/', '62', $member->number_phone); // Ubah awalan 0 menjadi 62
            } else {
                $formattedPhoneNumber = null; // Jika nomor tidak ada
            }
        
            if ($member->show_button && $formattedPhoneNumber) {
                $message = urlencode("Halo, ini dari Elite Fitness Kediri. Kami ingin mengingatkan bahwa keanggotaan Anda akan segera berakhir pada tanggal " . $endDate->format('d-m-Y') . ". Mohon konfirmasi untuk perpanjangan member Anda. Terima kasih!");
                $member->wa_link = "https://wa.me/{$formattedPhoneNumber}?text={$message}";
            } else {
                $member->wa_link = null;
            }
        }

        $paket = DB::table('packets')->get();
        $paket_trainer = DB::table('packet_trainer')->get();
        $trainer = DB::table('trainers')->where('status', 'Aktif')->get();
        $member = DB::table('users')->where('role', 'member')->get();

        return view('admin.member.index', [
            'paket' => $paket,
            'paket_trainer' => $paket_trainer,
            'trainer' => $trainer,
            'member' => $member,
            'active' => $memberActive,
        ]);
    }

    public function show($id)
    {
        $member = MemberInformation::findOrFail($id);
        return view('admin.member.show', compact('member'));
    }

    public function cetakBarcode($id)
    {
        // Mengambil data dari tabel member_gym dan member_information melalui join
        $member = DB::table('member_gym')
            ->join('member_information', 'member_gym.idmember', '=', 'member_information.id')
            ->select('member_information.barcode', 'member_information.name')
            ->where('member_gym.id', $id)
            ->first();

        if (!$member) {
            abort(404, 'Member not found');
        }

        // Membuat QR code dengan URL menuju route 'information.member'
        $qrCode = new QrCode(route('information.member', ['id' => $id]));
        $writer = new PngWriter();
        $qrCodeImage = $writer->write($qrCode);

        // Menyimpan QR code ke dalam folder public/assets/images/barcode
        $qrCodeImage->saveToFile(public_path('assets/images/barcode/' . $member->barcode . '_qrcode.png'));

        return view('admin.member.cetak_barcode', compact('member'));
    }

    public function destroy($id)
    {
        $member = Member_gym::findOrFail($id);
        $member->delete();

        return redirect()->route('admin.data_member')->with('success', 'Member berhasil dihapus!');
    }

    public function tambah_dataMember(Request $request)
    {
        $request->validate([
            'idmember' => 'required|exists:users,id',
            'idpaket' => 'required|exists:packets,id',
            'start_training' => 'required|date',
            'end_training' => 'required|date|after_or_equal:start_training',
        ]);

        // Set waktu akhir training menjadi 23:59:59
        $endTraining = \Carbon\Carbon::parse($request->end_training)->endOfDay();

        Member_gym::create([
            'iduser' => auth()->id(),
            'idmember' => $request->idmember,
            'idpacket_trainer' => $request->idpacket_trainer,
            'idpaket' => $request->idpaket,
            'total_price' => $request->total_price,
            'point' => $request->point,
            'start_training' => $request->start_training,
            'end_training' => $endTraining,  // Gunakan waktu yang sudah diubah
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Data member berhasil ditambahkan.');
    }

        public function getMember($id)
        {
            $member = DB::table('member_gym')
                ->join('users', 'users.id', '=', 'member_gym.idmember')
                ->join('packet_trainer', 'packet_trainer.id', '=', 'member_gym.idpacket_trainer')
                ->select(
                    'member_gym.id',
                    DB::raw('DATE_FORMAT(member_gym.start_training, "%Y-%m-%d") as start_training'),
                    DB::raw('DATE_FORMAT(member_gym.end_training, "%Y-%m-%d") as end_training'),
                    'users.name',
                    'member_gym.idpaket',
                    'member_gym.idpacket_trainer',
                    'member_gym.idmember',
                    'packet_trainer.pertemuan',
                    'packet_trainer.poin',
                    'packet_trainer.price',
                )
                ->where('member_gym.id', $id)
                ->first();

            if ($member) {
                return response()->json($member);
            }

            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
}