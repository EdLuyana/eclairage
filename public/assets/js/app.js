document.addEventListener('DOMContentLoaded', function () {
    const referenceInput = document.querySelector('#reference');
    const sizeSelect = document.querySelector('#size');
    const quantityInput = document.querySelector('#quantity');
    const form = document.querySelector('form');

    // Chargement dynamique des tailles
    if (referenceInput && sizeSelect) {
        const url = referenceInput.dataset.sizesUrl;

        referenceInput.addEventListener('change', function () {
            const reference = referenceInput.value.trim();

            if (reference.length > 0 && url) {
                fetch(`${url}?reference=${encodeURIComponent(reference)}`)
                    .then(response => response.json())
                    .then(data => {
                        sizeSelect.innerHTML = '';

                        const defaultOption = document.createElement('option');
                        defaultOption.value = '';
                        defaultOption.textContent = 'Sélectionnez une taille';
                        sizeSelect.appendChild(defaultOption);

                        if (data.sizes && data.sizes.length > 0) {
                            data.sizes.forEach(size => {
                                const option = document.createElement('option');
                                option.value = size;
                                option.textContent = size;
                                sizeSelect.appendChild(option);
                            });
                        } else {
                            const option = document.createElement('option');
                            option.value = '';
                            option.textContent = 'Aucune taille disponible';
                            sizeSelect.appendChild(option);
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors de la récupération des tailles :', error);
                    });
            } else {
                sizeSelect.innerHTML = '';
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'Sélectionnez une taille';
                sizeSelect.appendChild(option);
            }
        });
    }

    // Toast après ajout de stock
    if (form && window.location.pathname.includes('/add-stock')) {
        form.addEventListener('submit', function () {
            setTimeout(() => {
                const success = document.querySelector('.alert-success');
                if (success) {
                    showToast(success.textContent.trim());
                    referenceInput.value = '';
                    sizeSelect.innerHTML = '<option value="">Sélectionnez une taille</option>';
                    if (quantityInput) {
                        quantityInput.value = '';
                    }
                    success.remove(); // on le retire du DOM pour éviter l’affichage brut
                }
            }, 300);
        });
    }

    // Fonction de toast Bootstrap
    function showToast(message) {
        const toastContainer = document.getElementById('toast-container');

        if (!toastContainer) return;

        const toastElement = document.createElement('div');
        toastElement.className = 'toast align-items-center text-bg-success border-0';
        toastElement.setAttribute('role', 'alert');
        toastElement.setAttribute('aria-live', 'assertive');
        toastElement.setAttribute('aria-atomic', 'true');
        toastElement.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
            </div>
        `;

        toastContainer.appendChild(toastElement);

        const toast = new bootstrap.Toast(toastElement, { delay: 4000 });
        toast.show();

        toastElement.addEventListener('hidden.bs.toast', () => toastElement.remove());
    }
});
