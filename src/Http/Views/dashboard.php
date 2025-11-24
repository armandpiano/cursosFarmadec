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

    .tituloCursos {
        color: #004186;
        font-size: 4em;
        text-align: center;
    }
    .subtituloCursos{
        color: #983986 !important;
        text-align: center;
        font-size: 2em;
        font-weight: 700;
    }
</style>

<div class="inline-layout-wrapper mt-3">
    <div class="inline-layout-sidebar">
        <div class="sidebar-profile-menu card">
            <div class="card-body">
                <div class="text-muted small mb-3 fw-semibold">Mi perfil y cursos</div>
                <div class="list-group">
                    <a href="<?php echo url('profile'); ?>"
                       class="list-group-item list-group-item-action <?php echo $currentUrl === 'profile' ? 'active' : ''; ?>">
                        <i class="bi bi-person"></i> Mi Perfil
                    </a>
                    <a href="<?php echo url('app'); ?>"
                       class="list-group-item list-group-item-action <?php echo $currentUrl === 'app' || strpos($currentUrl, 'course') !== false ? 'active' : ''; ?>">
                        <i class="bi bi-book"></i> Cursos
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="inline-layout-main">
        <div class="container">
            <div class="row mb-4">
                <div class="col">
                    <h1 class="display-5 fw-bold tituloCursos">MIS CURSOS</h1>
                    <p class="text-muted subtituloCursos">Aquí comienza tu formación: el camino hacia la exigencia logística</p>
                </div>
            </div>

            <div class="row g-4">
                <?php if (empty($courses)): ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> No hay cursos disponibles en este momento.
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($courses as $course): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100">
                            <?php if ($course['image_url']): ?>
                            <img src="<?php echo htmlspecialchars($course['image_url']); ?>"
                                 class="card-img-top" alt="<?php echo htmlspecialchars($course['title']); ?>"
                                 style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                            <div class="bg-primary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="bi bi-book" style="font-size: 64px;"></i>
                            </div>
                            <?php endif; ?>

                            <div class="card-body d-flex flex-column">
                                <div class="mb-3">
                                    <!-- Status badge -->
                                    <?php  if ($course['status'] === 'in_progress'): ?>
                                        <span class="badge bg-warning mb-2">
                                            <i class="bi bi-clock"></i> En Progreso
                                        </span>
                                    <?php elseif ($course['status'] === 'completed'): ?>
                                        <span class="badge bg-success mb-2">
                                            <i class="bi bi-check-circle"></i> Completado
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary mb-2">
                                            <i class="bi bi-circle"></i> No Iniciado
                                        </span>
                                    <?php endif; ?>

                                    <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                                    <p class="card-text text-muted"><?php echo htmlspecialchars($course['description']); ?></p>
                                </div>

                                <?php if ($course['status'] === 'in_progress'): ?>
                                <div class="mb-3 mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-muted">Progreso del curso</small>
                                        <small class="fw-bold text-primary"><?php echo $course['progress_percent']; ?>% completado</small>
                                    </div>
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated"
                                             role="progressbar"
                                             style="width: <?php echo $course['progress_percent']; ?>%;"
                                             aria-valuenow="<?php echo $course['progress_percent']; ?>"
                                             aria-valuemin="0" aria-valuemax="100">
                                            <?php if ($course['progress_percent'] > 15): ?>
                                                <?php echo $course['progress_percent']; ?>%
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php elseif ($course['status'] === 'completed'): ?>
                                <div class="mb-3 mt-auto">
                                    <div class="text-center">
                                        <i class="bi bi-check-circle-fill text-success" style="font-size: 24px;"></i>
                                        <p class="text-success fw-bold mb-0 mt-1">Curso Completado</p>
                                        <small class="text-muted">¡Felicitaciones!</small>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php
                                $btnClass = 'btn-primary';
                                $btnText = 'Iniciar curso';
                                $btnDisabled = '';
                                $btnIcon = 'bi-play-circle';

                                if ($course['status'] === 'in_progress') {
                                    $btnText = 'Continuar curso';
                                    $btnIcon = 'bi-arrow-right-circle';
                                } elseif ($course['status'] === 'completed') {
                                    $btnClass = 'btn-success';
                                    $btnText = 'Curso Completado';
                                    $btnIcon = 'bi-check-circle';
                                    $btnDisabled = 'disabled';
                                }
                                ?>

                                <a href="<?php echo url('course/' . $course['id'] . '/modules'); ?>"
                                   class="btn <?php echo $btnClass; ?> btn-lg mt-auto" <?php echo $btnDisabled; ?>>
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
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/layout.php'; ?>
