<?php

namespace App\Http\Controllers\Api;

use App\Models\Device;
use App\Models\Donation;
use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DonationController extends Controller
{
    public function index()
    {
        $medicines = Medicine::all();
        $devices = Device::all();

        $donations = $medicines->concat($devices);
        return response()->json(['donations' => $donations], 200);
    }
}
