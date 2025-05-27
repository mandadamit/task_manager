$(document).ready(fetchProjects);
// Open modal for create
function openCreateModal() {
    $('#modalTitle').text('Add Project');
    $('#project-form').attr('action', '/projects'); 
    $('#form-method').val('POST');
    $('#project-id').val('');
    $('#project-name').val('');
    $('#project-description').val('');
    $('#projectModal').modal('show');
}

// Open modal for edit
function openEditModal(project) {
    $('#modalTitle').text('Edit Project');
    $('#project-form').attr('action', '/projects/' + project.id); 
    $('#form-method').val('PUT');
    $('#project-id').val(project.id);
    $('#project-name').val(project.name);
    $('#project-description').val(project.description);
    $('#projectModal').modal('show');
}

// Submit form (Create or Update)
$('#projectForm').on('submit', function (e) {
    e.preventDefault();

    let id = $('#project-id').val();
    let formData = $(this).serialize(); 

    let url = id ? `/projects/${id}` : '/projects';
    let method = id ? 'POST' : 'POST'; 
    if (id) {
        formData += '&_method=PUT'; 
    }

    $.ajax({
        url: url,
        method: method,
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function () {
            $('#projectModal').modal('hide');
            $('.modal-backdrop').remove(); // removes the backdrop
            $('body').removeClass('modal-open'); // removes body scroll lock
            $('body').css('padding-right', '');
            $('#projectForm')[0].reset();
            fetchProjects();
            toastr.success("Project successfully saved!");
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                for (let field in errors) {
                    if (errors.hasOwnProperty(field)) {
                        toastr.error(errors[field][0]);
                    }
                }
            } else {
                toastr.error('Unexpected error occurred.');
                console.error(xhr.responseText);
            }
        }
    });
});

// Delete row 
function deleteRow(project) {
    if (confirm(`Are you sure you want to delete project "${project.name}"?`)) {
        $.ajax({
            url: `/projects/${project.id}`,
            method: 'POST', 
            data: {
                _method: 'DELETE',
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function () {
                fetchProjects(); 
                toastr.success("Project successfully deleted!");
            },
            error: function (err) {
                toastr.success("Something went wrong while deleting.");
                console.error(err);
            }
        });
    }
}
$('#project-filter').on('input', function () {
    let filter = $(this).val();
    fetchProjectsWithFilter(filter);
});
// Fetch & show projects
function fetchProjects() {
    $.get('/projects', function (data) {
        let html = '';
        data.forEach((project, i) => {
            let percent = project.tasks_count > 0
                ? Math.round((project.completed_tasks_count / project.tasks_count) * 100)
                : 0;

            let bgColor = i % 2 === 0 ? '#c2d9ff' : '#c2ffef';

            html += `
                <tr style="background-color:${bgColor};">
                    <td>${i + 1}</td>
                    <td>${project.name}</td>
                    <td>${project.tasks_count}</td>
                    <td>${project.completed_tasks_count}</td>
                    <td>${percent}%</td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick='openEditModal(${JSON.stringify(project)})'>Edit</button>
                        <button class="btn btn-sm btn-danger" onclick='deleteRow(${JSON.stringify(project)})'>Delete</button>
                    </td>
                </tr>
            `;
        });

        $('#project-container').html(html);
    });
}
function fetchProjectsWithFilter(filter = '') {
    $.get('/projects', { filter: filter }, function (data) {
        let html = '';
        data.forEach((project, i) => {
            let percent = project.tasks_count > 0
                ? Math.round((project.completed_tasks_count / project.tasks_count) * 100)
                : 0;

            let bgColor = i % 2 === 0 ? '#c2d9ff' : '#c2ffef';

            html += `
                <tr style="background-color:${bgColor};">
                    <td>${i + 1}</td>
                    <td>${project.name}</td>
                    <td>${project.tasks_count}</td>
                    <td>${project.completed_tasks_count}</td>
                    <td>${percent}%</td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick='openEditModal(${JSON.stringify(project)})'>Edit</button>
                        <button class="btn btn-sm btn-danger" onclick='deleteRow(${JSON.stringify(project)})'>Delete</button>
                    </td>
                </tr>
            `;
        });

        $('#project-container').html(html);
    });
}

