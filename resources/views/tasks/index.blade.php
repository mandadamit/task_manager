@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-sm-12 col-xl-12">
        <div class="bg-light rounded h-100 p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="col-sm-4 col-xl-4 px-1">
                    <input type="text" id="task-filter" class="form-control mb-3" placeholder="Search by All kays...">
                </div>
                <div class="col-sm-4 col-xl-4">
                    <select class="form-select mb-3" id="task-status-filter">
                        <option value="" selected>All Status</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="col-sm-4 col-xl-4 text-end">
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#taskModal" onclick="openCreateModal()">
                    + Create Task
                    </button>
                </div>
            </div>
            <!-- Task Table -->
            <table class="table table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Project Name</th>
                        <th>Task Name</th>
                        <th>Milestone Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="task-container"></tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal for Add/Edit -->
<div class="modal fade" id="taskModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="taskForm" method="POST" action="{{ route('tasks.update', $task->id ?? 0) }}">
            @csrf
            <input type="hidden" name="id" id="task-id">
            <input type="hidden" name="_method" id="form-method" value="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Create Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Project Name</label>
                        <select class="form-select" name="project_id" id="task-project-id" required>
                            <option value="" selected disabled>Select Project</option>
                            @forelse ( $projects as $project )
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @empty
                                <option value="">No projects available</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Task Name</label>
                        <input type="text" class="form-control" name="title" id="task-name" required>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea class="form-control" name="description" id="task-description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Milestone Date</label>
                        <input type="text" class="form-control" name="due_date" id="task-milestone" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('js')
    <script src="{{ asset('/assets/js/task.js') }}"></script>
@endsection