<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Patient;
use App\Models\Professional;
use App\Models\Schedule;
use App\Models\Appointment;
use Carbon\Carbon;
use App\Models\Survey;
use App\Models\Therapist;
use App\Models\Pack;


class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:patient')->except(['showRegistrationForm', 'register', 'showLoginForm', 'login']);
    }

    public function dashboard()
    {
        $patient = auth()->user(); // Get the currently authenticated user
        $schedules = Schedule::with('professional')->where('patient_id', auth()->id())->get();

        // Add these lines
        $specialities = Professional::select('specialization')->distinct()->get();
        $cities = Professional::select('city')->distinct()->get();

        // Include the specialities and cities in the data you're passing to the view
        return view('patient_dashboard', ['schedules' => $schedules, 'patient' => $patient, 'specialities' => $specialities, 'cities' => $cities]);
    }

    public function getCalendar(Request $request)
    {
        $professionalId = $request->input('professional_id');
        $month = $request->input('month');
        $year = $request->input('year');

        $calendar = $this->generateCalendar($professionalId, $month, $year);

        return response()->json($calendar);
    }

    public function showRegistrationForm()
    {
        return view('patient_register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:patients',
            'city' => 'required',
            'phone' => 'required',
            'age' => 'required|integer',
            'password' => 'required|min:8',
            'description' => 'nullable',
        ]);

        $patient = new Patient([
            'name' => $request->name,
            'email' => $request->email,
            'city' => $request->city,
            'phone' => $request->phone,
            'age' => $request->age,
            'password' => Hash::make($request->password),
            'description' => $request->description,
        ]);

        $patient->save();

        Auth::guard('patient')->login($patient);

        return redirect()->intended(route('patient.dashboard'));
    }
    public function profile()
    {
        $patient = Auth::user(); // Get the currently authenticated user
        return view('patient_profile', ['patient' => $patient]);
    }

    public function showLoginForm()
    {
        return view('patient_login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('patient')->attempt($credentials)) {
            // Authentication passed...
            return redirect()->intended(route('patient.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout()
    {
        Auth::guard('patient')->logout();
        return redirect('/home');
    }


    public function editUser($id)
    {
        $patient = Patient::find($id);

        if ($patient) {
            return view('admin.edit', ['patient' => $patient]);
        } else {
            return redirect()->back()->with('error', 'Patient not found');
        }
    }
    public function search(Request $request)
    {
        $patientId = auth()->id();
        $patient = Patient::find($patientId); // Assuming you have a Patient model

        if ($patient->confirmed == 0) {
            return view('account_not_confirmed'); // You need to create this view
        }

        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));
        $professionals = Professional::where('location', $request->location)
            ->where('specialization', $request->specialization)
            ->where('city', $request->city)
            ->with('appointments') // Eager load the appointments
            ->get();

        // Generate a calendar for each professional
        foreach ($professionals as $professional) {
            // Fetch the schedules for each professional
            $schedules = Schedule::where('patient_id', $patientId)
                ->where('professional_id', $professional->id)
                ->get();

            $calendar1 = $this->generateCalendar($month, $year, $professional->id, $schedules);
            $calendar1['id'] = $professional->id . '-' . $month . '-' . $year;
            $professional->calendar1 = $calendar1;

            $calendar2 = $this->generateCalendar($month + 1, $year, $professional->id, $schedules);
            $calendar2['id'] = $professional->id . '-' . ($month + 1) . '-' . $year;
            $professional->calendar2 = $calendar2;

            $calendar3 = $this->generateCalendar($month + 2, $year, $professional->id, $schedules);
            $calendar3['id'] = $professional->id . '-' . ($month + 2) . '-' . $year;
            $professional->calendar3 = $calendar3;
        }

        return view('search_results', [
            'month' => $month,
            'year' => $year,
            'professionals' => $professionals,
        ]);
    }
    public function edit()
    {
        // Replace with your own logic
        $patient = Auth::user();
        return view('patient_edit', ['patient' => $patient]);
    }
    public function update(Request $request)
    {
        $patient = Auth::user();
        $patient->update($request->only(['name', 'city', 'phone', 'description']));
        return redirect()->route('patient.profile');
    }
    public function bookAppointment(Request $request)
    {
        $request->validate([
            'professional_id' => 'required|integer',
            'date' => 'required|date',
            'location' => 'required|string',
            'city' => 'required|string',
            'patient_id' => 'required|integer',
        ]);

        $professional_id = $request->input('professional_id');
        $patient_id = Auth::user()->id;
        $date = $request->input('date');

        // Check if a schedule already exists for the given patient_id, professional_id and date
        $existingSchedule = Schedule::where('patient_id', $patient_id)
            ->where('professional_id', $professional_id)
            ->where('date', $date)
            ->first();

        if ($existingSchedule) {
            // A schedule with the same patient_id, professional_id and date already exists.
            // Return an error message.
            return response()->json([
                'error' => 'An appointment already exists with the same professional on the given date.'
            ], 400);
        } else {
            // No existing schedule found. Proceed with creating the new schedule.
            $schedule = new Schedule;
            $schedule->professional_id = $professional_id;
            $schedule->patient_id = $patient_id;
            $schedule->date = $date;
            $schedule->location = $request->input('location');
            $schedule->city = $request->input('city');
            $schedule->status = 'pending'; // default value
            $schedule->appointment_time = '00:00:00'; // default value
            $schedule->save();

            // Return a JSON response with the status of the appointment
            return response()->json([
                'status' => $schedule->status,
                'professional_id' => $schedule->professional_id,
                'date' => $schedule->date,
                'location' => $schedule->location,
                'city' => $schedule->city
            ]);
        }
    }

    public function generateCalendar($month, $year, $professionalId)
    {
        // Fetch the currently logged in user
        $patient = Auth::user();
        $patientId = $patient->id;

        // Fetch the location and city of the professional
        $location = request('location');
        $city = request('city');

        $appointments = Appointment::where('professional_id', $professionalId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->groupBy(function ($appointment) {
                return Carbon::parse($appointment->date)->day;
            })
            ->map(function ($appointmentsOnDay) {
                return $appointmentsOnDay->groupBy('status');
            });

        $schedules = Schedule::where('professional_id', $professionalId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->groupBy(function ($schedule) {
                return Carbon::parse($schedule->date)->day;
            })
            ->map(function ($schedulesOnDay) {
                return $schedulesOnDay->groupBy('status');
            });

        $daysOfWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
        $numberDays = date('t', $firstDayOfMonth);
        $dateComponents = getdate($firstDayOfMonth);
        $dayOfWeek = $dateComponents['wday'] == 0 ? 6 : $dateComponents['wday'] - 1;
        $calendar = "<table><tr>";

        foreach ($daysOfWeek as $day) {
            $calendar .= "<th class='header'>$day</th>";
        }

        $calendar .= "</tr><tr>";

        for ($i = 0; $i < $dayOfWeek; $i++) {
            $calendar .= "<td class='empty'></td>";
        }

        for ($currentDay = 1; $currentDay <= $numberDays; $currentDay++) {
            $class = 'day';
            // Check if an appointment is available alone
            if (isset($appointments[$currentDay]['available']) && !isset($schedules[$currentDay])) {
                $class .= ' status-available';
            } elseif (isset($appointments[$currentDay]) && isset($schedules[$currentDay])) {
                if (isset($appointments[$currentDay]['available']) && isset($schedules[$currentDay]['pending'])) {
                    $class .= ' status-orange';
                } elseif (isset($appointments[$currentDay]['available']) && isset($schedules[$currentDay]['accepted'])) {
                    $class .= ' status-yellow';
                } elseif (isset($appointments[$currentDay]['available']) && isset($schedules[$currentDay]['declined'])) {
                    $class .= ' status-red';
                }
            } elseif (isset($appointments[$currentDay])) {
                if (isset($appointments[$currentDay]['available'])) {
                    $class .= ' status-available';
                } elseif (isset($appointments[$currentDay]['pending'])) {
                    $class .= ' status-pending';
                } elseif (isset($appointments[$currentDay]['booked'])) {
                    $class .= ' status-booked';
                }
            } elseif (isset($schedules[$currentDay])) {
                if (isset($schedules[$currentDay]['available'])) {
                    $class .= ' status-available';
                } elseif (isset($schedules[$currentDay]['pending'])) {
                    $class .= ' status-pending';
                } elseif (isset($schedules[$currentDay]['booked'])) {
                    $class .= ' status-booked';
                }
            }

            $date = "$year-$month-$currentDay";
            $calendar .= "<td class='$class' data-date='$date' data-professional-id='$professionalId' data-location='$location' data-city='$city' data-patient-id='$patientId'>$currentDay</td>";

            // Increment the day of week counter
            $dayOfWeek++;

            // If it's a new week, start a new row
            if ($dayOfWeek == 7 && $currentDay != $numberDays) {
                $calendar .= "</tr><tr>";
                $dayOfWeek = 0;
            }
        }

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
    public function surveys()
    {
        $patientId = auth()->id();
        $patient = Patient::find($patientId); // Assuming you have a Patient model

        if ($patient->confirmed == 0) {
            return view('account_not_confirmed'); // You need to create this view
        }

        $surveys = Survey::all();
        return view('surveys_list', compact('surveys'));
    }

    public function therapist()
    {
        $patientId = auth()->id();
        $patient = Patient::find($patientId); // Assuming you have a Patient model

        if ($patient->confirmed == 0) {
            return view('account_not_confirmed'); // You need to create this view
        }

        $therapists = Therapist::all(); // Retrieve all therapists from the database
        $packs = Pack::all(); // Retrieve all packs from the database

        return view('choose_therapist', compact('therapists', 'packs')); // Pass the therapists and packs to the view
    }
}
