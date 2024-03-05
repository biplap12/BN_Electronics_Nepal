
function showLoader() {
    var emailValue = document.querySelector('input[name="email"]').value;
    var passValue = document.querySelector('input[name="pass"]').value;

    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(emailValue)) {
        alert('Please enter a valid email address.');
        return false;
    }

    if (emailValue == '' || passValue == '') {
        alert('Please fill all the fields.');
        return false;
    } else {
        document.getElementById('loader').style.display = 'flex';
        document.getElementById('loginForm').style.background = 'white';
        document.getElementById('loginForm').style.opacity = '0.2';
        return true;
    }
}
function showloaderForgotpassword() {
    var emailInput = document.getElementById('forgotEmail');
    var email = emailInput.value;
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (emailRegex.test(email)) {
        var loaderDiv = document.getElementById('loader_div');
        
        loaderDiv.classList.remove('hide');
        loaderDiv.classList.add('loderDiv');
        
        document.getElementById('forgotId').style.background = 'white';
        document.getElementById('forgotId').style.opacity = '0.2';
    } else {
        emailInput.style.border = '2px solid red';
    }
}

