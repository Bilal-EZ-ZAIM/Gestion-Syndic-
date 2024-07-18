<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afficher et éditer les informations du HOA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div id="hoa-info-container">
            <!-- Les informations sur le HOA seront affichées ici -->
        </div>
        <!-- Ajouter زر لفتح الـ modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editHoaModal">
            Edit HOA Information
        </button>
    </div>

    <!-- Modal pour l'édition du HOA -->
    <div class="modal fade" id="editHoaModal" tabindex="-1" aria-labelledby="editHoaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editHoaModalLabel">Édition du HOA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulaire pour l'édition du HOA -->
                    <form id="editHoaForm">
                        <!-- Les champs du formulaire seront remplis avec les données du HOA -->
                        <!-- Vous pouvez les pré-remplir en JavaScript après avoir récupéré les données -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" id="btnSaveChanges">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hoaInfoContainer = document.getElementById('hoa-info-container');

            // Utiliser Fetch pour récupérer les données du HOA à partir de la route
            fetch('<?php echo e(route('get.hoa.information')); ?>')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur de réseau');
                    }
                    return response.json();
                })
                .then(data => {
                    // Afficher les données du HOA dans l'élément HTML
                    hoaInfoContainer.innerHTML = `
                        <h1>Informations sur le HOA</h1>
                        <ul>
                            <li>ID: ${data.id}</li>
                            <li>Nom: ${data.name}</li>
                            <li>Description: ${data.description}</li>
                            <li>Adresse: ${data.address}</li>
                            <li>Prix par mois: ${data.price_per_month}</li>
                            <li>Total: ${data.total}</li>
                            <li>Date de création: ${data.created_at}</li>
                            <li>Date de mise à jour: ${data.updated_at}</li>
                        </ul>`;

                    // Pré-remplir le formulaire de modification avec les données du HOA
                    const editHoaForm = document.getElementById('editHoaForm');
                    editHoaForm.innerHTML = `
                        <input type="hidden" id="hoaId" value="${data.id}">
                        <div class="mb-3">
                            <label for="nameInput" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nameInput" value="${data.name}">
                        </div>
                        <div class="mb-3">
                            <label for="descriptionInput" class="form-label">Description</label>
                            <textarea class="form-control" id="descriptionInput">${data.description}</textarea>
                        </div>
                        <!-- Ajoutez les autres champs du formulaire avec leurs valeurs correspondantes -->
                    `;
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    hoaInfoContainer.innerHTML =
                        '<p>Une erreur est survenue lors de la récupération des informations du HOA.</p>';
                });

            document.getElementById('btnSaveChanges').addEventListener('click', function() {
                const hoaId = document.getElementById('hoaId').value;
                const name = document.getElementById('nameInput').value;
                const description = document.getElementById('descriptionInput').value;
                // Ajouter les autres valeurs des champs du formulaire ici

                fetch(`/hoa/${hoaId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({
                        name: name,
                        description: description,
                        // Ajouter les autres champs du formulaire ici
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur de réseau');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.errors) {
                        console.error('Validation Errors:', data.errors);
                        // Gérer les erreurs de validation
                    } else {
                        console.log('HOA mis à jour avec succès:', data.hoa);
                        // Mettre à jour les informations du HOA dans l'interface utilisateur
                        hoaInfoContainer.innerHTML = `
                            <h1>Informations sur le HOA</h1>
                            <ul>
                                <li>ID: ${data.hoa.id}</li>
                                <li>Nom: ${data.hoa.name}</li>
                                <li>Description: ${data.hoa.description}</li>
                                <li>Adresse: ${data.hoa.address}</li>
                                <li>Prix par mois: ${data.hoa.price_per_month}</li>
                                <li>Total: ${data.hoa.total}</li>
                                <li>Date de création: ${data.hoa.created_at}</li>
                                <li>Date de mise à jour: ${data.hoa.updated_at}</li>
                            </ul>`;
                        // Fermer le modal
                        var editHoaModal = new bootstrap.Modal(document.getElementById('editHoaModal'));
                        editHoaModal.hide();
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                });
            });
        });
    </script>

</body>

</html>
<?php /**PATH C:\Users\Youcode\Desktop\stafe\Stage\cindik\CindikManager\resources\views/HOA/getHoa.blade.php ENDPATH**/ ?>