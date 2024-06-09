<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Psychotherapy;
use App\Models\Therapist;
use App\Models\Pack;
use App\Models\Patient;

class PsychotherapyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store']);
    }

    public function create()
    {
        $patientId = auth()->id();
        $patient = Patient::find($patientId); // Assuming you have a Patient model

        if ($patient->confirmed == 0) {
            return view('account_not_confirmed'); // You need to create this view
        }

        $therapists = Therapist::all();
        $packs = Pack::all();
        return view('choose_therapist', compact('therapists', 'packs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required',
            'therapist_id' => 'required',
            'pack_id' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
        ]);

        $psychotherapy = new Psychotherapy($request->all());
        $psychotherapy->save();

        return back()->with('success', 'Psychotherapy session created successfully!');
    }
}
