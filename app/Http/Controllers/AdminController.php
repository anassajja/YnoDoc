<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Professional;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Patient;
use App\Models\SurveyResponse;
use App\Models\Survey;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Response;
use App\Models\Therapist;
use App\Models\Pack;


class AdminController extends Controller
{
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('admin.login');
        }
    }

    public function __construct()
    {
        $this->middleware('auth:admin')->except(['showLoginForm', 'login', 'showRegistrationForm', 'register']);
    }

    public function showLoginForm()
    {
        return view('admin_login');
    }

    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to log the admin in
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            // If login successful, then redirect to their intended location
            return redirect()->intended(route('admin.dashboard'));
        }

        // If unsuccessful, then redirect back to the login with the form data
        return redirect()->back()->withInput($request->only('email', 'remember'));
    }

    public function showRegistrationForm()
    {
        return view('admin_register');
    }

    public function register(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:admins',
            'password' => 'required|min:6',
        ]);

        // Create the admin
        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Log the admin in
        Auth::guard('admin')->login($admin);

        // Redirect the admin to the dashboard
        return redirect()->intended(route('admin.dashboard'));
    }
    public function dashboard()
    {
        $patients = Patient::all();
        $professionals = Professional::all();
        $therapists = Therapist::all();
        $packs = Pack::all();
        $surveys = Survey::all();

        $totalUsers = $patients->count() + $professionals->count();
        $totalPatients = $patients->count();
        $totalProfessionals = $professionals->count();
        $totalTherapists = $therapists->count();
        $totalPacks = $packs->count();
        $totalSurveys = $surveys->count();

        return view('admin_dashboard', [
            'patients' => $patients,
            'professionals' => $professionals,
            'therapists' => $therapists,
            'packs' => $packs,
            'surveys' => $surveys,
            'totalUsers' => $totalUsers,
            'totalPatients' => $totalPatients,
            'totalProfessionals' => $totalProfessionals,
            'totalTherapists' => $totalTherapists,
            'totalPacks' => $totalPacks,
            'totalSurveys' => $totalSurveys
        ]);
    }

    public function confirmUser($id)
    {
        $professional = Professional::find($id);

        if ($professional) {
            $professional->confirmed = true;
            $professional->save();

            if ($professional->wasChanged()) {
                return redirect()->back()->with('success', 'Professional confirmed successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to confirm professional');
            }
        } else {
            return redirect()->back()->with('error', 'Professional not found');
        }
    }

    public function unconfirmUser($id)
    {
        $professional = Professional::find($id);
        $professional->confirmed = false;
        $professional->save();

        return redirect()->back();
    }
    public function confirmPatient($id)
    {
        $patient = Patient::find($id);
        $patient->confirmed = true;
        $patient->save();

        return redirect()->back();
    }

    public function unconfirmPatient($id)
    {
        $patient = Patient::find($id);
        $patient->confirmed = false;
        $patient->save();

        return redirect()->back();
    }
    public function editProfessional($id)
    {
        $professional = Professional::find($id);

        if ($professional) {
            return view('admin.editProfessional', ['professional' => $professional]);
        } else {
            return redirect()->back()->with('error', 'Professional not found');
        }
    }

    public function editPatient($id)
    {
        $patient = Patient::find($id);

        if ($patient) {
            return view('admin.editPatient', ['patient' => $patient]);
        } else {
            return redirect()->back()->with('error', 'Patient not found');
        }
    }

    public function updatePatient(Request $request, $id)
    {
        $patient = Patient::find($id);

        if ($patient) {
            $patient->name = $request->input('name');
            $patient->email = $request->input('email');
            $patient->city = $request->input('city');
            $patient->phone = $request->input('phone');
            $patient->age = $request->input('age');
            $patient->description = $request->input('description');

            $patient->save();

            return redirect()->route('admin.dashboard')->with('success', 'Patient updated successfully');
        } else {
            return redirect()->back()->with('error', 'Patient not found');
        }
    }
    public function deleteTherapist($id)
    {
        $therapist = Therapist::findOrFail($id);
        $therapist->delete();

        return redirect()->route('admin.dashboard')->with('status', 'Therapist deleted successfully');
    }
    public function deletePack($id)
    {
        $pack = Pack::findOrFail($id);
        $pack->delete();

        return redirect()->route('admin.dashboard')->with('status', 'Pack deleted successfully');
    }

    public function deleteSurvey($id)
    {
        $survey = Survey::findOrFail($id);
        $survey->delete();

        return redirect()->route('admin.dashboard')->with('status', 'Survey deleted successfully');
    }

    public function updateProfessional(Request $request, $id)
    {
        $professional = Professional::find($id);

        if ($professional) {
            $professional->titre = $request->input('titre');
            $professional->name = $request->input('name');
            $professional->persoPhone = $request->input('persoPhone');
            $professional->email = $request->input('email');
            $professional->specialization = $request->input('specialization');
            $professional->city = $request->input('city');
            $professional->address = $request->input('address');
            $professional->proPhone = $request->input('proPhone');
            $professional->location = $request->input('location');
            $professional->description = $request->input('description');

            $professional->save();

            return redirect()->route('admin.dashboard')->with('success', 'Professional updated successfully');
        } else {
            return redirect()->back()->with('error', 'Professional not found');
        }
    }
    public function showPatients()
    {
        $patients = Patient::all();

        return view('admin.patients', ['patients' => $patients]);
    }

    public function deleteUser(User $user)
    {
        $user->delete();

        return redirect()->back();
    }
    public function displaySurveys()
    {
        $surveys = Survey::all();
        $responses = SurveyResponse::all(); // Fetch all survey responses

        return view('display_surveys', compact('surveys', 'responses')); // Pass both surveys and responses to the view
    }
    public function destroySurvey($id)
    {
        // Find the survey
        $survey = Survey::findOrFail($id);

        // Delete associated responses
        $survey->responses()->delete();

        // Delete the survey
        $survey->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Survey deleted successfully');
    }
    public function archiveSurvey(Request $request)
    {
        $responseId = $request->input('response_id');
        $response = SurveyResponse::find($responseId);

        // Now you can access the data you want to archive
        $surveyTitle = $response->survey->title;
        $patientId = $response->patient->id;
        $patientName = $response->patient->name;
        $questionsAndAnswers = [];
        foreach ($response->survey->questions as $index => $question) {
            $questionsAndAnswers[] = [
                'question' => $question['text'],
                'answer' => json_decode($response->answers, true)[$index],
            ];
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Write the data to the spreadsheet
        $sheet->setCellValue('A1', 'Survey Title');
        $sheet->setCellValue('B1', $surveyTitle);
        $sheet->setCellValue('A2', 'Patient ID');
        $sheet->setCellValue('B2', $patientId);
        $sheet->setCellValue('A3', 'Patient Name');
        $sheet->setCellValue('B3', $patientName);

        $row = 4;
        foreach ($questionsAndAnswers as $qa) {
            $sheet->setCellValue("A{$row}", $qa['question']);
            $sheet->setCellValue("B{$row}", $qa['answer']);
            $row++;
        }

        // Ensure the directory exists
        $path = storage_path("excel/exports");
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save("{$path}/survey_{$responseId}.xlsx");

        // Delete the survey response from the database
        $response->delete();

        // You might want to return a response to indicate that the operation was successful
        return redirect()->back()->with('message', 'Survey response archived and deleted successfully');
    }
    public function createTherapist()
    {
        return view('therapist_create');
    }
    public function storeTherapist(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:therapists',
            'phone_number' => 'required|unique:therapists',
            'city' => 'required',
            'address' => 'required',
            'specialization' => 'required',
        ]);

        $therapist = new Therapist([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'phone_number' => $request->get('phone_number'),
            'city' => $request->get('city'),
            'address' => $request->get('address'),
            'specialization' => $request->get('specialization'),
            'description' => $request->get('description'),
        ]);

        $therapist->save();

        return back()->with('success', 'Therapist created successfully!');
    }
    public function blockPatient(Request $request, $id)
    {
        $patient = Patient::find($id);
        if ($patient->psychotherapies) {
            foreach ($patient->psychotherapies as $psychotherapy) {
                $psychotherapy->delete(); // or update the record as necessary
            }
        }
        foreach ($patient->surveyResponses as $surveyResponse) {
            $surveyResponse->delete(); // or update the record as necessary
        }

        // Now you can access the data you want to archive
        $patientId = $patient->id;
        $patientName = $patient->name;
        // Add any other patient data you want to archive

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Write the data to the spreadsheet
        $sheet->setCellValue('A1', 'Patient ID');
        $sheet->setCellValue('B1', $patientId);
        $sheet->setCellValue('A2', 'Patient Name');
        $sheet->setCellValue('B2', $patientName);
        // Write any other patient data to the spreadsheet

        // Ensure the directory exists
        $path = storage_path("excel/exports");
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save("{$path}/patient_{$patientId}.xlsx");

        // Delete the patient from the database
        $patient->delete();

        // You might want to return a response to indicate that the operation was successful
        return redirect()->back()->with('message', 'Patient data archived and patient deleted successfully');
    }
    public function blockProfessional(Request $request, $id)
    {
        $professional = Professional::find($id);

        // Now you can access the data you want to archive
        $professionalId = $professional->id;
        $professionalName = $professional->name;
        // Add any other professional data you want to archive

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Write the data to the spreadsheet
        $sheet->setCellValue('A1', 'Professional ID');
        $sheet->setCellValue('B1', $professionalId);
        $sheet->setCellValue('A2', 'Professional Name');
        $sheet->setCellValue('B2', $professionalName);
        // Write any other professional data to the spreadsheet

        // Ensure the directory exists
        $path = storage_path("excel/exports");
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save("{$path}/professional_{$professionalId}.xlsx");

        // Delete the professional from the database
        $professional->delete();

        // You might want to return a response to indicate that the operation was successful
        return redirect()->back()->with('message', 'Professional data archived and professional deleted successfully');
    }
}
