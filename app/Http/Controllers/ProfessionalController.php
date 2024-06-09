<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Professional;
use App\Models\Appointment;
use App\Models\Schedule;
use DateTime;
use DateInterval;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProfessionalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:professional')->except(['showRegistrationForm', 'register', 'showLoginForm', 'login', 'search']);
    }

    public function showRegistrationForm()
    {
        return view('professional_register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'titre' => 'required',
            'name' => 'required',
            'persoPhone' => 'required',
            'email' => 'required|email|unique:professionals,email',
            'password' => 'required|min:8',
            'specialization' => 'required',
            'city' => 'required',
            'address' => 'required',
            'proPhone' => 'required',
            'location' => 'required',
            'description' => 'nullable',
        ]);

        $professional = new Professional([
            'titre' => $request->titre,
            'name' => $request->name,
            'persoPhone' => $request->persoPhone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'specialization' => $request->specialization,
            'city' => $request->city,
            'address' => $request->address,
            'proPhone' => $request->proPhone,
            'location' => $request->location,
            'description' => $request->description,
        ]);

        $professional->save();

        Auth::guard('professional')->login($professional);

        return redirect()->intended(route('professional.dashboard'));
    }
    public function showLoginForm()
    {
        return view('professional_login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('professional')->attempt($credentials)) {
            // Authentication passed...
            return redirect()->intended(route('professional.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function dashboard()
    {
        $professional = Auth::user();
        $appointments = Appointment::where('professional_id', $professional->id)->get();
        $schedules = Schedule::where('professional_id', $professional->id)->get();

        $calendar1 = $this->generateCalendar(date('m'), date('Y'));
        $calendar2 = $this->generateCalendar(date('m') + 1, date('Y'));
        $calendar3 = $this->generateCalendar(date('m') + 2, date('Y'));

        return view('professional_dashboard', compact('professional', 'appointments', 'schedules', 'calendar1', 'calendar2', 'calendar3'));
    }

    public function generateCalendar($month, $year)
    {
        $professional = Auth::user();
        $appointments = Appointment::where('professional_id', $professional->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->groupBy(function ($appointment) {
                // ...

                return Carbon::parse($appointment->date)->day;
            });

        $daysOfWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
        $numberDays = date('t', $firstDayOfMonth);
        $dateComponents = getdate($firstDayOfMonth);
        $dayOfWeek = $dateComponents['wday'] == 0 ? 6 : $dateComponents['wday'] - 1;
        $calendar = "<table><tr>";

        // Create the calendar headers
        foreach ($daysOfWeek as $day) {
            $calendar .= "<th class='header'>$day</th>";
        }

        $calendar .= "</tr><tr>";

        // Pad the first week with empty cells if necessary
        for ($i = 0; $i < $dayOfWeek; $i++) {
            $calendar .= "<td class='empty'></td>";
        }

        // Create the rest of the calendar
        for ($currentDay = 1; $currentDay <= $numberDays; $currentDay++) {
            $class = 'day';
            if (isset($appointments[$currentDay])) {
                $class .= ' available';
            }
            $calendar .= "<td class='$class'>$currentDay</td>";

            // Increment the day of week counter
            $dayOfWeek++;

            // If it's a new week, start a new row
            if ($dayOfWeek == 7 && $currentDay != $numberDays) {
                $calendar .= "</tr><tr>";
                $dayOfWeek = 0;
            }
        }

        // Complete the row of the last week in month, if necessary
        while ($dayOfWeek > 0 && $dayOfWeek < 7) {
            $calendar .= "<td class='empty'></td>";
            $dayOfWeek++;
        }

        $calendar .= "</tr></table>";

        return [
            'month' => $month,
            'year' => $year,
            'calendar' => $calendar,
        ];
    }

    public function edit($id)
    {
        $professional = Professional::find($id);

        if ($professional) {
            return view('professionals.edit', ['professional' => $professional]);
        } else {
            return redirect()->back()->with('error', 'Professional not found');
        }
    }

    public function profile()
    {
        // Get the currently authenticated professional's ID
        $id = auth()->guard('professional')->user()->id;

        $professional = Professional::find($id);

        // Check if professional exists
        if (!$professional) {
            return redirect()->back()->with('error', 'Professional not found');
        }

        return view('professional_profile', compact('professional'));
    }
    public function editProfile()
    {
        // Get the currently authenticated professional's ID
        $id = auth()->guard('professional')->id();

        $professional = Professional::find($id);

        if ($professional) {
            return view('professional_edit', ['professional' => $professional]);
        } else {
            return redirect()->back()->with('error', 'Professional not found');
        }
    }
    public function updateProfile(Request $request)
    {
        Log::info('Updating profile for professional: ' . auth('professional')->id());
        Log::info('Received data: ', $request->all());

        $professional = auth('professional')->user();

        if ($professional) {
            $professional->proPhone = $request->input('proPhone');
            $professional->specialization = $request->input('specialization');
            $professional->city = $request->input('city');
            $professional->location = $request->input('location');
            $professional->address = $request->input('address');
            $professional->description = $request->input('description');
            $professional->save();

            return redirect()->back()->with('success', 'Profile updated successfully');
        } else {
            return redirect()->back()->with('error', 'Profile not found');
        }
    }
    public function show($id)
    {
        $professional = Professional::find($id);

        // Check if professional exists
        if (!$professional) {
            return response()->json(['error' => 'Professional not found'], 404);
        }

        return view('search_results_profiles', compact('professional'))->render();
    }
}
