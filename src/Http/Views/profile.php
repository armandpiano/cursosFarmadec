<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Farmadec LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-body text-center">
                        <h1>Mi Perfil</h1>
                        <p class="text-muted">Información del usuario</p>
                        
                        <div class="mt-4">
                            <h3><?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?></h3>
                            <p class="text-muted"><?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?></p>
                            <p class="badge bg-primary"><?php echo htmlspecialchars($_SESSION['user_role'] ?? 'user'); ?></p>
                        </div>
                        
                        <div class="mt-4">
                            <a href="<?php echo url('app'); ?>" class="btn btn-primary">
                                <i class="bi bi-house"></i> Volver a Mis Cursos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
