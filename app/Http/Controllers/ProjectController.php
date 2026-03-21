<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
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
        $projects = Project::orderBy('id', 'desc')->paginate(20);
        return view('main', ['projects' => $projects]);
    }
    public function create()
    {
        return view('project_settings');
    }

    public function store(ProjectRequest $request)
    {
       $project = Project::firstOrCreate(
           ['base_url' => $request->base_url],
           $request->validated()
       );

        if (!$project->wasRecentlyCreated) {
            // Элемент уже существует
            return redirect()->back()->with('error', 'Проект с таким base_url уже существует');
        } else {
            // Элемент был создан
            return redirect()->route('projects.index')->with('success', 'Проект успешно создан');
        }

    }

    public function update(ProjectRequest $request, $id)
    {
        $project = Project::find($id);
        $project->update($request->validated());
        return redirect()->route('projects.index')->with('success', 'Проект успешно обновлен');
    }

    public function show($id)
    {
        $project = Project::find($id);
        return view('project_settings', ['project' => $project]);
    }

    public function destroy($id)
    {
        $project = Project::find($id);
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Проект успешно удален');
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
