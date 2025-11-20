<?php ob_start(); ?>
<div class="container">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo url('app'); ?>">Mis Cursos</a></li>
                    <?php if (isset($courseData['title'])): ?>
                    <li class="breadcrumb-item"><a href="<?php echo url('course/' . $courseData['id'] . '/modules'); ?>">
                        <?php echo htmlspecialchars($courseData['title']); ?>
                    </a></li>
                    <?php endif; ?>
                    <li class="breadcrumb-item active">Acceso Restringido</li>
                </ol>
            </nav>

            <div class="card border-warning">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="bi bi-lock-fill" style="font-size: 64px; color: #ffc107;"></i>
                    </div>
                    
                    <h2 class="card-title text-warning mb-3">
                        <i class="bi bi-shield-exclamation"></i> Módulo Bloqueado
                    </h2>
                    
                    <p class="card-text lead text-muted mb-4">
                        Este módulo requiere que completes los módulos anteriores antes de poder acceder a él.
                    </p>
                    
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-info-circle"></i>
                        <strong>Prerequisito no cumplido:</strong> Debes completar todos los módulos anteriores 
                        en secuencia para acceder a este contenido.
                    </div>
                    
                    <div class="d-grid gap-2 d-md-block mt-4">
                        <a href="<?php echo url('app'); ?>" class="btn btn-primary me-2">
                            <i class="bi bi-arrow-left"></i> Volver a Mis Cursos
                        </a>
                        <?php if (isset($courseData['id'])): ?>
                        <a href="<?php echo url('course/' . $courseData['id'] . '/modules'); ?>" class="btn btn-outline-primary">
                            <i class="bi bi-list-task"></i> Ver Módulos del Curso
                        </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mt-4 p-3 bg-light rounded">
                        <small class="text-muted">
                            <i class="bi bi-lightbulb"></i>
                            <strong>Consejo:</strong> Completa los módulos en orden para avanzar en tu aprendizaje 
                            y desbloquear nuevo contenido.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-redirect después de mostrar el mensaje
setTimeout(function() {
    <?php if (isset($courseData['id'])): ?>
    // Redirigir automáticamente a la lista de módulos después de 3 segundos
    setTimeout(function() {
        window.location.href = '<?php echo url('course/' . $courseData['id'] . '/modules'); ?>';
    }, 3000);
    <?php endif; ?>
}, 1000);
</script>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/layout.php'; ?>
