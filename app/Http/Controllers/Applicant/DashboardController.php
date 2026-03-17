<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Applicant;

class DashboardController extends Controller
{
    public function index()
    {
        $applicant = Applicant::where('user_id', Auth::id())->first();

        return view('applicant.dashboard', compact('applicant'));
    }
}
