function validateForm() {
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirm_password').value;
    
    if(password !== confirmPassword) {
        alert("password dan konfirmasi password tidak sesuai")
        return false
    } 

    return true
}

