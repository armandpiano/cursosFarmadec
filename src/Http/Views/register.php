<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Farmadec LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-card {
            background: white;
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
            background: white;
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
        }
        .btn-google:hover {
            background: #f8f9fa;
            border-color: #ccc;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="text-center">
            <div class="logo">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <h1>Farmadec LMS</h1>
            <p class="subtitle">Crear nueva cuenta</p>
        </div>

        <?php if (isset($_SESSION['register_error'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($_SESSION['register_error']); unset($_SESSION['register_error']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo url('auth/register'); ?>">
            <div class="form-floating">
                <input type="text" 
                       class="form-control" 
                       id="name" 
                       name="name" 
                       placeholder="Nombre completo" 
                       value="<?php echo htmlspecialchars($_SESSION['register_name'] ?? ''); ?>" 
                       required>
                <label for="name"><i class="bi bi-person"></i> Nombre completo</label>
            </div>

            <div class="form-floating">
                <input type="email" 
                       class="form-control" 
                       id="email" 
                       name="email" 
                       placeholder="correo@ejemplo.com" 
                       value="<?php echo htmlspecialchars($_SESSION['register_email'] ?? ''); ?>" 
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
                <label for="password"><i class="bi bi-lock"></i> Contraseña (mínimo 6 caracteres)</label>
            </div>

            <div class="form-floating">
                <input type="password" 
                       class="form-control" 
                       id="confirm_password" 
                       name="confirm_password" 
                       placeholder="Confirmar contraseña" 
                       required>
                <label for="confirm_password"><i class="bi bi-lock-fill"></i> Confirmar contraseña</label>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">
                <i class="bi bi-person-plus"></i> Crear cuenta
            </button>
        </form>

        <div class="divider">
            <span>ó</span>
        </div>

        <div class="text-center">
            <p class="mb-3">¿Ya tienes cuenta? <a href="<?php echo url('login'); ?>" class="text-decoration-none">Inicia sesión</a></p>
        </div>
    </div>

    <script>
        // Validación de contraseñas en tiempo real
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword && confirmPassword.length > 0) {
                this.setCustomValidity('Las contraseñas no coinciden');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</body>
</html>
