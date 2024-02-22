<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\models\Applicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class JobApplicantController extends Controller
{

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:applicants',
            'country_id' => 'required',
            'referral_source_id' => 'required',
            'resume' => 'required|mimes:pdf|max:5000',
        ], [
            'email.unique'=> 'Looks like we already have your application'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $request['applicant_status_id'] = 1;

        $request['number'] = mt_rand(100000, 999999);
        $request['attachment'] = $request->file('resume')->store('resumes');

          Applicant::create($request->all());

        $message = settings('writer_application_form_success_message');

        if (empty($message)) {
            $message = 'Thank you for submitting your application.';
        }
        Session::flash('message', $message);
        Session::flash('alert-class', 'alert-success');

        return apiResponseSuccess('',$message);

    }







}

