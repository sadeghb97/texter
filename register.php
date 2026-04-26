<?php
require 'lib/library.php';

$conn = new TexterConnection();
$auth = new TexterAuth();
$auth->bootstrapRememberMe($conn);

if ($auth->isLoggedIn()) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register</title>
<link rel="icon" type="image/png" href="assets/img/favicon.png">
<link rel="shortcut icon" type="image/png" href="assets/img/favicon.png">
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <form id="registerForm" action="#" class="card" novalidate>
        <h2>Create Account</h2>

        <div id="registerError" class="error" style="display:none;"></div>

        <input id="username" type="text" name="username" placeholder="Username" required autocomplete="username">
        <input id="password" type="password" name="password" placeholder="Password" required autocomplete="new-password">

        <button id="registerSubmit" type="submit">Register</button>

        <p><a href="login.php">Already have an account?</a></p>
    </form>
</div>

<script>
(() => {
    const form = document.getElementById("registerForm");
    const errorEl = document.getElementById("registerError");
    const usernameEl = document.getElementById("username");
    const passwordEl = document.getElementById("password");
    const submitBtn = document.getElementById("registerSubmit");

    // On mobile, virtual keyboards can overlap centered layouts.
    // Ensure the focused field stays visible.
    const scrollFieldIntoView = (el) => {
        if (!el || typeof el.scrollIntoView !== "function") return;
        // Defer until after the keyboard/viewport has adjusted.
        setTimeout(() => {
            try {
                el.scrollIntoView({ behavior: "smooth", block: "center", inline: "nearest" });
            } catch (_) {
                el.scrollIntoView(true);
            }
        }, 250);
    };

    document.addEventListener(
        "focusin",
        (e) => {
            const t = e.target;
            if (t && (t.tagName === "INPUT" || t.tagName === "TEXTAREA" || t.tagName === "SELECT")) {
                scrollFieldIntoView(t);
            }
        },
        { passive: true }
    );

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

        const originalLabel = submitBtn?.textContent ?? "Register";
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = "Creating...";
        }

        try {
            const res = await fetch("api/register.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ username, password }),
            });

            let data = null;
            try { data = await res.clone().json(); } catch (_) {}

            if (!res.ok || data?.error) {
                const err = data?.error || "register_failed";
                if (err === "username_exists") {
                    setError("Username already exists");
                    usernameEl?.focus?.();
                    return;
                }
                if (err === "username_too_short") {
                    setError("Username must be at least 3 characters");
                    usernameEl?.focus?.();
                    return;
                }
                if (err === "password_too_short") {
                    setError("Password must be at least 4 characters");
                    if (passwordEl) passwordEl.value = "";
                    passwordEl?.focus?.();
                    return;
                }
                if (err === "missing_fields") {
                    setError("Missing fields");
                    return;
                }
                setError("Register failed");
                return;
            }

            // API auto-logs in on success; go to index.
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
