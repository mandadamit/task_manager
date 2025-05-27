<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProjectRequest;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Project::withCount('tasks')
                        ->withCount(['tasks as completed_tasks_count' => function ($q) {
                            $q->where('status', 'completed');
                        }]);
            if ($request->has('filter') && $request->filter != null) {
                $query->where('name', 'like', '%' . $request->filter . '%');
            }
            return response()->json($query->get());
        }
        \Log::info('Filter:', ['filter' => $request->filter]);
        return view('projects.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request)
    {
        $project = Project::create($request->all());

        $this->logActivity('created', $project);

        return redirect()->route('projects.index')->with('success', 'Project created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequest $request, string $id)
    {
        $project = Project::findOrFail($id); // make sure you fetch the project
        $project->update($request->all());

        return redirect()->route('projects.index')->with('success', 'Project updated!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);
        $this->logActivity('deleted', $project);
        $project->delete();

        return response()->json(['message' => 'Project deleted successfully!']);
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
}
