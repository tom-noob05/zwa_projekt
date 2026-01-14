document.addEventListener('DOMContentLoaded', () => {
    const deleteForms = document.querySelectorAll('.delete-form');

    deleteForms.forEach((form) => {
        form.addEventListener('submit', (e) => {

            const confirmed = confirm('Opravdu chcete tento inzerát smazat?');

            if (!confirmed) {
                e.preventDefault();
            }
        });
    });
});