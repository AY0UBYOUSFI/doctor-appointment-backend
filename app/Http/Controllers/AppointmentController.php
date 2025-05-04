<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User; // To find the patient
use App\Models\Doctor; // To find the doctor
use App\Notifications\AppointmentNotification; // Notification class

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        // Validate incoming data
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date',
            'time' => 'required',
        ]);
        $validated['user_id'] = auth()->id();    

        // Create the appointment
        $appointment = Appointment::create($validated);

        // Find the patient and doctor to notify
        //$patient = User::find($validated['user_id']);
        //$doctor = Doctor::find($validated['doctor_id']);

        // Notify both patient and doctor
        //$patient->notify(new AppointmentNotification($appointment));
        //$doctor->notify(new AppointmentNotification($appointment));

        return response()->json($appointment, 201);
    }

    public function index()
    {
        return Appointment::with(['doctor', 'user'])->get();
    }

    public function cancel($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->status = 'cancelled';
        $appointment->save();

        // Find the patient and doctor to notify
        $patient = User::find($appointment->user_id);
        $doctor = Doctor::find($appointment->doctor_id);

        // Notify both patient and doctor about cancellation
        $patient->notify(new AppointmentNotification($appointment));
        $doctor->notify(new AppointmentNotification($appointment));

        return response()->json(['message' => 'Appointment cancelled.']);
    }

    // إعادة جدولة موعد
    public function reschedule(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->appointment_date = $request->input('date');
        $appointment->start_time = $request->input('time');
        $appointment->status = 'pending'; // reset to pending
        $appointment->save();

        // Find the patient and doctor to notify
        $patient = User::find($appointment->user_id);
        $doctor = Doctor::find($appointment->doctor_id);

        // Notify both patient and doctor about the rescheduling
        $patient->notify(new AppointmentNotification($appointment));
        $doctor->notify(new AppointmentNotification($appointment));

        return response()->json(['message' => 'Appointment rescheduled.']);
    }

    // موافقة على الموعد من الطبيب
    public function approve($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->status = 'confirmed';
        $appointment->save();

        // Find the patient and doctor to notify
        $patient = User::find($appointment->user_id);
        $doctor = Doctor::find($appointment->doctor_id);

        // Notify both patient and doctor about confirmation
        $patient->notify(new AppointmentNotification($appointment));
        $doctor->notify(new AppointmentNotification($appointment));

        return response()->json(['message' => 'Appointment approved.']);
    }

    // رفض الموعد
    public function reject($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->status = 'cancelled';
        $appointment->save();

        // Find the patient and doctor to notify
        $patient = User::find($appointment->user_id);
        $doctor = Doctor::find($appointment->doctor_id);

        // Notify both patient and doctor about rejection
        $patient->notify(new AppointmentNotification($appointment));
        $doctor->notify(new AppointmentNotification($appointment));

        return response()->json(['message' => 'Appointment rejected.']);
    }
}