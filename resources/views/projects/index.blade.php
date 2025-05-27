@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-sm-12 col-xl-12">
        <div class="bg-light rounded h-100 p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="col-sm-4 col-xl-4">
                    <input type="text" id="project-filter" class="form-control mb-3" placeholder="Search by project name...">
                </div>
                <div class="col-sm-4 col-xl-4 text-end">
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#projectModal" onclick="openCreateModal()">
                    + Create Project
                    </button>
                </div>
            </div>
            <!-- Project Table -->
            <table class="table table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Tasks</th>
                        <th>Completed</th>
                        <th>Progress</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="project-container"></tbody>
            </table>
        </div>
    </div>
</div>


<!-- Modal for Add/Edit -->
<div class="modal fade" id="projectModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="projectForm" method="POST" action="{{ route('projects.update', $project->id ?? 0) }}">
            @csrf
            <input type="hidden" name="id" id="project-id">
            <input type="hidden" name="_method" id="form-method" value="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Create Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Project Name</label>
                        <input type="text" class="form-control" name="name" id="project-name" required>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea class="form-control" name="description" id="project-description"></textarea>
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
    <script src="{{ asset('/assets/js/project.js') }}"></script>
@endsection