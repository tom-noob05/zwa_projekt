const inputs = document.querySelectorAll('input');
const errorDiv = document.querySelector('.error-div');

inputs.forEach(input => {
    input.addEventListener('input', () => {
        if (errorDiv) {
            errorDiv.textContent = '';
        }
    });
});