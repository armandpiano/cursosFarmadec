<?php ob_start(); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="display-5 fw-bold">Gestión de Usuarios</h1>
            <p class="text-muted">Administrar roles y usuarios del sistema</p>
        </div>
        <div class="col-auto">
            <a href="<?php echo url('admin'); ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver al Panel
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Listado de Usuarios</h5>
            <p class="text-muted">Total: <?php echo count($users); ?> usuarios registrados</p>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Avatar</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Fecha Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user->getId(); ?></td>
                            <td>
                                <img src="<?php echo $user->getAvatarUrl() ?: 'https://via.placeholder.com/40'; ?>" 
                                     alt="Avatar" class="rounded-circle" width="40" height="40">
                            </td>
                            <td><?php echo htmlspecialchars($user->getName()); ?></td>
                            <td><?php echo htmlspecialchars($user->getEmail()); ?></td>
                            <td>
                                <span class="badge <?php echo $user->isAdmin() ? 'bg-danger' : 'bg-primary'; ?>">
                                    <?php echo strtoupper($user->getRole()); ?>
                                </span>
                            </td>
                            <td><?php echo $user->getCreatedAt(); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="alert alert-warning mt-4">
        <i class="bi bi-exclamation-triangle"></i> 
        <strong>Importante:</strong> Los cambios de rol deben realizarse directamente en la base de datos para mayor seguridad.
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layout.php'; ?>
