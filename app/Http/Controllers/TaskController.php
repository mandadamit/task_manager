<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\ActivityLog;
use App\Models\Project;
use App\Http\Requests\TaskRequest;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Task::with('project');
            if ($request->has('filter') && $request->filter != null) {
                $filter = $request->filter;
                $query->where(function ($q) use ($filter) {
                    $q->where('title', 'like', '%' . $filter . '%')
                    ->orWhere('due_date', 'like', '%' . $filter . '%')
                    ->orWhere('status', 'like', '%' . $filter . '%')
                    ->orWhereHas('project', function ($q2) use ($filter) {
                        $q2->where('name', 'like', '%' . $filter . '%');
                    });
                });

            }
            return response()->json($query->get());
        }
        $projects = Project::get();
        \Log::info('Filter:', ['filter' => $request->filter]);
        return view('tasks.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $task = Task::create($request->all());

        $this->logActivity('created', $task);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $task = Task::findOrFail($id);
        $task->update($request->all());

        return redirect()->route('projects.index')->with('success', 'Project updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        $this->logActivity('deleted', $task);

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }

    protected function logActivity($action, $model)
    {
        ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => "{$action} project: {$model->name}",
            'model_type' => get_class($model),
            'model_id'   => $model->id,
        ]);
    }

    public function status(Task $task)
    {
        $task = Task::findOrFail($task->id);
        $task->status = $task->status === 'completed' ? 'pending' : 'completed';
        $task->save();

        return response()->json([
            'success' => true,
            'status' => $task->status
        ]);
    }
}
