<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class TaskController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Task::class, 'task');
    }
    /**
     * Retrieves a list of tasks with filtering, sorting, and pagination options.
     *
     * @param Request $request The HTTP request object.
     * @return TaskCollection A collection of tasks.
     */
    public function index(Request $request)
    {
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters('is_done')
            ->defaultSort('-created_at')
            ->allowedSorts(['created_at', 'title', 'is_done'])
            ->paginate();
        return new TaskCollection($tasks);
    }

    /**
     * Retrieves a single task by its ID.
     *
     * @param Request $request The HTTP request object.
     * @param Task $task The task to retrieve.
     * @return TaskResource The task resource.
     */
    public function show(Request $request, Task $task)
    {
        return new TaskResource($task);
    }

    /**
     * Creates a new task using the validated data from the request.
     *
     * @param StoreTaskRequest $request The HTTP request object.
     * @return TaskResource The created task resource.
     */
    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();
        $task = Auth::user()->tasks()->create($validated);

        return new TaskResource($task);
    }

    /**
     * Updates an existing task using the validated data from the request.
     *
     * @param UpdateTaskRequest $request The HTTP request object.
     * @param Task $task The task to update.
     * @return TaskResource The updated task resource.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $validated = $request->validated();
        $task->update($validated);

        return new TaskResource($task);
    }

    /**
     * Deletes a task.
     *
     * @param Request $request The HTTP request object.
     * @param Task $task The task to delete.
     * @return \Illuminate\Http\Response The HTTP response.
     */
    public function destroy(Request $request, Task $task)
    {
        $task->delete();

        return response()->noContent();
    }
}
