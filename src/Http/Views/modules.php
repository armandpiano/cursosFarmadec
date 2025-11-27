<?php ob_start(); ?>
<style>
.sidebar {
    display: none !important;
}
.main-content {
    margin-left: 0 !important;
}

.inline-layout-wrapper {
    display: flex;
    gap: 20px;
    align-items: flex-start;
}

.inline-layout-sidebar {
    flex: 0 0 320px;
}

.inline-layout-main {
    flex: 1;
}

.sidebar-profile-menu {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    margin-bottom: 14px;
}

.sidebar-profile-menu .card-body {
    padding: 16px;
}

.sidebar-profile-menu .list-group-item.active {
    background-color: #c240a8;
    border-color: #c240a8;
    color: #fff;
}

.sidebar-profile-menu .list-group-item {
    border: 1px solid #e9ecef;
}

.sidebar-profile-menu .list-group-item + .list-group-item {
    border-top: none;
}

@media (max-width: 768px) {
    .inline-layout-wrapper {
        flex-direction: column;
    }
    .inline-layout-sidebar,
    .inline-layout-main {
        width: 100%;
    }
}
</style>

<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo url('app'); ?>">Mis Cursos</a></li>
                <li class="breadcrumb-item active"><?php echo htmlspecialchars($course->getTitle()); ?></li>
            </ol>
        </nav>
    </div>

    <div class="inline-layout-wrapper mt-3">
        <div class="inline-layout-sidebar">
            <div class="sidebar-profile-menu card">
                <div class="card-body">
                    <div class="text-muted small mb-3 fw-semibold">Navegación del curso</div>
                    <div class="list-group">
                        <a href="<?php echo url('profile'); ?>"
                           class="list-group-item list-group-item-action">
                           <i class="bi bi-person"></i> Mi Perfil
                        </a>

                        <a href="<?php echo url('app'); ?>"
                           class="list-group-item list-group-item-action">
                           <i class="bi bi-book"></i> Cursos
                        </a>

                        <a href="<?php echo url('course/' . $course->getId() . '/modules'); ?>"
                           class="list-group-item list-group-item-action active">
                           <i class="bi bi-grid"></i> Módulos de <?php echo htmlspecialchars($course->getTitle()); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="inline-layout-main">
            <div class="container">
                <div class="row mb-4">
                    <div class="col">
                        <!-- Título prominente MÓDULOS -->
                        <div class="text-center mb-4">
                            <h1 class="display-4 fw-bold text-primary">MÓDULOS</h1>
                            <h2 class="h4 text-secondary mb-3"><?php echo htmlspecialchars($courseData['title']); ?></h2>
                            <p class="lead text-muted mb-4"><?php echo htmlspecialchars($courseData['description']); ?></p>

                            <!-- Progreso del curso después de la descripción -->
                            <?php if ($courseData['status'] !== 'not_started'): ?>
                            <div class="d-flex justify-content-center align-items-center mb-4">
                                <span class="badge bg-primary fs-5 me-3"><?php echo $courseData['progress_percent']; ?>% de avance</span>
                                <div class="progress" style="width: 300px; height: 15px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                         role="progressbar"
                                         style="width: <?php echo $courseData['progress_percent']; ?>%;"
                                         aria-valuenow="<?php echo $courseData['progress_percent']; ?>"
                                         aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <?php if (empty($modules)): ?>
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> Este curso no tiene módulos disponibles.
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($modules as $module): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100">
                                <?php if ($module['image_url']): ?>
                                <img src="<?php echo htmlspecialchars($module['image_url']); ?>"
                                     class="card-img-top" alt="<?php echo htmlspecialchars($module['title']); ?>"
                                     style="height: 180px; object-fit: cover;">
                                <?php else: ?>
                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 180px;">
                                    <i class="bi bi-journal-text" style="font-size: 48px;"></i>
                                </div>
                                <?php endif; ?>

                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title">
                                            <span class="badge bg-secondary me-2">Módulo <?php echo $module['number']; ?></span>
                                        </h5>
                                        <?php if ($module['status'] === 'completed'): ?>
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> Completado
                                        </span>
                                        <?php elseif ($module['status'] === 'in_progress'): ?>
                                        <span class="badge bg-warning">
                                            <i class="bi bi-clock"></i> En progreso
                                        </span>
                                        <?php else: ?>
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-circle"></i> No iniciado
                                        </span>
                                        <?php endif; ?>
                                    </div>

                                    <h6 class="fw-bold"><?php echo htmlspecialchars($module['title']); ?></h6>
                                    <p class="card-text text-muted small"><?php echo htmlspecialchars($module['description']); ?></p>

                                    <?php if ($module['status'] === 'in_progress'): ?>
                                    <div class="mb-3 mt-auto">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <small class="text-muted">Progreso del módulo</small>
                                            <small class="fw-bold text-primary"><?php echo $module['percent']; ?>%</small>
                                        </div>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar progress-bar-striped"
                                                 role="progressbar"
                                                 style="width: <?php echo $module['percent']; ?>%;">
                                            </div>
                                        </div>
                                    </div>
                                    <?php elseif ($module['status'] === 'completed'): ?>
                                    <div class="mb-3 mt-auto">
                                        <div class="text-center text-success">
                                            <i class="bi bi-check-circle-fill" style="font-size: 24px;"></i>
                                            <p class="mb-0 mt-1 small fw-bold">¡Módulo completado!</p>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <?php
                                    $btnClass = 'btn-primary';
                                    $btnText = 'Iniciar Módulo';
                                    $btnIcon = 'bi-play-circle';
                                    $btnDisabled = '';

                                    if ($module['status'] === 'in_progress') {
                                        $btnText = 'Continuar';
                                        $btnIcon = 'bi-arrow-right-circle';
                                    } elseif ($module['status'] === 'completed') {
                                        $btnText = 'Revisar';
                                        $btnIcon = 'bi-eye';
                                        $btnClass = 'btn-outline-success';
                                    }
                                    ?>

                                    <a href="<?php echo url('module/' . $module['id']); ?>"
                                       class="btn <?php echo $btnClass; ?> mt-auto" <?php echo $btnDisabled; ?>>
                                        <i class="<?php echo $btnIcon; ?>"></i>
                                        <?php echo $btnText; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/layout.php'; ?>
