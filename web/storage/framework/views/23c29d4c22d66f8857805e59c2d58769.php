
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.form-layouts'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <style>
        .hero-section {
            padding: 200px 0;
            /* Augmenter la valeur de 200px à ce que vous préférez */
            background-image: url('chemin/vers/votre/image.jpg');
            background-size: cover;
            background-position: center;
            color: #fff;
            /* Couleur du texte */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            /* Ombre portée pour le texte */
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="hero-section text-center">
                <h1>Bienvenue sur notre page de création de HOA</h1>
                <p>Créez votre HOA facilement avec notre formulaire simple.</p>
            </div>
        </div>
    </div>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            Forms
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>



    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="live-preview">
                    <form action="javascript:void(0);" id="hoaForms">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="nameInput" class="form-label">Name</label>
                                    <input type="text" class="form-control" placeholder="Enter name" id="nameInput"
                                        name="name">
                                    <div class="invalid-feedback" id="name-error"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="descriptionInput" class="form-label">Description</label>
                                    <textarea class="form-control" placeholder="Enter description" id="descriptionInput" name="description"></textarea>
                                    <div class="invalid-feedback" id="description-error"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="imageInput" class="form-label">Image</label>
                                    <input type="file" class="form-control" id="imageInput" name="image">
                                    <div class="invalid-feedback" id="image-error"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="totalInput" class="form-label">Total</label>
                                    <input type="number" class="form-control" placeholder="Enter total" id="totalInput"
                                        name="total">
                                    <div class="invalid-feedback" id="total-error"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="addressInput" class="form-label">Address</label>
                                    <input type="text" class="form-control" placeholder="Enter address" id="addressInput"
                                        name="address">
                                    <div class="invalid-feedback" id="address-error"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="priceInput" class="form-label">Price Per Month</label>
                                    <input type="number" class="form-control" placeholder="Enter price per month"
                                        id="priceInput" name="price_per_month">
                                    <div class="invalid-feedback" id="price-error"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <script>
                    document.getElementById('hoaForms').addEventListener('submit', function(event) {
                        event.preventDefault();

                        let formData = new FormData(this);

                        // Clear previous errors
                        document.querySelectorAll('.invalid-feedback').forEach(el => el.innerText = '');
                        document.querySelectorAll('.form-control').forEach(el => el.classList.remove('is-invalid'));

                        fetch('<?php echo e(route('hoa.store')); ?>', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content')
                                },
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.errors) {
                                    console.log('Validation errors:', data.errors);

                                    // Show errors under each field
                                    for (let field in data.errors) {
                                        let errorElement = document.getElementById(`${field}-error`);
                                        let fieldElement = document.getElementById(`${field}Input`);
                                        if (errorElement) {
                                            errorElement.textContent = data.errors[field][0];
                                            fieldElement.classList.add('is-invalid');
                                        }
                                    }


                                } else if (data.error) {
                                    console.log('Error:', data.error);
                                    Swal.fire({
                                        title: 'Error!',
                                        text: data.error,
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                } else {
                                    console.log('Success:', data.success);
                                    console.log('HOA:', data.hoa);

                                    Swal.fire({
                                        title: 'Success!',
                                        text: data.success,
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    });

                                    window.location.href = 'http://127.0.0.1:8000/getViewResedence';

                                }
                            })
                            .catch(error => console.error('Error:', error));
                    });
                </script>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('build/libs/prismjs/prism.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Youcode\Desktop\stafe\Stage\cindik\CindikManager\resources\views/HOA/forms-HOA.blade.php ENDPATH**/ ?>