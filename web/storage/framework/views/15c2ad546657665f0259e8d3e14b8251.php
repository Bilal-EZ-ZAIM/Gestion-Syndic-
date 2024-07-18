
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.hoa-Details'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(URL::asset('build/libs/gridjs/theme/mermaid.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(URL::asset('build/libs/swiper/swiper-bundle.min.css')); ?>" rel="stylesheet">
    <style>
        .hoaImageContainer {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 300px;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .hoaImage img {
            height: 100%;
            width: 100%;
            object-fit: cover;
        }

        .cardHeader {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .cardHeader h2 {
            margin: 0;
        }

        .hoaInfo p {
            margin: 10px 0;
        }

        .modal-content {
            border-radius: 8px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            Homeowners Association
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            HOA Detail
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="details" id="hoaDetails">
                        <div class="cardHeader">
                            <h2>HOA Information</h2>
                            <div>
                                <button type="button" class="btn btn-primary add-btn" data-bs-toggle="modal"
                                    id="create-btn" data-bs-target="#showModal"><i
                                        class="ri-add-line align-bottom me-1"></i> Edit Information </button>
                                <button class="btn btn-danger" onclick="deleteHOA()">Delete</button>
                            </div>
                        </div>
                        <div class="hoaCard">
                            <div class="hoaImageContainer">
                                <img src="https://www.syr-res.com/pictures/1292318635.jpg" alt="HOA Image" class="hoaImage"
                                    id="hoaImage">
                            </div>
                            <div class="hoaInfo">
                                <h4 id="hoaName">HOA Name</h4>
                                <p id="hoaDescription" class="description">Description</p>
                                <p id="hoaAddress" class="address"><strong>Address:</strong> Address</p>
                                <p id="hoaResidents" class="residents"><strong>Total Residents:</strong> 0</p>
                                <p id="hoaIncome" class="income"><strong>Total Income:</strong> $0</p>
                                <p id="hoaExpenses" class="expenses"><strong>Total Expenses:</strong> $0</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Edit HOA Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form class="tablelist-form" autocomplete="off" id="hoaForms" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="hoaNameInput" class="form-label">HOA Name</label>
                            <input type="text" name="name" id="hoaNameInput" class="form-control"
                                placeholder="Enter HOA Name" required />
                            <div class="invalid-feedback">Please enter the HOA name.</div>
                        </div>
                        <div class="mb-3">
                            <label for="hoaDescriptionInput" class="form-label">Description</label>
                            <textarea name="description" id="hoaDescriptionInput" class="form-control" rows="3"
                                placeholder="Enter Description" required></textarea>
                            <div class="invalid-feedback">Please enter the description.</div>
                        </div>
                        <div class="mb-3">
                            <label for="hoaaddressnInput" class="form-label">address</label>
                            <textarea name="address" id="hoaaddressInput" class="form-control" rows="3" placeholder="Enter address" required></textarea>
                            <div class="invalid-feedback">Please enter the address.</div>
                        </div>
                        <div class="mb-3">
                            <label for="hoaPriceInput" class="form-label">Price Per Month</label>
                            <input type="number" name="price_per_month" id="hoaPriceInput" class="form-control"
                                placeholder="Enter Price Per Month" required />
                        </div>
                        <div class="mb-3">
                            <label for="hoaTotalInput" class="form-label">Total</label>
                            <input type="number" name="total" id="hoaTotalInput" class="form-control"
                                placeholder="Enter Total" required />
                        </div>
                        <div class="mb-3">
                            <label for="hoaImageInput" class="form-label">Image</label>
                            <input type="file" class="form-control" id="hoaImageInput" name="image">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" id="save-btn">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Include Bootstrap JS -->
    <script src="<?php echo e(URL::asset('build/libs/swiper/swiper-bundle.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/pages/ecommerce-product-details.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('<?php echo e(route('get.hoa.information')); ?>')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('hoaName').textContent = data.name;
                    document.getElementById('hoaDescription').textContent = data.description;
                    document.getElementById('hoaAddress').textContent = 'Address: ' + data.address;
                    document.getElementById('hoaResidents').textContent = 'Total Residents: ' + data.total;
                    document.getElementById('hoaIncome').textContent = 'Total Income: $' + data.price_per_month;
                    document.getElementById('hoaExpenses').textContent = 'Total Expenses: $' + data.total;

                    document.getElementById('hoaNameInput').value = data.name;
                    document.getElementById('hoaDescriptionInput').value = data.description;
                    document.getElementById('hoaaddressInput').value = data.address;
                    document.getElementById('hoaPriceInput').value = data.price_per_month;
                    document.getElementById('hoaTotalInput').value = data.total;


                    if (data.image) {
                        document.getElementById('hoaImage').src = data.image;
                    } else {
                        document.getElementById('hoaImage').src =
                            'https://www.syr-res.com/pictures/1292318635.jpg';
                    }
                });
        });

        document.getElementById('hoaForms').addEventListener('submit', function(event) {
            event.preventDefault();
            let formData = new FormData(this);

            fetch('/hoa/update', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.hoa) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated',
                            text: 'HOA updated successfully'
                        });

                        // Update the HOA details on the page
                        document.getElementById('hoaName').textContent = data.hoa.name;
                        document.getElementById('hoaDescription').textContent = data.hoa.description;

                        if (data.hoa.image) {
                            document.getElementById('hoaImage').src = data.hoa.image;
                        }

                        // Close the modal
                        var myModalEl = document.getElementById('showModal');
                        var modal = bootstrap.Modal.getInstance(myModalEl);
                        modal.hide();
                    } else if (data.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error: ' + data.error
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An unexpected error occurred'
                    });
                });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Youcode\Desktop\stafe\Stage\cindik\CindikManager\resources\views/apps-ecommerce-product-details.blade.php ENDPATH**/ ?>