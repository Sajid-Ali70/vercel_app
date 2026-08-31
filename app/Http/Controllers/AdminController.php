<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function showLogin()
    {
        if (Session::has('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate(['password' => 'required']);

        try {
            $admin = DB::table('admins')->where('username', 'admin')->first();
            if ($admin && Hash::check($request->password, $admin->password)) {
                Session::put('admin_logged_in', true);
                return redirect()->route('admin.dashboard');
            }
        } catch (\Exception $e) {
            return back()->withErrors(['password' => 'Database error: Ensure SQL files are imported.']);
        }
        return back()->withErrors(['password' => 'Password incorrect']);
    }

    public function dashboard()
    {
        $settings = null;
        $reviews = [];
        try {
            $settings = DB::table('app_settings')->where('id', 1)->first();
            $reviews = DB::table('app_reviews')->orderBy('id', 'desc')->get();
        } catch (\Exception $e) {}

        if (!$settings) {
            $settings = (object)[
                'app_name' => 'Alfa Mobiles',
                'developer' => 'Alfa Mobiles Mart Karachi',
                'category' => 'Shopping',
                'tags' => 'Contains ads · In-app purchases',
                'app_icon' => '',
                'rating_score' => '4.3',
                'reviews_count' => '1.9K reviews',
                'downloads_count' => '3K+',
                'content_rating' => 'Rated for 3+',
                'updated_date' => 'Aug 14, 2026',
                'description' => '',
                'release_notes' => '',
                'screenshots' => '[]',
                'apk_url' => '/uploads/apk/app-release.apk',
                'active_theme' => 'playstore'
            ];
        }

        return view('admin.dashboard', compact('settings', 'reviews'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->only([
            'app_name', 'developer', 'category', 'tags',
            'rating_score', 'reviews_count', 'downloads_count',
            'content_rating', 'updated_date', 'description', 'release_notes',
            'active_theme'
        ]);

        if ($request->hasFile('app_icon_file')) {
            $file = $request->file('app_icon_file');
            $fileName = 'app_icon_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $fileName);
            $data['app_icon'] = '/uploads/' . $fileName;
        }

        try {
            $existingSettings = DB::table('app_settings')->where('id', 1)->first();
            $currentScreenshots = json_decode($existingSettings->screenshots ?? '[]', true);

            if ($request->hasFile('screenshots')) {
                foreach ($request->file('screenshots') as $file) {
                    $fileName = 'screenshot_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/screenshots'), $fileName);
                    $currentScreenshots[] = '/uploads/screenshots/' . $fileName;
                }
            }

            $data['screenshots'] = json_encode($currentScreenshots);
            $data['updated_at'] = now();

            DB::table('app_settings')->updateOrInsert(['id' => 1], $data);
            return response()->json(['message' => 'Settings updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Database error: ' . $e->getMessage()], 500);
        }
    }

    public function removeScreenshot(Request $request)
    {
        $index = $request->index;
        try {
            $settings = DB::table('app_settings')->where('id', 1)->first();
            $screenshots = json_decode($settings->screenshots ?? '[]', true);

            if (isset($screenshots[$index])) {
                $filePath = public_path($screenshots[$index]);
                if (File::exists($filePath)) File::delete($filePath);
                array_splice($screenshots, $index, 1);
                DB::table('app_settings')->where('id', 1)->update(['screenshots' => json_encode($screenshots)]);
                return response()->json(['message' => 'Screenshot removed']);
            }
        } catch (\Exception $e) {}
        return response()->json(['message' => 'Error removing screenshot'], 500);
    }

    private function convertDriveLink($url)
    {
        if (strpos($url, 'drive.google.com') !== false) {
            // Case 1: /d/FILE_ID
            if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
                return "https://drive.google.com/uc?export=download&id=" . $matches[1];
            }
            // Case 2: ?id=FILE_ID
            if (preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
                return "https://drive.google.com/uc?export=download&id=" . $matches[1];
            }
        }
        return $url;
    }

    public function updateApkUrl(Request $request)
    {
        $request->validate(['apk_url' => 'required']);

        $apkUrl = $this->convertDriveLink($request->apk_url);

        try {
            DB::table('app_settings')->updateOrInsert(['id' => 1], [
                'apk_url' => $apkUrl,
                'updated_at' => now()
            ]);
            return response()->json(['message' => 'APK URL updated successfully', 'url' => $apkUrl]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Database error: ' . $e->getMessage()], 500);
        }
    }

    public function uploadApk(Request $request)
    {
        if (!$request->hasFile('apk_file')) {
            return response()->json(['message' => 'No file uploaded'], 400);
        }

        $file = $request->file('apk_file');
        if (strtolower($file->getClientOriginalExtension()) !== 'apk') {
            return response()->json(['message' => 'Only .apk files are allowed'], 400);
        }

        try {
            $apkDir = public_path('uploads/apk');
            if (!File::isDirectory($apkDir)) {
                File::makeDirectory($apkDir, 0777, true, true);
            }

            // Get current settings to delete old file
            $settings = DB::table('app_settings')->where('id', 1)->first();
            if ($settings && $settings->apk_url) {
                if (str_starts_with($settings->apk_url, '/uploads/apk/')) {
                    $oldPath = public_path($settings->apk_url);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }
            }

            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $fileName = $originalName . '_' . time() . '.apk';
            $file->move($apkDir, $fileName);
            $apkUrl = '/uploads/apk/' . $fileName;

            DB::table('app_settings')->updateOrInsert(['id' => 1], [
                'apk_url' => $apkUrl,
                'updated_at' => now()
            ]);

            return response()->json(['message' => 'APK uploaded successfully', 'url' => $apkUrl]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Upload error: ' . $e->getMessage()], 500);
        }
    }

    public function addReview(Request $request)
    {
        $request->validate([
            'reviewer_name' => 'required',
            'rating' => 'required|numeric|min:1|max:5',
            'review_date' => 'required',
            'review_text' => 'required',
        ]);

        $avatarLetter = strtoupper(substr($request->reviewer_name, 0, 1));

        try {
            DB::table('app_reviews')->insert([
                'reviewer_name' => $request->reviewer_name,
                'rating' => $request->rating,
                'review_date' => $request->review_date,
                'review_text' => $request->review_text,
                'avatar_letter' => $avatarLetter,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return response()->json(['message' => 'Review added successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Database error'], 500);
        }
    }

    public function deleteReview(Request $request)
    {
        try {
            DB::table('app_reviews')->where('id', $request->id)->delete();
            return response()->json(['message' => 'Review deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Database error'], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:4|confirmed',
        ]);

        try {
            $admin = DB::table('admins')->where('username', 'admin')->first();
            if (!$admin || !Hash::check($request->current_password, $admin->password)) {
                return response()->json(['message' => 'Current password incorrect'], 422);
            }

            DB::table('admins')->where('username', 'admin')->update([
                'password' => Hash::make($request->new_password),
                'updated_at' => now()
            ]);
            return response()->json(['message' => 'Password updated']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Database error'], 500);
        }
    }

    public function logout()
    {
        Session::forget('admin_logged_in');
        return redirect()->route('admin.login');
    }
}
