<?php ob_start(); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="display-5 fw-bold">Panel de Administración</h1>
            <p class="text-muted">Gestión completa del LMS</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-book"></i> Total Cursos
                    </h5>
                    <h2 class="display-4"><?php echo $stats['total_courses']; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-people"></i> Total Usuarios
                    </h5>
                    <h2 class="display-4"><?php echo $stats['total_users']; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-check-circle"></i> Cursos Activos
                    </h5>
                    <h2 class="display-4"><?php echo $stats['active_courses']; ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-3">
            <a href="<?php echo url('admin/courses'); ?>" class="text-decoration-none">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-book display-1 text-primary"></i>
                        <h5 class="card-title mt-3">Gestionar Cursos</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?php echo url('admin/modules'); ?>" class="text-decoration-none">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-journal-text display-1 text-success"></i>
                        <h5 class="card-title mt-3">Gestionar Módulos</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?php echo url('admin/users'); ?>" class="text-decoration-none">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-people display-1 text-warning"></i>
                        <h5 class="card-title mt-3">Gestionar Usuarios</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?php echo url('app'); ?>" class="text-decoration-none">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-eye display-1 text-info"></i>
                        <h5 class="card-title mt-3">Ver como Usuario</h5>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Cursos Recientes</h5>
                    <ul class="list-group list-group-flush">
                        <?php foreach (array_slice($courses, 0, 5) as $course): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($course->getTitle()); ?>
                            <span class="badge <?php echo $course->isActive() ? 'bg-success' : 'bg-secondary'; ?>">
                                <?php echo $course->isActive() ? 'Activo' : 'Inactivo'; ?>
                            </span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Usuarios Recientes</h5>
                    <ul class="list-group list-group-flush">
                        <?php foreach (array_slice($users, 0, 5) as $user): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($user->getName()); ?>
                            <span class="badge <?php echo $user->isAdmin() ? 'bg-danger' : 'bg-primary'; ?>">
                                <?php echo $user->getRole(); ?>
                            </span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layout.php'; ?>
