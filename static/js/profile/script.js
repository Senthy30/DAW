function changeName(){
    fnameValue = document.getElementById('fname').value;
    lnameValue = document.getElementById('lname').value;

    formData = new FormData();
    formData.append('fname', fnameValue);
    formData.append('lname', lnameValue);

    fetch("changeName.php", {
        method: "POST",
        body: formData
    }).then(function (response) {
        window.location.href = 'index.php?';
    });
}

function changePassword() {
    currPass = document.getElementById('currentPassword').value;
    newPass = document.getElementById('newPassword').value;
    confNewPass = document.getElementById('confirmNewPassword').value;

    formData = new FormData();
    formData.append('currPass', currPass);
    formData.append('newPass', newPass);
    formData.append('confNewPass', confNewPass);

    fetch("changePassword.php", {
        method: "POST",
        body: formData
    }).then(function (response) {
        window.location.href = 'index.php?';
    });
}