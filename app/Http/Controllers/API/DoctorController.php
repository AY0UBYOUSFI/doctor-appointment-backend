<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;

class DoctorController extends Controller
{
    // إرجاع كل الأطباء
    public function index()
    {
        $doctors = Doctor::with('user')->get();
        return response()->json($doctors);
    }
    
    // إضافة طبيب جديد
    public function store(Request $request)
    {
        $validated = $request->validate([
            
            'name' => 'required|string',
            'email' => 'required|email|unique:doctors',
            'phone' => 'nullable|string',
            'specialization' => 'required|string',
            'bio' => 'nullable|string',
        ]);

        $doctor = Doctor::create($validated);

        return response()->json($doctor, 201);
    }
}
