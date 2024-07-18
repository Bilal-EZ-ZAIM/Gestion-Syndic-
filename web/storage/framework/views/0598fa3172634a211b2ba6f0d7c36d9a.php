<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>HOA Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="live-preview container-sm">
        <form class="tablelist-form" autocomplete="off" id="hoaForms">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="customername-field" class="form-label">Customer Name</label>
                    <input type="text" name="name" id="customername-field" class="form-control"
                        placeholder="Enter Name" required />
                    <div class="invalid-feedback">Please enter a customer name.</div>
                </div>

                <div class="mb-3">
                    <label for="email-field" class="form-label">Email</label>
                    <input type="email" id="email-field" name="email" class="form-control" placeholder="Enter Email"
                        required />
                    <div class="invalid-feedback">Please enter an email.</div>
                </div>
                <div class="mb-3">
                    <label for="Password" class="form-label">Password</label>
                    <input type="email" id="Password" name="password" class="form-control" placeholder="Enter Email"
                        required />
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="imageInput" class="form-label">Image</label>
                    <input type="file" class="form-control" id="imageInput" name="avatar">
                </div>
            </div>

            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="add-btn">Add Customer</button>
                </div>
            </div>
        </form>


        

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const hoaInfoContainer = document.getElementById('hoa-info-container');

                // Utiliser Fetch pour récupérer les données du HOA à partir de la route
                fetch('<?php echo e(route('get.all.Resedonce')); ?>')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erreur de réseau');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log(data);

                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                    });
            })
        </script>


        <script>
            document.getElementById('hoaForms').addEventListener('submit', function(event) {
                event.preventDefault();

                let formData = new FormData(this);

                console.log(formData);

                fetch('<?php echo e(route('resdence.store')); ?>', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        if (data.errors) {
                            console.log('Validation errors:', data.errors);
                        } else if (data.error) {
                            console.log('Error:', data.error);
                        } else {
                            console.log('Success:', data.message);
                            console.log('Résidence:', data.residence);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        </script>
    </div>
</body>

</html>
<?php /**PATH C:\Users\Youcode\Desktop\stafe\Stage\cindik\CindikManager\resources\views/HOA/createRe.blade.php ENDPATH**/ ?>