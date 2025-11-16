function validatePassword(){
    let passwordField = document.querySelector('#password');
    let confirmPasswordField = document.querySelector('#confirmPassword');    

    let password = passwordField.value;
    let confirmPassword = confirmPasswordField.value;

    if (password != confirmPassword)
    {
        alert("Passwords don't match!");
        return false;
    }else
    {
        return true;
    }
}