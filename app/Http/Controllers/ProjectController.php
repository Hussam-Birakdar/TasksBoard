<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

	public function __construct()
	{
	}

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
		$projects = Auth()->user()->accessibleProjects();

		return view('projects.index',compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

		return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
		$attributes = request()->validate([
			'title' => 'required|min:3',
			'description' => 'required|min:3',
			'notes' => 'min:3'
		]);

		$attributes['owner_id'] = Auth()->id();
//		Auth()->user()->projects()->create($attributes);

		$project = Project::create($attributes);

		return redirect($project->path())->with('status','Project Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        //
		$this->authorize('view',$project);
//		if(Auth()->user()->isNot($project->owner)){
//			abort(403);
//		}
		return view('projects.view',compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
		return view('projects.edit',compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        //
		$this->authorize('update',$project);


		$attributes = request()->validate([
			'title' => 'sometimes|required',
			'description' => 'sometimes|required',
			'notes' => 'nullable'
		]);
		$project->update($attributes);

		return redirect($project->path());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        //
		$this->authorize('manage',$project);

		$project->delete();
		return redirect('/projects');
    }
}
