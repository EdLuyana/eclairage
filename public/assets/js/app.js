document.addEventListener('DOMContentLoaded', function () {
    const referenceInput = document.querySelector('#reference');
    const sizeSelect = document.querySelector('#size');

    if (referenceInput && sizeSelect) {
        referenceInput.addEventListener('change', function () {
            const reference = referenceInput.value.trim();

            if (reference.length > 0) {
                fetch(`/user/get-sizes?reference=${encodeURIComponent(reference)}`)
                    .then(response => response.json())
                    .then(data => {
                        sizeSelect.innerHTML = '';
                        if (data.length === 0) {
                            const option = document.createElement('option');
                            option.value = '';
                            option.textContent = 'Aucune taille disponible';
                            sizeSelect.appendChild(option);
                        } else {
                            data.forEach(size => {
                                const option = document.createElement('option');
                                option.value = size;
                                option.textContent = size;
                                sizeSelect.appendChild(option);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors de la récupération des tailles :', error);
                    });
            }
        });
    }
});
