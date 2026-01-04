<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - MiniStrava</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #fc4c02 0%, #e84118 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.15);
            max-width: 440px;
            width: 100%;
            padding: 48px 40px;
        }

        .logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-text {
            font-size: 32px;
            font-weight: 700;
            color: #fc4c02;
            letter-spacing: -0.5px;
        }

        h1 {
            font-size: 24px;
            font-weight: 600;
            color: #242428;
            margin-bottom: 8px;
            text-align: center;
        }

        .subtitle {
            font-size: 14px;
            color: #6d6d78;
            text-align: center;
        }

        .form {
            margin-top: 24px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            font-size: 14px;
            line-height: 1.5;
            display: none;
            margin-top: 24px;
        }

        .alert.show {
            display: block;
        }

        .alert-error {
            background: #fee;
            border: 1px solid #fcc;
            color: #c00;
        }

        .alert-success {
            background: #efe;
            border: 1px solid #cfc;
            color: #060;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #242428;
            margin-bottom: 8px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            font-size: 15px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: white;
            transition: all 0.2s;
            font-family: inherit;
        }

        input[type="email"]:disabled {
            background: #f3f4f6;
            color: #6d6d78;
            cursor: not-allowed;
        }

        input[type="password"]:focus {
            outline: none;
            border-color: #fc4c02;
            box-shadow: 0 0 0 3px rgba(252, 76, 2, 0.1);
        }

        input.error {
            border-color: #c00;
        }

        input.error:focus {
            box-shadow: 0 0 0 3px rgba(204, 0, 0, 0.1);
        }

        .password-requirements {
            font-size: 12px;
            color: #6d6d78;
            margin-top: 6px;
            line-height: 1.5;
        }

        .field-error {
            font-size: 12px;
            color: #c00;
            margin-top: 6px;
            display: none;
        }

        .field-error.show {
            display: block;
        }

        button[type="submit"] {
            width: 100%;
            padding: 14px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            background: #fc4c02;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
            position: relative;
        }

        button[type="submit"]:hover:not(:disabled) {
            background: #e84118;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(252, 76, 2, 0.3);
        }

        button[type="submit"]:active:not(:disabled) {
            transform: translateY(0);
        }

        button[type="submit"]:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .footer-link {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: #6d6d78;
        }

        .footer-link a {
            color: #fc4c02;
            text-decoration: none;
            font-weight: 500;
        }

        .footer-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .container {
                padding: 32px 24px;
            }

            h1 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <div class="logo-text">MINISTRAVA</div>
        </div>

        <h1>Reset your password</h1>
        <p class="subtitle">Enter your new password below</p>

        
        <div id="alert" class="alert"></div>
        <form class="form" id="resetForm">
            
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="form-group">
                <label for="email">Email address</label>
                <input disabled type="email" id="email" name="email" value="{{ $email ?? '' }}" required>
            </div>

            <div class="form-group">
                <label for="password">New password</label>
                <input type="password" id="password" name="password" required>
                <div id="passwordError" class="field-error"></div>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm new password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
                <div id="confirmError" class="field-error"></div>
            </div>

            <button type="submit" id="submitBtn">Reset password</button>
        </form>
    </div>
</body>
<script>
  const form = document.getElementById("resetForm");
  const alert = document.getElementById("alert");
  const passwordInput = document.getElementById("password");
  const confirmInput = document.getElementById("password_confirmation");
  const passwordError = document.getElementById("passwordError");
  const confirmError = document.getElementById("confirmError");
  const submitBtn = document.getElementById("submitBtn");

  function showAlert(message, type) {
    alert.textContent = message;
    alert.className = `alert alert-${type} show`;
    alert.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }

  function hideAlert() {
    alert.className = "alert";
  }

  function showFieldError(element, errorElement, message) {
    element.classList.add("error");
    errorElement.textContent = message;
    errorElement.classList.add("show");
  }

  function clearFieldError(element, errorElement) {
    element.classList.remove("error");
    errorElement.textContent = "";
    errorElement.classList.remove("show");
  }

  function clearAllErrors() {
    hideAlert();
    clearFieldError(passwordInput, passwordError);
    clearFieldError(confirmInput, confirmError);
  }

  // Clear errors on input
  passwordInput.addEventListener("input", () => {
    clearFieldError(passwordInput, passwordError);
    hideAlert();
  });

  confirmInput.addEventListener("input", () => {
    clearFieldError(confirmInput, confirmError);
    hideAlert();
  });

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    clearAllErrors();

    const password = passwordInput.value;
    const passwordConfirmation = confirmInput.value;

    // Client-side validation
    let hasError = false;

    if (password.length < 8) {
      showFieldError(passwordInput, passwordError, "Password must be at least 8 characters");
      hasError = true;
    }

    if (password !== passwordConfirmation) {
      showFieldError(confirmInput, confirmError, "Passwords do not match");
      hasError = true;
    }

    if (hasError) {
      return;
    }

    const data = {
      token: "{{ $token }}",
      email: "{{ $email ?? '' }}",
      password: password,
      password_confirmation: passwordConfirmation,
    };

    // Disable button and show loading state
    submitBtn.disabled = true;
    submitBtn.textContent = "Resetting...";

    try {
      const res = await fetch("/api/auth/reset-password", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      });

      const result = await res.json();

      if (!res.ok) {
        showAlert(result.message || "An error occurred. Please try again.", "error");
        submitBtn.disabled = false;
        submitBtn.textContent = "Reset password";
        return;
      }

      showAlert("Password reset successful! You can now log in with your new password.", "success");
      form.reset();
      form.hidden = true;
      document.querySelector('.subtitle').hidden = true;

      
      // Optionally redirect after success
      setTimeout(() => {
        // window.location.href = "/login";
      }, 2000);

    } catch (error) {
      showAlert("Network error. Please check your connection and try again.", "error");
      submitBtn.disabled = false;
      submitBtn.textContent = "Reset password";
    }
  });
</script>

</html>