const validator = (firstInput, secondInput) => {
    let password
    selector(firstInput).addEventListener('keyup', function (e) {
        password = e.target.value

        if (/[A-Z]/.test(password)) {
            selector('.password-upper').style.color = 'green';
        } else {
            selector('.password-upper').style.color = 'red';
        }
        if (password.length > 7) {
            selector('.password-length').style.color = 'green';
        } else {
            selector('.password-length').style.color = 'red';
        }
        if (/[a-z]/.test(password)) {
            selector('.password-lower').style.color = 'green';
        } else {
            selector('.password-lower').style.color = 'red';
        }
        if (/[0-9]/.test(password)) {
            selector('.password-number').style.color = 'green';
        } else {
            selector('.password-number').style.color = 'red';
        }
        if (/[^A-Za-z0-9]/.test(password)) {
            selector('.password-special').style.color = 'green';
        } else {
            selector('.password-special').style.color = 'red';
        }
    })
    selector(secondInput).addEventListener('keyup', function (e) {
        e.preventDefault();
        if (e.target.value === password) {
            selector('.password-check-same').style.color = 'green';
            selector('.password-check-same').innerHTML = 'Les mots de passe correspondent';
        } else {
            selector('.password-check-same').style.color = 'red';
            selector('.password-check-same').innerHTML = 'Les mots de passe ne correspondent pas';
        }

    })
}

const selector = (selector) => {
    return document.querySelector(selector)
}

if (selector('#register_commercant_password_first')) {
    validator('#register_commercant_password_first', '#register_commercant_password_second')
}

if (selector('#register_password_first')) {
    validator('#register_password_first', '#register_password_second')
}