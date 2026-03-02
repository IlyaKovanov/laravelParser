<?php

namespace App\Http\Controllers;

use App\Services\Parser\Parsers\PageParser;
use App\Services\Parser\ParserService;


class ProjectController extends Controller
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
        return view('project_settings');
    }

    private function startParse()
    {
        $config['base_url'] = 'https://rudingli.ru/';
        $config['url'] = 'https://rudingli.ru/products/nozhnichnye-podemniki/jcpt1008ha/';
        $config['selectors'] = [
            'title' => '.product__title',
            'preview_text' => '.product__text',
            'detail_text' => '.tabs__content-inner',
            'images' => '.product__img',
            'attributes_name' => '.product__tabs-table .table__row-name',
            'attributes_values' => '.product__tabs-table .table__row-meaning',
        ];
        $config['selectors_type'] = [
            'title' => 'single',
            'preview_text' => 'single',
            'detail_text' => 'single',
            'images' => 'images',
            'attributes_name' => 'multiple',
            'attributes_values' => 'multiple'

        ];
        $config['type'] = 'single';

        $pageParser = new PageParser();
        $parserServices = new ParserService($pageParser);

        $result = $parserServices->parse($config);

        dd($result);
    }
}
