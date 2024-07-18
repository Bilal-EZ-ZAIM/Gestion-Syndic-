@extends('layouts.master')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('title')
    Maintenances
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Tables
        @endslot
        @slot('title')
            Maintenances
        @endslot
    @endcomponent

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
                                        <input type="text" class="form-control search" placeholder="Search...">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive table-card mt-3 mb-1">
                            <table class="table align-middle table-nowrap" id="customerTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="sort" data-sort="customer_name">Customer Title</th>
                                        <th class="sort" data-sort="email">Desciption</th>
                                        <th class="sort" data-sort="phone">facture</th>
                                        <th class="sort" data-sort="action">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="list form-check-all" id="tableBody">
                                    <!-- Rows will be added here dynamically -->
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
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form class="tablelist-form" autocomplete="off" id="hoaForms">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Customer Title</label>
                            <input type="text" name="title" id="title" class="form-control"
                                placeholder="Enter title" required />
                            <div class="invalid-feedback" id="title-error"></div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" name="description" id="description" class="form-control"
                                placeholder="Enter description" required />
                            <div class="invalid-feedback" id="description-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="facture" class="form-label">Facture</label>
                            <input type="number" name="facture" id="facture" class="form-control"
                                placeholder="Enter facture" required />
                            <div class="invalid-feedback" id="facture-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="imageInput" class="form-label">Image</label>
                            <input type="file" class="form-control" id="imageInput" name="image">
                            <div class="invalid-feedback" id="image-error"></div>
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
                    <h5 class="modal-title" id="updateModalLabel">Update Maintenances</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-update-modal"></button>
                </div>
                <form class="tablelist-form" autocomplete="off" id="updateForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="update-title-field" class="form-label">Customer Title</label>
                            <input type="text" name="title" id="update-title-field" class="form-control"
                                placeholder="Enter Name" required />
                            <div class="invalid-feedback" id="update-title-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="update-description-field" class="form-label">Description</label>
                            <input type="text" name="description" id="update-description-field" class="form-control"
                                placeholder="Enter description" required />
                            <div class="invalid-feedback" id="update-description-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="update-facture-field" class="form-label">Facture</label>
                            <input type="text" name="facture" id="update-facture-field" class="form-control"
                                placeholder="Enter facture" required />
                            <div class="invalid-feedback" id="update-facture-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="update-imageInput" class="form-label">Image</label>
                            <input type="file" class="form-control" id="update-imageInput" name="image">
                            <div class="invalid-feedback" id="update-image-error"></div>
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
@endsection
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Function to filter table rows based on search input
            function filterTableRows() {
                const searchBox = document.querySelector('.search-box input');
                const searchTerm = searchBox.value.toLowerCase();
                const tableRows = document.querySelectorAll('#customerTable tbody tr');

                tableRows.forEach(row => {
                    const customerTitle = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                    console.log(customerTitle);
                    if (customerTitle.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // Attach the filter function to the input event of the search box
            document.querySelector('.search-box input').addEventListener('input', filterTableRows);

            document.getElementById('hoaForms').addEventListener('submit', function(event) {
                event.preventDefault();
                let formData = new FormData(this);
                fetch('{{ route('maintenances.store') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.errors) {
                            // Display validation errors
                            for (let field in data.errors) {
                                let errorElement = document.getElementById(`${field}-error`);
                                let fieldElement = document.getElementById(field);
                                if (errorElement) {
                                    errorElement.textContent = data.errors[field][0];
                                    fieldElement.classList.add('is-invalid');
                                }
                            }
                        } else if (data.error) {
                            console.log('Error:', data.error);
                        } else {
                            addRowToTable(data.maintenances);
                            Swal.fire('Updated!', data.success, 'success');
                            var myModalEl = document.getElementById('showModal');
                            var modal = bootstrap.Modal.getInstance(myModalEl);
                            modal.hide();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });

            fetch('{{ route('get.all.maintenances') }}')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur de réseau');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.maintenances && Array.isArray(data.maintenances)) {
                        updateTable(data.maintenances);
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
    <td>${item.title}</td>
    <td>${item.description}</td>
    <td>${item.facture}</td>
    <td>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-success edit-item-btn" data-bs-toggle="modal"
                onclick="openUpdateModal(${item.id}, '${item.title}', '${item.description}', '${item.facture}')">Edit</button>
            <button class="btn btn-sm btn-primary remove-item-btn" onclick="confirmDelete(${item.id})">Remove</button>
        </div>
    </td>
</tr>
`;
        }

        function openUpdateModal(id, title, description, facture) {
            document.getElementById('update-title-field').value = title;
            document.getElementById('update-description-field').value = description;
            document.getElementById('update-facture-field').value = facture;

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

            fetch(`{{ url('/maintenances/update') }}/${id}`, {
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

                    Swal.fire('Updated!', 'Your record has been updated.', 'success');
                    const row = document.querySelector(`tr[data-id="${id}"]`);
                    if (row) {
                        row.querySelector('td:nth-child(1)').textContent = data.maintenances.title;
                        row.querySelector('td:nth-child(2)').textContent = data.maintenances.description;
                        row.querySelector('td:nth-child(3)').textContent = data.maintenances.facture;
                    }
                    var updateModalEl = document.getElementById('updateModal');
                    var modal = bootstrap.Modal.getInstance(updateModalEl);
                    modal.hide();

                })
                .catch(error => {
                    console.error('Error:', error);

                    console.log(error.error.title[0]);

                    // Show validation errors if available in error response
                    if (error.error) {
                        for (let field in error.error) {
                            let errorElement = document.getElementById(`update-${field}-error`);
                            let fieldElement = document.getElementById(`update-${field}-field`);
                            if (errorElement) {
                                errorElement.textContent = error.error[field][0];
                                fieldElement.classList.add('is-invalid');
                            }
                        }
                    } else {
                        Swal.fire('Error!', 'There was an error updating the record.', 'error');
                    }
                });
        }

        // Reset validation errors on input
        document.querySelectorAll('.form-control').forEach(element => {
            element.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    this.classList.remove('is-invalid');
                    let errorElement = document.getElementById(`${this.id}-error`);
                    if (errorElement) {
                        errorElement.textContent = '';
                    }
                }
            });
        });



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
            fetch(`{{ url('/getdeleteMaintenances') }}/${id}`, {
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
                        setTimeout(() => row.remove(), 1000);

                        Swal.fire('Deleted!', 'Your record has been deleted.', 'success');

                        updateTable(data.maintenances);
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
    <td>${item.title}</td>
    <td>${item.description}</td>
    <td>${item.facture}</td>
    <td>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-success edit-item-btn" data-bs-toggle="modal"
                onclick="openUpdateModal(${item.id}, '${item.title}', '${item.description}', '${item.facture}')">Edit</button>
            <button class="btn btn-sm btn-primary remove-item-btn" onclick="confirmDelete(${item.id})">Remove</button>
        </div>
    </td>
</tr>
`;
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
