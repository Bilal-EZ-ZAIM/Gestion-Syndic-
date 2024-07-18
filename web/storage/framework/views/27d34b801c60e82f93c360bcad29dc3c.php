
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<?php $__env->startSection('title'); ?>
    Listjs
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(URL::asset('build/libs/sweetalert2/sweetalert2.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            Tables
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            Listjs
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <style>
        .deleted-row {
            background-color: #f8d7da;
            transition: background-color 0.5s ease;
        }

        .invalid-feedback {
            display: block;
            color: red;
            font-size: 14px;
            margin-top: 2px;
        }
    </style>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Add, Edit & Remove</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div id="customerList">
                        <div class="row g-4 mb-3">
                            <div class="col-sm-auto">
                                <div>
                                    <button type="button" class="btn btn-primary add-btn" data-bs-toggle="modal"
                                        id="create-btn" data-bs-target="#showModal"><i
                                            class="ri-add-line align-bottom me-1"></i> Add</button>
                                    <button class="btn btn-soft-danger" onClick="deleteMultiple()"><i
                                            class="ri-delete-bin-2-line"></i></button>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <input type="text" class="form-control search search-box input"
                                            placeholder="Search...">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive table-card mt-3 mb-1">
                            <table class="table align-middle table-nowrap" id="customerTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="sort" data-sort="customer_name">Customer Name</th>
                                        <th class="sort" data-sort="email">Email</th>
                                        <th class="sort" data-sort="phone">Phone</th>
                                        <th class="sort" data-sort="phone">apartment number</th>
                                        <th class="sort" data-sort="action">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="list form-check-all" id="tableBody">

                                </tbody>
                            </table>
                            <div class="noresult" style="display: none">
                                <div class="text-center">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                        colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px">
                                    </lord-icon>
                                    <h5 class="mt-2">Sorry! No Result Found</h5>
                                    <p class="text-muted mb-0">We've searched more than 150+ Orders We did not find any
                                        orders for you search.</p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <div class="pagination-wrap hstack gap-2">
                                <a class="page-item pagination-prev disabled" href="#">
                                    Previous
                                </a>
                                <ul class="pagination listjs-pagination mb-0"></ul>
                                <a class="page-item pagination-next" href="#">
                                    Next
                                </a>
                            </div>
                        </div>
                    </div>
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end col -->
    </div>

    <!-- Modal for-->
    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Add Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form class="tablelist-form" autocomplete="off" id="hoaForms">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name-field" class="form-label">Customer Name</label>
                            <input type="text" name="name" id="name-field" class="form-control"
                                placeholder="Enter Name" required />
                            <div class="invalid-feedback" id="name-error"></div>
                        </div>

                        <div class="mb-3">
                            <label for="email-field" class="form-label">Email</label>
                            <input type="email" id="email-field" name="email" class="form-control"
                                placeholder="Enter Email" required />
                            <div class="invalid-feedback" id="email-error"></div>
                        </div>

                        <div class="mb-3">
                            <label for="password-field" class="form-label">Password</label>
                            <input type="password" id="password-field" name="password" class="form-control"
                                placeholder="Enter Password" required />
                            <div class="invalid-feedback" id="password-error"></div>
                        </div>

                        <div class="mb-3">
                            <label for="avatar-field" class="form-label">Image</label>
                            <input type="file" class="form-control" id="avatar-field" name="avatar">
                            <div class="invalid-feedback" id="avatar-error"></div>
                        </div>

                        <div class="mb-3">
                            <label for="phone-field" class="form-label">Phone</label>
                            <input type="text" id="phone-field" name="phone" class="form-control"
                                placeholder="Enter Phone" />
                            <div class="invalid-feedback" id="phone-error"></div>
                        </div>

                        <div class="mb-3">
                            <label for="apartment_number-field" class="form-label">Apartment Number</label>
                            <input type="text" id="apartment_number-field" name="apartment_number"
                                class="form-control" placeholder="Enter Apartment Number" />
                            <div class="invalid-feedback" id="apartment_number-error"></div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="modal-footer">
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success" id="add-btn">Add Customer</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Modal for Update -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="updateModalLabel">Update Resident</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-update-modal"></button>
                </div>
                <form class="tablelist-form" autocomplete="off" id="updateForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="update-name-field" class="form-label">Customer Name</label>
                            <input type="text" name="name" id="update-name-field" class="form-control"
                                placeholder="Enter Name" />
                            <div class="invalid-feedback" id="update-name-error"></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="modal-footer">
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success" id="update-btn">Update Customer</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            function filterTableRows() {
                const searchBox = document.querySelector('.search-box input');
                const searchTerm = searchBox.value.toLowerCase();
                const tableRows = document.querySelectorAll('#customerTable tbody tr');

                tableRows.forEach(row => {
                    const customerTitle = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                    if (customerTitle.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            document.querySelector('.search-box input').addEventListener('input', filterTableRows);


            document.getElementById('hoaForms').addEventListener('submit', function(event) {
                event.preventDefault();

                // Clear previous errors
                document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
                document.querySelectorAll('.form-control').forEach(el => el.classList.remove('is-invalid'));

                let formData = new FormData(this);

                fetch('<?php echo e(route('resdence.store')); ?>', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw data;
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.residence) {
                            addRowToTable(data.residence);
                            Swal.fire('Created', data.message, 'success');
                            var myModalEl = document.getElementById('showModal');
                            var modal = bootstrap.Modal.getInstance(myModalEl);
                            modal.hide();
                        }
                    })
                    .catch(error => {
                        console.log(error.error);
                        if (error.error) {
                            for (let field in error.error) {
                                let errorElement = document.getElementById(`${field}-error`);
                                let fieldElement = document.getElementById(`${field}-field`);
                                if (errorElement) {
                                    errorElement.textContent = error.error[field][0];
                                    fieldElement.classList.add('is-invalid');
                                }
                            }
                        } else {
                            console.error('Error:', error);
                            // Show general error message to the user if necessary
                        }
                    });
            });



            fetch('<?php echo e(route('get.all.Resedonce')); ?>')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur de réseau');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.residence && Array.isArray(data.residence)) {
                        updateTable(data.residence);
                    } else {
                        console.error('Format des données incorrect');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                });
        });

        function addRowToTable(item) {
            const tableBody = document.getElementById('tableBody');
            tableBody.innerHTML += `
    <tr data-id="${item.id}">
        <td>${item.name}</td>
        <td>${item.email}</td>
         <td>${item.phone}</td>
        <td>${item.apartment_number}</td>
        <td>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-success edit-item-btn" data-bs-toggle="modal"
                    onclick="openUpdateModal(${item.id}, '${item.name}')">Edit</button>
                <button class="btn btn-sm btn-primary remove-item-btn" onclick="confirmDelete(${item.id})">Remove</button>
            </div>
        </td>
    </tr>
    `;
        }

        function openUpdateModal(id, name) {
            document.getElementById('update-name-field').value = name;
            document.getElementById('updateForm').onsubmit = function(event) {
                event.preventDefault();
                updateRecord(id);
            };
            var updateModalEl = document.getElementById('updateModal');
            var modal = new bootstrap.Modal(updateModalEl);
            modal.show();
        }

        function updateRecord(id) {
            let formData = new FormData(document.getElementById('updateForm'));

            fetch(`<?php echo e(url('/residents')); ?>/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw data;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    // Clear previous errors

                    Swal.fire('Updated!', 'Your record has been updated.', 'success');


                    const row = document.querySelector(`tr[data-id="${id}"]`);
                    if (row) {
                        row.querySelector('td:nth-child(1)').textContent = data.resident.name;
                    }

                    var updateModalEl = document.getElementById('updateModal');
                    var modal = bootstrap.Modal.getInstance(updateModalEl);
                    modal.hide();
                })
                .catch(error => {
                    // Handle and display validation errors
                    if (error.errors) {
                        for (let field in error.errors) {
                            let errorElement = document.getElementById(`update-${field}-error`);
                            let fieldElement = document.getElementById(`update-${field}-field`);
                            if (errorElement && fieldElement) {
                                errorElement.textContent = error.errors[field][0];
                                fieldElement.classList.add('is-invalid');
                            }
                        }
                    } else if (error.error) {
                        console.error('Error:', error.error);
                    } else {
                        console.error('Unknown error:', error);
                    }
                });
        }




        function confirmDelete(id) {
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
                    deleteRecord(id);
                }
            });
        }

        function deleteRecord(id) {
            fetch(`<?php echo e(url('/getdeleteResedence')); ?>/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data) {
                        const row = document.querySelector(`tr[data-id="${id}"]`);
                        row.classList.add('deleted-row');
                        setTimeout(() => row.remove(), 1000); // Remove after 1 second for animation effect

                        Swal.fire('Deleted!', 'Your record has been deleted.', 'success');

                        // Optionally update the table after deletion
                        updateTable(data.residence);
                    } else {
                        console.error('Error:', data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function updateTable(data) {
            const tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = '';

            data.forEach(item => {
                tableBody.innerHTML += `
    <tr data-id="${item.id}">
        <td>${item.name}</td>
        <td>${item.email}</td>
         <td>${item.phone}</td>
        <td>${item.apartment_number}</td>
        <td>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-success edit-item-btn" data-bs-toggle="modal"
                    onclick="openUpdateModal(${item.id}, '${item.name}')">Edit</button>
                <button class="btn btn-sm btn-primary remove-item-btn" onclick="confirmDelete(${item.id})">Remove</button>
            </div>
        </td>
    </tr>
    `;
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Youcode\Desktop\stafe\Stage\cindik\CindikManager\resources\views/listResidence.blade.php ENDPATH**/ ?>