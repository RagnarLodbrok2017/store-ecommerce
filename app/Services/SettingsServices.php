<?php

namespace App\Services;

use App\Http\Requests\SettingsRequest;
use App\Models\Setting;
use App\Traits\FileTrait;

class SettingsServices
{
    use FileTrait;
    public function getAllSettings(){
        $settings = Setting::all();
        return $settings;
    }
    public function StoreSettings(SettingsRequest $request)
    {
        if ($request->validated())
        {
            $requestedData = $request->all();
            $oldSettings = Setting::first();
            if ($request->hasFile('logo'))
            {
                if ($oldSettings->logo && $oldSettings->logo !== "assets/admin/images/settings/logo/logo.png")
                {
                    $this->deleteFile($oldSettings->logo);
                }
                $logoName = $this->saveFile("logo",$request->logo);
                $requestedData['logo'] = $logoName;
            }
            else{
                $requestedData['logo'] = $oldSettings->logo;
            }
            if ($request->hasFile('favicon'))
            {
                if ($oldSettings->favicon && $oldSettings->favicon !== "assets/admin/images/settings/favicon/favicon.png")
                {
                    $this->deleteFile($oldSettings->favicon);
                }
                $faviconName = $this->saveFile("assets/admin/images/settings/favicon",$request->favicon);
                $requestedData['favicon'] = $faviconName;
            }
            else{
                $requestedData['favicon'] = $oldSettings->favicon;
            }
            Setting::whereNotNull('id')->delete();
            $settings = Setting::create($requestedData);
            return $settings;
        }
    }






}
