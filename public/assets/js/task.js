$(document).ready(fetchTasks);
// Open modal for create
function openCreateModal() {
    $('#modalTitle').text('Add Task');
    $('#task-form').attr('action', '/tasks'); 
    $('#form-method').val('POST');
    $('#task-id').val('');
    $('#task-name').val('');
    $('#task-description').val('');
    $('#taskModal').modal('show');
}

// Open modal for edit
function openEditModal(task) {
    console.log(task.project.name);
    $('#modalTitle').text('Edit Project');
    $('#task-form').attr('action', '/tasks/' + task.id); 
    $('#form-method').val('PUT');
    $('#task-project-id').val(task.project.id)
    $('#task-id').val(task.id);
    $('#task-name').val(task.title);
    $('#task-description').val(task.description);
    $('#task-milestone').val(task.due_date);
    $('#taskModal').modal('show');
}

// Submit form (Create or Update)
$('#taskForm').on('submit', function (e) {
    e.preventDefault();

    let id = $('#task-id').val();
    let formData = $(this).serialize(); 

    let url = id ? `/tasks/${id}` : '/tasks';
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
            $('#taskModal').modal('hide');
            $('.modal-backdrop').remove(); // removes the backdrop
            $('body').removeClass('modal-open'); // removes body scroll lock
            $('body').css('padding-right', '');
            $('#taskForm')[0].reset();
            fetchTasks();
            toastr.success("Task successfully saved!");
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
function deleteRow(task) {
    if (confirm(`Are you sure you want to delete task "${task.title}"?`)) {
        $.ajax({
            url: `/tasks/${task.id}`,
            method: 'POST', 
            data: {
                _method: 'DELETE',
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function () {
                fetchTasks(); 
                toastr.success("Task successfully deleted!");
            },
            error: function (err) {
                toastr.success("Something went wrong while deleting.");
                console.error(err);
            }
        });
    }
}
$('#task-filter').on('input', function () {
    let filter = $(this).val();
    fetchTasksWithFilter(filter);
});

$('#task-status-filter').on('change', function () {
    let filter = $(this).val();
    if (filter === 'all') {
        fetchTasks();
    } else {
        fetchTasksWithFilter(filter);
    }
})
// Fetch & show tasks
function fetchTasks() {
    $.get('/tasks', function (data) {
        let html = '';
        data.forEach((task, i) => {
            let bgColor = i % 2 === 0 ? '#c2d9ff' : '#c2ffef';

            html += `
                <tr style="background-color:${bgColor};">
                    <td>${i + 1}</td>
                    <td>${task.project.name}</td>
                    <td>${task.title}</td>
                    <td>${task.due_date}</td>
                    <td><button class="btn btn-sm btn-info" onclick='toggleStatus(${JSON.stringify(task)})'>${task.status=='completed'?'Mark as Pending':'Mark as Completed'}</button></td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick='openEditModal(${JSON.stringify(task)})'>Edit</button>
                        <button class="btn btn-sm btn-danger" onclick='deleteRow(${JSON.stringify(task)})'>Delete</button>
                    </td>
                </tr>
            `;
        });

        $('#task-container').html(html);
    });
}
function fetchTasksWithFilter(filter = '') {
    $.get('/tasks', { filter: filter }, function (data) {
        let html = '';
        data.forEach((task, i) => {
            let bgColor = i % 2 === 0 ? '#c2d9ff' : '#c2ffef';

            html += `
                <tr style="background-color:${bgColor};">
                    <td>${i + 1}</td>
                    <td>${task.project.name}</td>
                    <td>${task.title}</td>
                    <td>${task.due_date}</td>
                    <td><button class="btn btn-sm btn-info" onclick='toggleStatus(${JSON.stringify(task)})'>${task.status=='completed'?'Mark as Pending':'Mark as Completed'}</button></td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick='openEditModal(${JSON.stringify(task)})'>Edit</button>
                        <button class="btn btn-sm btn-danger" onclick='deleteRow(${JSON.stringify(task)})'>Delete</button>
                    </td>
                </tr>
            `;
        });

        $('#task-container').html(html);
    });
}

// Toggle task status
function toggleStatus(task) {
    if (confirm(`Are you sure you want to change task status ?`)) {
        let newStatus = task.status === 'Completed' ? 'Pending' : 'Completed';
        $.ajax({
            url: `/tasks/${task.id}/status`,
            method: 'POST',
            data: {
                task_id: task.id,
                status: newStatus,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function () {
                fetchTasks(); 
                toastr.success(`Task status updated to ${newStatus}!`);
            },
            error: function (err) {
                toastr.error("Something went wrong while updating status.");
                console.error(err);
            }
        });
    }
}
