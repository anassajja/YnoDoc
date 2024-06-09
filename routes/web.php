<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TherapistController;
use App\Http\Controllers\PackController;
use App\Http\Controllers\PsychotherapyController;
use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Admin routes
Route::get('/admin/register', [AdminController::class, 'showRegistrationForm'])->name('admin.register');
Route::post('/admin/register', [AdminController::class, 'register']);
Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login']);
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin', 'AdminController@index');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/professionals/{id}/confirm', [AdminController::class, 'confirmUser'])->name('admin.confirmUser');
    Route::get('/admin/professionals/{id}/unconfirm', [AdminController::class, 'unconfirmUser'])->name('admin.unconfirmUser');
    Route::post('admin/patient/{id}/confirm', [AdminController::class, 'confirmPatient'])->name('admin.confirmPatient');
    Route::post('admin/patient/{id}/unconfirm', [AdminController::class, 'unconfirmPatient'])->name('admin.unconfirmPatient');
    Route::get('/admin/professionals/{id}/edit', [AdminController::class, 'editProfessional'])->name('admin.editProfessional');
    Route::get('/admin/patients/{id}/edit', [AdminController::class, 'editPatient'])->name('admin.editPatient');
    Route::put('/admin/patients/{id}', [AdminController::class, 'updatePatient'])->name('admin.updatePatient');
    Route::put('/admin/professionals/{id}', [AdminController::class, 'updateProfessional'])->name('admin.updateProfessional');
    Route::get('/admin/patients', [AdminController::class, 'showPatients'])->name('admin.patients');
    Route::get('/admin/blockPatient/{id}', [AdminController::class, 'blockPatient'])->name('admin.blockPatient');
    Route::get('/admin/blockProfessional/{id}', [AdminController::class, 'blockProfessional'])->name('admin.blockProfessional');
    Route::get('/admin/surveys', [AdminController::class, 'displaySurveys'])->name('admin.surveys');
    Route::post('/surveys', [SurveyController::class, 'store'])->name('surveys.store');
    Route::get('/surveys/create', [SurveyController::class, 'create'])->name('surveys.create');
    Route::delete('/admin/surveys/{id}', [AdminController::class, 'destroySurvey'])->name('admin.surveys.destroy');
    Route::post('/admin/surveys/{id}/archive', [AdminController::class, 'archiveSurvey'])->name('admin.surveys.archive');
    Route::get('/admin/therapists/create', [AdminController::class, 'createTherapist'])->name('admin.createTherapist');
    Route::post('/therapists', [AdminController::class, 'storeTherapist'])->name('storeTherapist');
    Route::delete('/admin/therapists/{id}', [AdminController::class, 'deleteTherapist'])->name('admin.deleteTherapist');
    Route::get('/admin/packs/create', [PackController::class, 'create'])->name('packs.create');
    Route::post('/admin/packs', [PackController::class, 'store'])->name('packs.store');
    Route::delete('/admin/packs/{id}', [AdminController::class, 'deletePack'])->name('packs.delete');
});


// Patient routes
Route::get('/patient/register', [PatientController::class, 'showRegistrationForm'])->name('patient.register');
Route::post('/patient/register', [PatientController::class, 'register']);
Route::get('/patient/login', [PatientController::class, 'showLoginForm'])->name('patient.login');
Route::post('/patient/login', [PatientController::class, 'login']);
Route::middleware('auth:patient')->group(function () {
    Route::get('/patient/search', [PatientController::class, 'search'])->name('patient.search');
    Route::get('/professionals/{id}', [ProfessionalController::class, 'show'])->name('professional.show');
    Route::post('/patient/bookAppointment', [PatientController::class, 'bookAppointment'])->name('bookAppointment');
    Route::resource('psychotherapy', PsychotherapyController::class);
    Route::get('/patient', [PatientController::class, 'index']);
    Route::get('/patient/dashboard', [PatientController::class, 'dashboard'])->name('patient.dashboard');
    Route::get('/patient/surveys', [PatientController::class, 'surveys']);
    Route::get('/surveys/{id}/answer', [SurveyController::class, 'answer']);
    Route::post('/surveys/{id}/submit', [SurveyController::class, 'submit']);
    Route::get('/professionals/{id}', [ProfessionalController::class, 'show'])->name('professional.show');
    Route::get('/patient/profile', [PatientController::class, 'profile'])->name('patient.profile');
    Route::get('/patient/edit', [PatientController::class, 'edit'])->name('patient.edit');
    Route::put('/patient/update', [PatientController::class, 'update'])->name('patient.update');
    Route::get('/choose-therapist', [PatientController::class, 'therapist']);
});


// Professional routes
Route::get('/professional/register', [ProfessionalController::class, 'showRegistrationForm'])->name('professional.register');
Route::post('/professional/register', [ProfessionalController::class, 'register']);
Route::get('/professional/login', [ProfessionalController::class, 'showLoginForm'])->name('professional.login');
Route::post('/professional/login', [ProfessionalController::class, 'login']);
Route::get('/professional/profile', [ProfessionalController::class, 'profile'])->name('professional.profile');
Route::middleware('auth:professional')->group(function () {
    Route::get('/professional', 'ProfessionalController@index');
    Route::get('/professional/dashboard', [ProfessionalController::class, 'dashboard'])->name('professional.dashboard');
    Route::get('/professional/{id}/edit', [ProfessionalController::class, 'editProfile'])->name('professional.edit');
    Route::get('/professional/profile', [ProfessionalController::class, 'profile'])->name('professional.profile');
    Route::put('/professional/updateProfile', [ProfessionalController::class, 'updateProfile'])->name('professional.updateProfile');
});




Route::post('/appointment', [AppointmentController::class, 'store'])->name('appointment.store');
Route::delete('/appointment/{appointment}', [AppointmentController::class, 'destroy'])->name('appointment.destroy');

Route::post('/schedule/{schedule}/setAppointmentTime', 'ScheduleController@setAppointmentTime')->name('schedule.setAppointmentTime');
Route::post('/schedule/{schedule}/accept', [ScheduleController::class, 'accept'])->name('schedule.accept');
Route::post('/schedule/{schedule}/decline', [ScheduleController::class, 'decline'])->name('schedule.decline');
Route::delete('/schedule/{id}', [ScheduleController::class, 'destroy'])->name('schedule.destroy');


Route::get('/calendar', [PatientController::class, 'generateCalendar']);


// Routes for surveys
Route::get('/surveys/{survey}', [SurveyController::class, 'show'])->name('surveys.show');
Route::get('/surveys/{survey}/edit', [SurveyController::class, 'edit'])->name('surveys.edit');
Route::put('/surveys/{survey}', [SurveyController::class, 'update'])->name('surveys.update');


Route::get('/', function () {
    return view('home');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});

Route::post('/logout', function () {
    if (Auth::guard('patient')->check()) {
        Auth::guard('patient')->logout();
    } else if (Auth::guard('professional')->check()) {
        Auth::guard('professional')->logout();
    } else if (Auth::guard('admin')->check()) {
        Auth::guard('admin')->logout();
    }
    return redirect('/');
})->name('logout');
