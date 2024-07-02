<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Auth;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;
    public function __construct()
    {
        $this->authorizeResource(Task::class, 'task');
    }
    //show all records
    public function index()
    {
        $task = QueryBuilder::for(Task::class)
            ->allowedFilters('is_done')
            ->defaultSort('created_at')
            ->allowedSorts(['title','is_done','created_at'])
            ->paginate();
        return new TaskCollection($task);
    }
    //show single record
    public function show(Request $request, Task $task)
    {
        return new TaskResource($task);
    }
    //store new record
    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();
        $task = Auth::user()->tasks()->create($validated);
        return new TaskResource($task);
    }
    //update record
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $validated = $request->validated();
        $task->update($validated);
        return new TaskResource($task);
    }
    //delete record
    public function destroy(Request $request, Task $task)
    {
        $task->delete();
        return response()->noContent();
    }

}//end class
