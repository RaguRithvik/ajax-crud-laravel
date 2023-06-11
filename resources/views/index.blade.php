<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD App Laravel 8 & Ajax</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.2/css/bootstrap.min.css' />
    <link rel='stylesheet'
        href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css' />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.10.25/datatables.min.css" />
    <style>
        .img-obc {
            object-fit: cover;
            width: 35px;
            height: 35px;
        }
    </style>
</head>

<body>
    {{-- add new employee modal start --}}
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="POST" id="add_employee_form" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4 bg-light">
                        <div class="row">
                            <div class="col-lg">
                                <label for="fname">First Name</label>
                                <input type="text" name="fname" class="form-control" placeholder="First Name"
                                    required>
                            </div>
                            <div class="col-lg">
                                <label for="lname">Last Name</label>
                                <input type="text" name="lname" class="form-control" placeholder="Last Name"
                                    required>
                            </div>
                        </div>
                        <div class="my-2">
                            <label for="email">E-mail</label>
                            <input type="email" name="email" class="form-control" placeholder="E-mail" required>
                        </div>
                        <div class="my-2">
                            <label for="phone">Phone</label>
                            <input type="tel" name="phone" class="form-control" placeholder="Phone" required>
                        </div>
                        <div class="my-2">
                            <label for="post">Post</label>
                            <input type="text" name="post" class="form-control" placeholder="Post" required>
                        </div>
                        <div class="my-2">
                            <label for="avatar">Select Avatar</label>
                            <input type="file" name="avatar" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="add_employee_btn" class="btn btn-primary">Add Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- add new employee modal end --}}

    {{-- edit employee modal start --}}
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="POST" id="edit_employee_form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="avatar" id="emp_avatar">
                    <div class="modal-body p-4 bg-light">
                        <div class="row">
                            <div class="col-lg">
                                <label for="fname">First Name</label>
                                <input type="text" name="fname" id="fname" class="form-control"
                                    placeholder="First Name" required>
                            </div>
                            <div class="col-lg">
                                <label for="lname">Last Name</label>
                                <input type="text" name="lname" id="lname" class="form-control"
                                    placeholder="Last Name" required>
                            </div>
                        </div>
                        <div class="my-2">
                            <label for="email">E-mail</label>
                            <input type="email" name="email" id="email" class="form-control"
                                placeholder="E-mail" required>
                        </div>
                        <div class="my-2">
                            <label for="phone">Phone</label>
                            <input type="tel" name="phone" id="phone" class="form-control"
                                placeholder="Phone" required>
                        </div>
                        <div class="my-2">
                            <label for="post">Post</label>
                            <input type="text" name="post" id="post" class="form-control"
                                placeholder="Post" required>
                        </div>
                        <div class="my-2">
                            <label for="avatar">Select Avatar</label>
                            <input type="file" name="avatar" class="form-control" accept="image/*">
                        </div>
                        <div class="mt-2" id="avatar">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="edit_employee_btn" class="btn btn-success">Update
                            Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- edit employee modal end --}}

    <body class="bg-light">
        <div class="container-fluid">
            <div class="row my-5">
                <div class="col-lg-12">
                    <div class="card shadow">
                        <div class="card-header bg-success d-flex justify-content-between align-items-center">
                            <h3 class="text-light">Manage Employees</h3>
                            <button class="btn btn-light" data-bs-toggle="modal"
                                data-bs-target="#addEmployeeModal"><i class="bi-plus-circle me-2"></i>Add New
                                Employee</button>
                        </div>
                        <div class="card-body" id="show_all_employees">
                            <h1 class="text-center text-secondary my-5">Loading...</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.2/js/bootstrap.bundle.min.js'></script>
        <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/datatables.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script type="text/javascript">
            const fetchAllEmployee = () => {
                $.ajax({
                    url: '{{ route('fetchAll') }}',
                    method: 'get',
                    success: function(res) {
                        $("#show_all_employees").html(res);
                        $("table").dataTable({
                            order: [0, "asc"]
                        })
                    }
                });

            }
            fetchAllEmployee();
            $("#add_employee_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#add_employee_btn").text("Adding...");
                $.ajax({
                    url: '{{ route('store') }}',
                    method: 'POST',
                    data: fd,
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(res) {
                        if (res.status = 200) {
                            swal.fire("Added !", "Employee Added Sucessfully", 'success')
                        }
                        $("#add_employee_btn").text("Add Employee");
                        $("#add_employee_form")[0].reset();
                        fetchAllEmployee()
                        $("#addEmployeeModal").modal("hide");
                    }

                })
            })

            $(document).on('click', '.editIcon', function(e) {
                e.preventDefault();
                const id_emp = $(this).attr("id");
                $.ajax({
                    url: '{{ route('editEmployee') }}',
                    method: "post",
                    data: {
                        id: id_emp,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        $("#fname").val(res.first_name)
                        $("#lname").val(res.last_name)
                        $("#email").val(res.email)
                        $("#phone").val(res.phone)
                        $("#post").val(res.post)
                        $("#emp_avatar").val(res.avatar)
                        $("#id").val(res.id)
                        $("#avatar").html(
                            `<img src="storage/images/${res.avatar}" width="100" class="img-fluid img-thumbnail" />`
                        )
                    }
                })
            })

            $("#edit_employee_form").submit(function(e) {
                e.preventDefault();
                const updateData = new FormData(this);
                $("#edit_employee_btn").text("Updating..");
                $.ajax({
                    url: '{{ route('update') }}',
                    method: 'POST',
                    data: updateData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(res) {
                        if (res.status == 200) {
                            swal.fire("Updated !", "Employee Updated Sucessfully", 'success')
                        }
                        $("#edit_employee_btn").text("Update Employee");
                        $("#edit_employee_form")[0].reset();
                        fetchAllEmployee()
                        $("#editEmployeeModal").modal("hide");
                    }
                })
            })

            $(document).on('click', '.deleteIcon', function(e) {
                e.preventDefault();
                const id_emp = $(this).attr("id");
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('delete') }}',
                            method: 'POST',
                            data: {
                                id: id_emp,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(res) {
                                if (res.status == 200) {
                                    swal.fire("Deleted !", "Employee Details has been deleted",
                                        'success');
                                    fetchAllEmployee()
                                }
                            }
                        })
                    }
                })
                $.ajax({
                    url: '{{ route('editEmployee') }}',
                    method: "post",
                    data: {
                        id: id_emp,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {

                    }
                })
            })
        </script>
    </body>

</html>
