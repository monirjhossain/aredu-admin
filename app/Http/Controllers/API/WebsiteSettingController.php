<?php

namespace App\Http\Controllers\API;

use App\Models\WebsiteSetting;

class WebsiteSettingController extends BaseController
{
    public function show()
    {
        return $this->sendResponse([
            'settings' => WebsiteSetting::publicPayload(),
        ], 'successfully.');
    }
}
