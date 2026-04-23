<?php
require 'lib/library.php';

if (!empty($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<link rel="icon" type="image/png" href="assets/img/favicon.png">
<link rel="shortcut icon" type="image/png" href="assets/img/favicon.png">
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <form id="loginForm" action="#" class="card" novalidate>
        <h2>Login</h2>

        <div id="loginError" class="error" style="display:none;"></div>

        <input id="username" type="text" name="username" placeholder="Username" required autocomplete="username">
        <input id="password" type="password" name="password" placeholder="Password" required autocomplete="current-password">

        <button id="loginSubmit" type="submit">Login</button>

        <p><a href="register.php">Create account</a></p>
    </form>
</div>

<script>
(() => {
    const form = document.getElementById("loginForm");
    const errorEl = document.getElementById("loginError");
    const usernameEl = document.getElementById("username");
    const passwordEl = document.getElementById("password");
    const submitBtn = document.getElementById("loginSubmit");

    const setError = (msg) => {
        if (!errorEl) return;
        if (!msg) {
            errorEl.style.display = "none";
            errorEl.textContent = "";
            return;
        }
        errorEl.textContent = msg;
        errorEl.style.display = "block";
    };

    form?.addEventListener("submit", async (e) => {
        e.preventDefault();
        setError("");

        const username = (usernameEl?.value ?? "").trim();
        const password = (passwordEl?.value ?? "");

        if (!username || !password) {
            setError("Please fill in username and password.");
            return;
        }

        const originalLabel = submitBtn?.textContent ?? "Login";
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = "Logging in...";
        }

        try {
            const res = await fetch("api/login.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ username, password }),
            });

            let data = null;
            try { data = await res.clone().json(); } catch (_) {}

            if (!res.ok || data?.error) {
                const err = data?.error || "login_failed";
                if (err === "wrong_password") {
                    if (passwordEl) passwordEl.value = "";
                    setError("Wrong password");
                    passwordEl?.focus?.();
                    return;
                }
                if (err === "username_not_found") {
                    setError("Username not found");
                    usernameEl?.focus?.();
                    return;
                }
                if (err === "missing_fields") {
                    setError("Missing fields");
                    return;
                }
                setError("Login failed");
                return;
            }

            window.location.href = "index.php";
        } catch (_) {
            setError("Network error");
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = originalLabel;
            }
        }
    });
})();
</script>
</body>
</html>
