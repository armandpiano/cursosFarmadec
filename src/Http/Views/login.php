<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Farmadec CAPACITACIÓN</title>
    <link href="img/logo/icon.webp" rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-image: url(img/fondo.jpg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #D5F1FF;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
            max-width: 450px;
            width: 100%;
        }
        .logo {
            font-size: 48px;
            color: #667eea;
            margin-bottom: 20px;
        }
        h1 { 
            color: #333; 
            font-weight: bold; 
        }
        .subtitle { 
            color: #666; 
            margin-bottom: 30px; 
        }
        .divider {
            margin: 20px 0;
            text-align: center;
            position: relative;
        }
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #dee2e6;
        }
        .divider span {
            padding: 0 15px;
            color: #6c757d;
            position: relative;
        }
        .form-floating {
            margin-bottom: 15px;
        }
        .btn-google {
            width: 100%;
            background: #fff;
            color: #757575;
            border: 1px solid #ddd;
            padding: 10px 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-google:hover {
            background: #f8f9fa;
            border-color: #ccc;
            color: #555;
        }
        .btn-primary {
            /*background: linear-gradient(135deg, #667eea 0%, #004186 100%);*/
            background: #004186;
            border: none;
        }
        .btn-primary:hover {
            /*background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);*/
            background: #004186d0;
            border: none;
        }
        div[role="button"][aria-labelledby="button-label"] {
            background-color: transparent !important;
            box-shadow: none !important;
        }
        .footerLogin{
            color:#6c757d;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center">
            <div class="logo">
                <img src="img/logo/logo.png" style="width:25%;">
            </div>
            <h1 class="h4 text-gray-900 font-weight-bold bienvenidafarmadec">Farma DEC</h1>
            <p class="subtitle">Iniciar Sesión</p>
        </div>

        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($_SESSION['login_error']); unset($_SESSION['login_error']); ?>
            </div>
        <?php endif; ?>

        <!-- Google OAuth Button -->
        <div id="g_id_onload"
             data-client_id="<?php 
                 $config = @include(__DIR__ . '/../../Config/google.local.php');
                 echo $config ? $config['client_id'] : 'YOUR_CLIENT_ID';
             ?>"
             data-callback="handleCredentialResponse">
        </div>
        <div class="g_id_signin" data-type="standard" data-size="large" data-theme="outline" data-text="signin_with" data-shape="rectangular" data-logo_alignment="left" style="margin-bottom: 20px;"></div>

        <div class="divider">
            <span>o continúa con email</span>
        </div>

        <!-- Login Form -->
        <form method="POST" action="<?php echo url('auth/login'); ?>">
            <div class="form-floating">
                <input type="email" 
                       class="form-control" 
                       id="email" 
                       name="email" 
                       placeholder="correo@ejemplo.com" 
                       required>
                <label for="email"><i class="bi bi-envelope"></i> Correo electrónico</label>
            </div>

            <div class="form-floating">
                <input type="password" 
                       class="form-control" 
                       id="password" 
                       name="password" 
                       placeholder="Contraseña" 
                       required>
                <label for="password"><i class="bi bi-lock"></i> Contraseña</label>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">
                <i class="bi bi-box-arrow-in-right"></i>ENTRAR
            </button>
        </form>

        <div class="text-center">
            <p class="mb-3 footerLogin">¿No tienes cuenta? <br>Accede con tu cuenta de Google o <a href="<?php echo url('register'); ?>" class="text-decoration-none">Regístrate aquí</a></p>
        </div>
    </div>

    <!-- Google OAuth Script -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    
    <!-- Error Display -->
    <div id="error-message" class="alert alert-danger mt-3" style="display:none;"></div>

    <script>
        function handleCredentialResponse(response) {
            fetch('/cursosFarmadec/google-callback.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_token: response.credential })
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    document.getElementById('error-message').textContent = data.message || 'Error al iniciar sesión';
                    document.getElementById('error-message').style.display = 'block';
                }
            })
            .catch(function(error) {
                document.getElementById('error-message').textContent = 'Error de conexión';
                document.getElementById('error-message').style.display = 'block';
            });
        }
    </script>
</body>
</html>
