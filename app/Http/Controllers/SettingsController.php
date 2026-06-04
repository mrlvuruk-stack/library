<?php

namespace App\Http\Controllers;

use App\Models\settings;
use App\Http\Requests\StoresettingsRequest;
use App\Http\Requests\UpdatesettingsRequest;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('settings',['data' => settings::latest()->first()]);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatesettingsRequest  $request
     * @param  \App\Models\settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatesettingsRequest $request)
    {
        $setting = settings::latest()->first() ?: new settings();
        
        $setting->library_name = $request->library_name;
        $setting->email = $request->email;
        $setting->phone = $request->phone;
        $setting->address = $request->address;
        $setting->return_days = $request->return_days;
        $setting->fine = $request->fine;

        if ($request->filled('logo')) {
            $logoData = $request->logo;
            if (preg_match('/^data:image\/(\w+);base64,/', $logoData, $type)) {
                $logoData = substr($logoData, strpos($logoData, ',') + 1);
                $type = strtolower($type[1]);
                if (in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                    $logoData = base64_decode($logoData);
                    if ($logoData !== false) {
                        // Delete old custom logo
                        if ($setting->logo && file_exists(public_path('/images/' . $setting->logo))) {
                            @unlink(public_path('/images/' . $setting->logo));
                        }

                        $fileName = 'logo_' . time() . '.' . $type;
                        $destinationPath = public_path('/images');
                        file_put_contents($destinationPath . '/' . $fileName, $logoData);
                        $setting->logo = $fileName;
                    }
                }
            }
        }

        $setting->save();
        return redirect()->route('settings')->with('success', 'Library profile updated successfully.');
    }
}
