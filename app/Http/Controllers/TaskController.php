<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use App\Repositories\TaskRepository;

class TaskController extends Controller
{
    protected $tasks;

    public function __construct(TaskRepository $tasks)
    {
        $this->middleware('auth');

        $this->tasks = $tasks;
    }

    /**
     * Display a list of all of the user's task.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $tasks = $request->user()->tasks()->orderBy('created_at', 'desc')->get();

        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
      'name' => 'required|max:225',
    ]);

        $request->user()->tasks()->create([
      'name' => $request->name,

    ]);
    }

    public function destroy(Request $request, Task $task)
    {
        $this->authorize('destroy', $task);
        $task->delete();

        return response()->json($task);
    }

    public function edit(Task $task)
    {
        return response()->json($task);
    }

    public function update(Request $request, Task $task)
    {
        $task->update($request->all());

        return response()->json($task);
    }
}
