<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    use ResponseTrait;

    /**
     * Display the settings page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // In a real application, you would fetch settings from the database.
        // For now, we can return a view with some dummy data.
        $settings = Setting::first();
        return view('owner.settings.index', compact('settings'));
    }

    /**
     * Update the application settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'site_name' => 'required|string|max:255',
                'light_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'dark_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $setting = Setting::first(); // Assuming there's a Setting model

            $saveData = [
                'site_name' => $request->site_name,
                'site_email' => $request->site_email,
                'site_phone' => $request->site_phone,
                'site_address' => $request->site_address,
                'facebook_url' => $request->facebook_url,
                'instagram_url' => $request->instagram_url,
                'twitter_url' => $request->twitter_url,
                'linkedin_url' => $request->linkedin_url,
                'youtube_url' => $request->youtube_url,
                'pinterest_url' => $request->pinterest_url,
                'tiktok_url' => $request->tiktok_url,
                'whatsapp_url' => $request->whatsapp_url,
                'telegram_url' => $request->telegram_url,
            ];

            if($request->hasFile('light_logo')) {
                if($setting && $setting->light_logo) {
                    deleteImgFile($setting->light_logo, SETTINGS_IMAGE_PATH);
                }
                $saveData['light_logo'] = uploadImgFile($request->light_logo, SETTINGS_IMAGE_PATH,'light_logo_');
            }

            if($request->hasFile('dark_logo')) {
                if($setting && $setting->dark_logo) {
                    deleteImgFile($setting->dark_logo, SETTINGS_IMAGE_PATH);
                }
                $saveData['dark_logo'] = uploadImgFile($request->dark_logo, SETTINGS_IMAGE_PATH,'dark_logo_');
            }

            if($request->hasFile('favicon')) {
                if($setting && $setting->favicon) {
                    deleteImgFile($setting->favicon, SETTINGS_IMAGE_PATH);
                }
                $saveData['favicon'] = uploadImgFile($request->favicon, SETTINGS_IMAGE_PATH,'favicon_');
            }

            if($setting) {
                $setting->update($saveData);
            } else {
                Setting::create($saveData);
            }
            DB::commit();
            return $this->sendSuccess('Settings updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }
}