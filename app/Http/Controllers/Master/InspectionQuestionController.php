<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InspectionQuestionController extends Controller
{
    public function index()
    {
        return view('master.inspection_question.'.__FUNCTION__, [
            'title' => 'Inspection Question'
        ]);
    }
}
