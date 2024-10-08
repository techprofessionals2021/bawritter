<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Models\Service;
use App\Models\Content;
use App\Services\CalculatorService;
use App\Services\SeoService;
use App\Mail\CustomerQuery;


class HomeController extends Controller
{
    private $seoService;

    function __construct(SeoService $seoService)
    {
        $this->seoService = $seoService;
    }

    public function index()
    {

        //    if(env('ENABLE_APP_SETUP_CONFIG') != TRUE)
        //    {
        //       return redirect()->route('installer_page');
        //    }
        $this->seoService->load('home');


        $data['services'] = [];

        $services = Service::all();

        if ($services->count() > 0) {
            if ($services->count() > 4) {
                $data['services'] = array_chunk($services->toArray(), ceil($services->count() / 4));
            } else {
                $data['services'] = [$services->toArray()];
            }
        }

        return view('website.index', compact('data'));
    }

    function pricing(CalculatorService $calculator)
    {
        $this->seoService->load('pricing');

        return view('website.pricing')->with(['data' => $calculator->priceList()]);
    }

    function content(Request $request)
    {
        $this->seoService->load($request->route()->getName());

        $slug = $request->segment(count($request->segments()));

        $content = Content::where('slug', $slug)->get();

        if ($content->count() > 0) {
            $content = $content->first();
        } else {
            abort(404);
        }

        return view('website.content')->with('content', $content);
    }

    function contact()
    {
        $this->seoService->load('contact');

        return view('website.contact');
    }

    function handle_email_query(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required'
        ]);

        if ($validator->fails()) {

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Mail::to(settings('company_email'))->send(new CustomerQuery($request->all()));

        $request->session()->flash('alert-class', 'alert-success');
        $request->session()->flash('message', 'Thank you for your query. We will get back to you as soon as possible');

        return redirect()->back();
    }
}
