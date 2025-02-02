<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cms;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class CMSController extends Controller
{
    public function layout()
    {
        $cms = Cms::get();
        $content = [];

        $icons = [
            'ri-home-line' => '🏠 Home',
            'ri-user-line' => '👤 User',
            'ri-settings-line' => '⚙️ Settings',
            'ri-star-line' => '⭐ Star',
            'ri-heart-line' => '❤️ Heart',
            'ri-shopping-cart-line' => '🛒 Cart',
            'ri-search-line' => '🔍 Search',
            'ri-folder-line' => '📁 Folder',
            'ri-file-line' => '📄 File',
            'ri-camera-line' => '📷 Camera',
            'ri-notification-line' => '🔔 Notification',
            'ri-mail-line' => '✉️ Mail',
            'ri-calendar-line' => '📅 Calendar',
            'ri-phone-line' => '📞 Phone',
            'ri-globe-line' => '🌍 Globe',
            'ri-edit-line' => '✏️ Edit',
            'ri-trash-line' => '🗑️ Trash',
            'ri-lock-line' => '🔒 Lock',
            'ri-unlock-line' => '🔓 Unlock',
            'ri-dashboard-line' => '📊 Dashboard',
            'ri-chat-line' => '💬 Chat',
            'ri-logout-circle-line' => '🔚 Logout',
            'ri-chat-delete-line' => '🗨️ Delete Chat',
            'ri-delete-bin-line' => '🗑️ Delete Bin',
            'ri-home-8-line' => '🏡 Home 8',
            'ri-briefcase-4-line' => '💼 Briefcase',
            'ri-wallet-line' => '💰 Wallet',
            'ri-shopping-bag-line' => '👜 Shopping Bag',
            'ri-cloud-line' => '☁️ Cloud',
            'ri-sun-line' => '🌞 Sun',
            'ri-moon-line' => '🌙 Moon',
            'ri-sun-cloud-line' => '🌤️ Sun Cloud',
            'ri-snowy-line' => '❄️ Snowy',
            'ri-earth-line' => '🌏 Earth',
            'ri-leaf-line' => '🍃 Leaf',
            'ri-star-half-line' => '⭐ Half Star',
            'ri-globe-alt-line' => '🌐 Globe Alt',
            'ri-book-line' => '📚 Book',
            'ri-coupon-line' => '🎟️ Coupon',
            'ri-umbrella-line' => '☂️ Umbrella',
            'ri-trophy-line' => '🏆 Trophy',
            'ri-cake-line' => '🍰 Cake',
            'ri-git-merge-line' => '🔀 Git Merge',
            'ri-link-m' => '🔗 Link M',
            'ri-anchor-line' => '⚓ Anchor',
            'ri-map-pin-line' => '📍 Map Pin',
            'ri-add-line' => '➕ Add',
            'ri-heart-2-line' => '💖 Heart 2',
            'ri-eye-line' => '👁️ Eye',
            'ri-video-line' => '🎥 Video',
            'ri-mic-line' => '🎙️ Mic',
            'ri-signal-tower-line' => '📶 Signal Tower',
            'ri-archive-line' => '📦 Archive',
            'ri-print-line' => '🖨️ Print',
            'ri-laptop-line' => '💻 Laptop',
            'ri-desktop-line' => '🖥️ Desktop',
            'ri-tablet-line' => '📱 Tablet',
            'ri-phone-fill' => '📱 Phone Fill',
        ];

        foreach ($cms as $key => $value) {
            if ($value['section'] == "hero") {
                $content['hero'] = $value['content'];
            }

            if ($value['section'] == "jelajahi") {
                $content['jelajahi'] = $value['content'];
            }

            if ($value['section'] == "service") {
                $content['service'] = $value['content'];
            }

            if ($value['section'] == "join") {
                $content['join'] = $value['content'];
            }

            if ($value['section'] == "price") {
                $content['price'] = $value['content'];
            }
        }

        return view('admin/cms/layout', compact('content', 'icons'));
    }

    public function store(Request $request)
    {
        $data = $request->except('_token'); // Ambil semua input kecuali CSRF token
        $sections = [
            'hero' => ['hero_mini_text', 'hero_description', 'hero_short_description', 'hero_image'],
            'jelajahi' => ['program1_icon', 'program1_title', 'program1_description', 'program2_icon', 'program2_title', 'program2_description', 'program3_icon', 'program3_title', 'program3_description', 'program4_icon', 'program4_title', 'program4_description'],
            'service' => ['service_image1', 'service_image2', 'service_title', 'service_description'],
            'join' => ['join_title', 'join_description', 'join_image1', 'why_join_title1', 'why_join_description1', 'why_join_long_description1', 'why_join_title2', 'why_join_description2', 'why_join_long_description2', 'why_join_title3', 'why_join_description3', 'why_join_long_description3'],
            'price' => ['pricing_title','pricing_description','pricing_title1','pricing_description1','pricing_long_description1','pricing_title2','pricing_description2','pricing_long_description2','pricing_title3','pricing_description3','pricing_long_description3',],
        ];

        // Ambil data lama dari database sebelum menghapusnya
        $oldData = Cms::pluck('content', 'section')->toArray();

        Cms::query()->delete(); // Menghapus semua data lama

        foreach ($sections as $section => $fields) {
            $content = [];

            foreach ($fields as $field) {
                if ($request->hasFile($field)) {
                    
                    // Jika ada file gambar yang diunggah
                    $image = $request->file($field);
                    $fileName = $field . '.png';
                    $path = $image->move(public_path('uploads'), $fileName);

                    if ($path) {
                        \Log::info('File ' . $field . ' berhasil disimpan: ' . $path);
                    } else {
                        \Log::error('Gagal menyimpan file ' . $field);
                    }

                    $content[$field] = 'uploads/' . $fileName;
                } elseif ($request->filled($field) && strpos($request->input($field), 'data:image/') === 0) {
                    // Jika gambar dalam format base64
                    $imageData = str_replace('data:image/png;base64,', '', $request->input($field));
                    $imageData = str_replace(' ', '+', $imageData);
                    $fileName = $field . '.png';
                    $photoPath = 'uploads/photos/' . $fileName;

                    try {
                        file_put_contents(public_path($photoPath), base64_decode($imageData));
                        \Log::info('Base64 image ' . $field . ' berhasil disimpan di: ' . $photoPath);
                    } catch (\Exception $e) {
                        \Log::error('Gagal menyimpan gambar base64 ' . $field . ': ' . $e->getMessage());
                    }

                    $content[$field] = $photoPath;
                } else {
                    // Jika field lain memiliki input, gunakan input yang diberikan
                    $content[$field] = $data[$field] ?? null;
                }
            }

            Cms::updateOrCreate(
                ['section' => $section],
                ['content' => $content]
            );
        }

        return redirect()->back();
    }


    public function deleteAllFilesInFolder()
    {
        $directory = public_path('uploads'); // Tentukan folder tempat file disimpan

        // Menghapus semua isi dalam folder, termasuk file dan subfolder
        File::cleanDirectory($directory);

        \Log::info('Semua file dalam folder telah dihapus.');

        return redirect()->back()->with('success', 'Semua file dalam folder berhasil dihapus.');
    }
}
