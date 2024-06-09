<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'status' => 'required|in:available,unavailable', // Add this line
        ]);

        Appointment::create([
            'professional_id' => Auth::guard('professional')->id(),
            'date' => $request->date,
            'status' => $request->status, // Add this line
        ]);

        return redirect()->back()->with('message', 'Appointment created successfully');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('professional.dashboard');
    }
}
