<script>
    console.log("JS WORKING");
document.addEventListener("DOMContentLoaded", function() {

    document.getElementById("loginForm").addEventListener("submit", function(e){
        e.preventDefault();

        let email = document.getElementById("email").value;
        let passwordValue = document.getElementById("password").value;

        fetch("php/login.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `email=${email}&password=${passwordValue}`
        })
        .then(res => res.text())
        .then(data => {
            alert("Response: " + data); // ✅ debug

            if(data.trim() === "success"){
                window.location.href = "dashboard.php";
            } else {
                alert("Invalid login");
            }
        });
    });

    // 👁️ password toggle
    const toggle = document.getElementById("togglePassword");
    const passwordField = document.getElementById("password");

    if(toggle){  // ✅ error avoid
        toggle.addEventListener("click", function () {
            const type = passwordField.type === "password" ? "text" : "password";
            passwordField.type = type;
            this.classList.toggle("fa-eye-slash");
        });
    }

});
</script>