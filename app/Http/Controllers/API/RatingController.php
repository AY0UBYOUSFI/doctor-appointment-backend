<?php
// App/Http/Controllers/API/RatingController.php

namespace App\Http\Controllers\API;

use App\Models\Rating;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    // 🔥 Fetch all ratings
    public function index()
    {
        $ratings = Rating::with('doctor', 'user')->get(); // مع علاقات الدكتور واليوزر لو تحب
        return response()->json($ratings);
    }

    // ✅ Store a new rating
    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        $rating = Rating::create($validated);

        return response()->json($rating, 201);
    }
    public function doctorRatings($doctor_id)
{
    $ratings = Rating::where('doctor_id', $doctor_id)->with('user')->get();
    return response()->json($ratings);
}

}
