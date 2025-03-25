function togglePassword(id) {
    const passwordInput = document.getElementById(id);
    const icon = passwordInput.nextElementSibling.querySelector('i');

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        icon.classList.replace("ri-eye-line", "ri-eye-off-line");
    } else {
        passwordInput.type = "password";
        icon.classList.replace("ri-eye-off-line", "ri-eye-line");
    }
}
