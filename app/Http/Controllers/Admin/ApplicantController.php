<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;

class ApplicantController extends Controller
{
    public function index()
    {
        $applicants = Applicant::orderBy('id', 'DESC')->get();

        return view('admin.applicants.index', compact('applicants'));
    }

}
