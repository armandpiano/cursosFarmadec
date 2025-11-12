<?php ob_start(); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="display-5 fw-bold">Gestión de Cursos</h1>
            <p class="text-muted">Crear, editar y eliminar cursos</p>
        </div>
        <div class="col-auto">
            <a href="<?php echo url('admin'); ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver al Panel
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Listado de Cursos</h5>
            <p class="text-muted">Total: <?php echo count($courses); ?> cursos</p>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Slug</th>
                            <th>Estado</th>
                            <th>Fecha Creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?php echo $course->getId(); ?></td>
                            <td><?php echo htmlspecialchars($course->getTitle()); ?></td>
                            <td><code><?php echo htmlspecialchars($course->getSlug()); ?></code></td>
                            <td>
                                <span class="badge <?php echo $course->isActive() ? 'bg-success' : 'bg-secondary'; ?>">
                                    <?php echo $course->isActive() ? 'Activo' : 'Inactivo'; ?>
                                </span>
                            </td>
                            <td><?php echo $course->getCreatedAt(); ?></td>
                            <td>
                                <a href="<?php echo url('course/' . $course->getId() . '/modules'); ?>" 
                                   class="btn btn-sm btn-info" target="_blank">
                                    <i class="bi bi-eye"></i> Ver
                                </a>
                                <button class="btn btn-sm btn-warning" onclick="alert('Función de edición en desarrollo')">
                                    <i class="bi bi-pencil"></i> Editar
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> 
        <strong>Nota:</strong> Para agregar o editar cursos, usa directamente la base de datos o extiende este panel con formularios CRUD completos.
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layout.php'; ?>
