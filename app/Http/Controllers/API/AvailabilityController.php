<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Availability;

class AvailabilityController extends Controller
{
    // عرض التوفر الخاص بالطبيب الحالي
    public function index()
    {
        $availabilities = Availability::where('doctor_id', auth()->id())->get();
        return response()->json($availabilities);
    }
    // 📅 عرض التوفر الخاص بدكتور معين (باستخدام doctor_id)
public function doctorAvailability($doctorId)
{
    $availabilities = Availability::where('doctor_id', $doctorId)->get();
    return response()->json($availabilities);
}


    // إضافة توفر جديد
    public function store(Request $request)
    {
        $validated = $request->validate([
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $validated['doctor_id'] = auth()->id();

        $availability = Availability::create($validated);

        return response()->json($availability, 201);
    }
}
