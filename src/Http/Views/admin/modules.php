<?php ob_start(); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="display-5 fw-bold">Gestión de Módulos</h1>
            <p class="text-muted">Ver módulos por curso</p>
        </div>
        <div class="col-auto">
            <a href="<?php echo url('admin'); ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver al Panel
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Módulos por Curso</h5>
            
            <?php 
            $courseGroups = [];
            foreach ($modules as $module) {
                $courseGroups[$module->getCourseId()][] = $module;
            }
            ?>
            
            <?php foreach ($courseGroups as $courseId => $courseModules): ?>
                <?php 
                $course = array_filter($courses, function($c) use ($courseId) {
                    return $c->getId() === $courseId;
                });
                $course = reset($course);
                ?>
                
                <div class="mb-4">
                    <h4 class="text-primary"><?php echo $course ? htmlspecialchars($course->getTitle()) : 'Curso ID ' . $courseId; ?></h4>
                    
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Número</th>
                                <th>Título</th>
                                <th>Posición</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courseModules as $module): ?>
                            <tr>
                                <td><?php echo $module->getId(); ?></td>
                                <td><?php echo $module->getNumber(); ?></td>
                                <td><?php echo htmlspecialchars($module->getTitle()); ?></td>
                                <td><?php echo $module->getPosition(); ?></td>
                                <td>
                                    <span class="badge <?php echo $module->isActive() ? 'bg-success' : 'bg-secondary'; ?>">
                                        <?php echo $module->isActive() ? 'Activo' : 'Inactivo'; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo url('module/' . $module->getId()); ?>" 
                                       class="btn btn-sm btn-info" target="_blank">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layout.php'; ?>
