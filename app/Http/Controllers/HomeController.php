<?php

namespace App\Http\Controllers;

use App\Services\Parser\Parsers\PageParser;
use App\Services\Parser\ParserService;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //$this->startParse();
        return view('welcome');
    }

}
