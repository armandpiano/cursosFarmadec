<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña - Farmadec</title>
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
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-3">
            <img src="img/logo/logo.png" style="width:20%;" alt="Farmadec">
            <h1 class="h4 mt-3">Recuperar contraseña</h1>
            <p class="text-muted">Ingresa tu correo para enviarte instrucciones.</p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo url('forgot-password'); ?>">
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="correo@ejemplo.com" required>
                <label for="email"><i class="bi bi-envelope"></i> Correo electrónico</label>
            </div>
            <button type="submit" class="btn btn-primary w-100">Enviar instrucciones</button>
        </form>

        <div class="mt-3 text-center">
            <a href="<?php echo url(); ?>" class="text-decoration-none"><i class="bi bi-arrow-left"></i> Volver a iniciar sesión</a>
        </div>
    </div>
</body>
</html>
