<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class ScheduleController extends Controller
{
    public function destroy($id)
    {
        $schedule = Schedule::find($id);

        if ($schedule) {
            $schedule->delete();
        }

        return response()->json(['status' => 'deleted']);
    }

    public function setAppointmentTime(Request $request, Schedule $schedule)
    {
        $schedule->appointment_time = $request->input('appointment_time');
        $schedule->save();

        return redirect()->back()->with('message', 'Appointment time set successfully');
    }

    public function accept(Request $request, Schedule $schedule)
    {
        $schedule->appointment_time = $request->input('appointment_time');
        $schedule->status = 'accepted';
        $schedule->save();

        return response()->json(['status' => 'accepted']);
    }

    public function decline(Schedule $schedule)
    {
        $schedule->status = 'declined';
        $schedule->save();

        return response()->json(['status' => 'declined']);
    }
}
