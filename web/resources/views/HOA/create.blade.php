<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HOA Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="live-preview">
        <form action="javascript:void(0);" id="hoaForms">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nameInput" class="form-label">Name</label>
                        <input type="text" class="form-control" placeholder="Enter name" id="nameInput"
                            name="name">
                    </div>
                </div><!--end col-->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="descriptionInput" class="form-label">Description</label>
                        <textarea class="form-control" placeholder="Enter description" id="descriptionInput" name="description"></textarea>
                    </div>
                </div><!--end col-->
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="imageInput" class="form-label">Image</label>
                        <input type="file" class="form-control" id="imageInput" name="image">
                    </div>
                </div><!--end col-->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="totalInput" class="form-label">Total</label>
                        <input type="number" class="form-control" placeholder="Enter total" id="totalInput"
                            name="total">
                    </div>
                </div><!--end col-->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="addressInput" class="form-label">Address</label>
                        <input type="text" class="form-control" placeholder="Enter address" id="addressInput"
                            name="address">
                    </div>
                </div><!--end col-->
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="priceInput" class="form-label">Price Per Month</label>
                        <input type="number" class="form-control" placeholder="Enter price per month" id="priceInput"
                            name="price_per_month">
                    </div>
                </div><!--end col-->
                <div class="col-lg-12">
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </form>


        <script>
            document.getElementById('hoaForms').addEventListener('submit', function(event) {
                event.preventDefault();

                let formData = new FormData(this);

                console.log(formData);

                fetch('{{ route('hoa.store') }}', {
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
                        } else if (data.error) {
                            console.log('Error:', data.error);
                        } else {
                            console.log('Success:', data.success);
                            console.log('HOA:', data.hoa);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        </script>
    </div>
</body>

</html>
