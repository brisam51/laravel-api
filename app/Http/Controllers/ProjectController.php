<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



class ProjectController extends Controller
{
    use AuthorizesRequests;
    public function __construct(){
        $this->authorizeResource(Project::class, 'project');
    }
    //show all projects
    public function index()
    {
        $project = QueryBuilder::for(Project::class)
            ->allowedIncludes('tasks')
            ->paginate();
        return new ProjectCollection($project);
    }
    //show singelprojects
    public function show(Request $request, Project $project)
    {
        return (new ProjectResource($project))
            ->load('tasks')
            ->load('members');
    }
    //store new project
    public function store(StoreProjectRequest $request, Project $project)
    {
        $validate = $request->validated();
        $project = Auth::user()->projects()->create($validate);
        return new ProjectResource($project);
    }
    //update current project
    public function update(UpdateProjectRequest $request, Project $project)
    {

        $validate = $request->validated();
        $project->update($validate);
        return new ProjectResource($project);
    }
    //delete slected project
    public function destroy(Request $request, Project $project)
    {
        $project->delete();
        return response()->noContent();
    }
}//end class
