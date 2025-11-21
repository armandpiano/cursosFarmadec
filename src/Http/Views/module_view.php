<?php ob_start(); ?>
<style>
/* Estilos específicos para el layout de 2 columnas */
.module-layout {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    margin-top: 20px;
    width: 100%;
}

.sidebar-modules {
    flex: 0 0 350px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    max-height: 80vh;
    overflow-y: auto;
}

.main-content {
    flex: 1;
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.module-item {
    border-bottom: 1px solid #e9ecef;
    padding: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.module-item:hover {
    background-color: #f8f9fa;
}

.module-item.active {
    background-color: #e3f2fd;
    border-left: 4px solid var(--primary-color);
}

.module-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 10px;
}

.module-title {
    font-weight: 600;
    color: #495057;
    margin: 0;
    flex: 1;
}

.module-status {
    margin-left: 10px;
}

.capsule-item {
    padding: 8px 0;
    border-bottom: 1px solid #f1f3f4;
}

.capsule-item:last-child {
    border-bottom: none;
}

.capsule-radio {
    margin-right: 10px;
}

.capsule-title {
    font-size: 0.9rem;
    color: #6c757d;
    margin: 0;
}

.capsule-progress {
    font-size: 0.8rem;
    color: #28a745;
    margin-left: auto;
}

.capsule-description,
.capsule-media {
    width: 100%;
}

.capsule-description {
    margin: 0 auto;
}

.progress-sidebar {
    height: 8px;
    margin-top: 5px;
}

.progress-sidebar .progress-bar {
    height: 100%;
    border-radius: 4px;
}

/* Responsive */
@media (max-width: 768px) {
    .module-layout {
        flex-direction: column;
    }
    
    .sidebar-modules {
        flex: none;
        max-height: 300px;
    }
}
</style>

<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo url('app'); ?>">Mis Cursos</a></li>
                <li class="breadcrumb-item"><a href="<?php echo url('course/' . $module->course_id . '/modules'); ?>">
                    <?php echo htmlspecialchars($course->getTitle()); ?>
                </a></li>
                <li class="breadcrumb-item active">Módulo <?php echo $module->number; ?></li>
            </ol>
        </nav>
    </div>

    <!-- Layout de 2 columnas -->
    <div class="module-layout">
        <!-- Columna izquierda: Navegación de módulos y cápsulas -->
        <div class="sidebar-modules">
            <div class="p-3">
                <h5 class="mb-3">
                    <i class="bi bi-list-task"></i> Progreso del Curso
                </h5>
                
                <?php foreach ($allCourseModules as $courseModule): ?>
                <div class="module-item <?php echo $courseModule['id'] === $module->id ? 'active' : ''; ?>" 
                     data-module-id="<?php echo $courseModule['id']; ?>">
                    
                    <div class="module-header">
                        <h6 class="module-title">
                            Módulo <?php echo $courseModule['number']; ?>: <?php echo htmlspecialchars($courseModule['title']); ?>
                        </h6>
                        <div class="module-status">
                            <?php if ($courseModule['status'] === 'completed'): ?>
                                <i class="bi bi-check-circle-fill text-success" title="Completado"></i>
                            <?php elseif ($courseModule['status'] === 'in_progress'): ?>
                                <i class="bi bi-clock-fill text-warning" title="En progreso"></i>
                            <?php else: ?>
                                <i class="bi bi-circle text-muted" title="No iniciado"></i>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if ($courseModule['status'] === 'in_progress'): ?>
                    <div class="progress-sidebar">
                        <div class="progress">
                            <div class="progress-bar" style="width: <?php echo $courseModule['percent']; ?>%;"></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Cápsulas del módulo (en dropdown) -->
                    <?php 
                    // Obtener cápsulas de este módulo
                    $moduleCapsules = [];
                    if ($courseModule['id'] === $module->id) {
                        $moduleCapsules = $module->capsules;
                    } else {
                        // Para otros módulos, necesitamos obtener sus cápsulas
                        // Aquí se podría implementar una llamada al servicio
                    }
                    ?>
                    
                    <?php if (!empty($moduleCapsules) || $courseModule['id'] === $module->id): ?>
                    <div class="mt-2">
                        <button class="btn btn-sm btn-outline-primary w-100 text-start" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#capsules-<?php echo $courseModule['id']; ?>"
                                aria-expanded="<?php echo $courseModule['id'] === $module->id ? 'true' : 'false'; ?>">
                            <i class="bi bi-chevron-down"></i> Cápsulas
                        </button>
                        
                        <div class="collapse <?php echo $courseModule['id'] === $module->id ? 'show' : ''; ?>" 
                             id="capsules-<?php echo $courseModule['id']; ?>">
                            <div class="mt-2">
                                <?php foreach ($moduleCapsules as $capsule): ?>
                                <div class="capsule-item">
                                    <div class="form-check">
                                        <input class="form-check-input capsule-radio" 
                                               type="radio" 
                                               name="capsule-nav" 
                                               id="capsule-<?php echo $capsule['id']; ?>"
                                               <?php echo $courseModule['id'] === $module->id ? 'checked' : ''; ?>>
                                        <label class="form-check-label w-100 d-flex align-items-center" 
                                               for="capsule-<?php echo $capsule['id']; ?>">
                                            <span class="capsule-title">
                                                Cápsula <?php echo $capsule['number']; ?>: <?php echo htmlspecialchars($capsule['title']); ?>
                                            </span>
                                            <?php if ($courseModule['id'] === $module->id): ?>
                                                <span class="capsule-progress">
                                                    <i class="bi bi-check-circle"></i>
                                                </span>
                                            <?php endif; ?>
                                        </label>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                
                                <!-- Sección de examen en la navegación lateral -->
                                <?php if ($courseModule['id'] === $module->id && !empty($moduleCapsules)): ?>
                                <div class="capsule-item mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="exam-nav" 
                                               id="exam-nav"
                                               data-target="exam-section">
                                        <label class="form-check-label w-100 d-flex align-items-center" 
                                               for="exam-nav">
                                            <span class="capsule-title fw-bold">
                                                <i class="bi bi-clipboard-check text-warning"></i>
                                                Examen del Módulo
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Columna derecha: Contenido del módulo -->
        <div class="main-content">
            <div class="p-4">
                <!-- Header del módulo -->
                <div class="mb-4">
                    <h2 class="mb-2">
                        <span class="badge bg-primary me-2">Módulo <?php echo $module->number; ?></span>
                        <?php echo htmlspecialchars($module->title); ?>
                    </h2>
                    <p class="text-muted lead"><?php echo htmlspecialchars($module->description); ?></p>
                </div>

                <!-- Contenido de las cápsulas -->
                <?php if (!empty($module->capsules)): ?>
                <div id="capsule-container">
                    <!-- Porcentaje de progreso arriba de las cápsulas -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>Progreso del Módulo</strong>
                                <span class="badge bg-primary fs-6"><?php echo $module->percent; ?>% completado</span>
                            </div>
                            <div class="progress" style="height: 20px;">
                                <div id="module-progress-bar" 
                                     class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" 
                                     style="width: <?php echo $module->percent; ?>%;">
                                    <?php if ($module->percent > 10): ?>
                                        <?php echo $module->percent; ?>%
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php foreach ($module->capsules as $index => $capsule): ?>
                    <div class="capsule-page mb-4" data-page="<?php echo $index; ?>" 
                         style="display: <?php echo $index === 0 ? 'block' : 'none'; ?>;">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-3">
                                    <i class="bi bi-play-circle"></i>
                                    Cápsula <?php echo $capsule['number']; ?>: <?php echo htmlspecialchars($capsule['title']); ?>
                                </h4>
                                
                                <!-- Texto descriptivo arriba del video -->
                                <?php if ($capsule['description']): ?>
                                <div class="mb-4 p-4 bg-light rounded capsule-description">
                                    <div class="capsule-content">
                                        <?php echo $capsule['description']; ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if ($capsule['video_url']): ?>
                                <div class="ratio ratio-16x9 mb-4 capsule-media">
                                    <video controls class="rounded" id="video-<?php echo $capsule['id']; ?>" style="width: 100%;">
                                        <source src="<?php echo htmlspecialchars($capsule['video_url']); ?>" type="video/mp4">
                                        Tu navegador no soporta video HTML5.
                                    </video>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Navegación entre cápsulas -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <button class="btn btn-secondary prev-capsule" <?php echo $index === 0 ? 'disabled' : ''; ?>>
                                        <i class="bi bi-arrow-left"></i> Anterior
                                    </button>
                                    
                                    <span class="text-muted">
                                        <i class="bi bi-list-ol"></i>
                                        Cápsula <?php echo $index + 1; ?> de <?php echo count($module->capsules); ?>
                                    </span>
                                    
                                    <button class="btn btn-primary next-capsule" 
                                            data-capsule-id="<?php echo $capsule['id']; ?>"
                                            data-module-id="<?php echo $module->id; ?>"
                                            <?php echo $index === count($module->capsules) - 1 ? 'disabled' : ''; ?>>
                                        Siguiente <i class="bi bi-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Sección de examen (página independiente) -->
                <div id="exam-section" class="card" style="display: none;">
                    <div class="card-body text-center p-5">
                        <h3 class="card-title text-success mb-3">
                            <i class="bi bi-clipboard-check"></i> Evaluación del Módulo
                        </h3>
                        <p class="text-muted mb-4">
                            ¡Felicitaciones! Has completado todas las cápsulas de este módulo.
                        </p>
                        <button id="start-exam-btn" class="btn btn-success btn-lg">
                            <i class="bi bi-play-fill"></i> Iniciar Evaluación
                        </button>
                    </div>
                </div>
                <?php else: ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Este módulo no tiene cápsulas disponibles.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
let currentPage = 0;
const totalPages = <?php echo count($module->capsules); ?>;
const moduleId = <?php echo $module->id; ?>;
const baseUrl = '<?php echo url(); ?>';

function pauseAllVideos() {
    document.querySelectorAll('.capsule-page video').forEach(function(video) {
        video.pause();
    });
}

function showCapsulePage(targetPage) {
    const currentCapsule = document.querySelector('[data-page="' + currentPage + '"]');
    if (currentCapsule) {
        pauseAllVideos();
        currentCapsule.style.display = 'none';
    }

    currentPage = targetPage;

    const nextCapsule = document.querySelector('[data-page="' + currentPage + '"]');
    if (nextCapsule) {
        nextCapsule.style.display = 'block';
    }

    updateCapsuleNavigation();
    document.getElementById('exam-section').style.display = currentPage === totalPages - 1 ? 'block' : 'none';
}

// Navegación entre cápsulas
document.querySelectorAll('.next-capsule').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const capsuleId = this.dataset.capsuleId;
        markCapsuleViewed(capsuleId, moduleId);

        if (currentPage < totalPages - 1) {
            showCapsulePage(currentPage + 1);
        }
    });
});

document.querySelectorAll('.prev-capsule').forEach(function(btn) {
    btn.addEventListener('click', function() {
        if (currentPage > 0) {
            showCapsulePage(currentPage - 1);
        }
    });
});

// Función para marcar cápsula como vista
function markCapsuleViewed(capsuleId, moduleId) {
    fetch(baseUrl + 'api/progress/capsule', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ capsule_id: capsuleId, module_id: moduleId })
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success && data.progress) {
            // Actualizar barra de progreso
            document.getElementById('module-progress-bar').style.width = data.progress.percent + '%';
            document.getElementById('module-progress-bar').textContent = data.progress.percent > 10 ? data.progress.percent + '%' : '';
            
            // Actualizar badge de progreso
            const badge = document.querySelector('.badge.bg-primary.fs-6');
            if (badge) {
                badge.textContent = data.progress.percent + '% completado';
            }
        }
    });
}

// Actualizar navegación de cápsulas en sidebar
function updateCapsuleNavigation() {
    const capsules = document.querySelectorAll('.capsule-radio');
    capsules.forEach(function(capsule, index) {
        capsule.checked = index === currentPage;
    });
}

// Iniciar examen
document.getElementById('start-exam-btn')?.addEventListener('click', function() {
    pauseAllVideos();
    loadExam(moduleId);
});

// Navegación directa a cápsulas desde sidebar
document.querySelectorAll('.capsule-radio').forEach(function(radio) {
    radio.addEventListener('change', function() {
        const capsuleIndex = Array.from(document.querySelectorAll('.capsule-radio')).indexOf(this);
        if (capsuleIndex >= 0) {
            showCapsulePage(capsuleIndex);
        }
    });
});

// Navegación directa al examen desde sidebar (CORREGIDA)
document.querySelectorAll('input[name="exam-nav"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        if (this.checked) {
            // Desmarcar todos los radio buttons de cápsulas
            document.querySelectorAll('.capsule-radio').forEach(function(capsuleRadio) {
                capsuleRadio.checked = false;
            });
            
            // Ocultar todas las cápsulas
            document.querySelectorAll('.capsule-page').forEach(function(page) {
                page.style.display = 'none';
            });

            // Mostrar sección de examen
            pauseAllVideos();
            document.getElementById('exam-section').style.display = 'block';
        }
    });
});



function loadExam(moduleId) {
    fetch(baseUrl + 'api/exam/module/' + moduleId)
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success) {
                showExam(data.exam);
            }
        })
        .catch(function(error) {
            console.error('Error loading exam:', error);
        });
}

function showExam(exam) {
    let html = '<div class="p-4"><h3 class="mb-4"><i class="bi bi-clipboard-check"></i> Examen del Módulo</h3>';
    html += '<p class="text-muted">Responde todas las preguntas. Puntaje mínimo: ' + exam.pass_score + '%</p>';
    html += '<form id="exam-form">';
    
    exam.questions.forEach(function(q, index) {
        html += '<div class="card mb-3"><div class="card-body">';
        html += '<h5 class="mb-3">Pregunta ' + (index + 1) + '</h5>';
        html += '<p class="mb-3">' + q.text + '</p>';
        
        q.options.forEach(function(opt) {
            const inputType = q.type === 'multiple_choice' ? 'checkbox' : 'radio';
            const name = q.type === 'multiple_choice' ? 'q' + q.id + '[]' : 'q' + q.id;
            html += '<div class="form-check mb-2">';
            html += '<input class="form-check-input" type="' + inputType + '" name="' + name + '" value="' + opt.id + '" id="opt' + opt.id + '">';
            html += '<label class="form-check-label" for="opt' + opt.id + '">' + opt.text + '</label>';
            html += '</div>';
        });
        
        html += '</div></div>';
    });
    
    html += '<div class="text-center"><button type="submit" class="btn btn-success btn-lg"><i class="bi bi-send"></i> Enviar Respuestas</button></div>';
    html += '</form></div>';
    
    document.querySelector('.main-content .p-4').innerHTML = html;
    
    document.getElementById('exam-form').addEventListener('submit', function(e) {
        e.preventDefault();
        submitExam(exam.id, new FormData(this));
    });
}

function submitExam(examId, formData) {
    const answers = {};
    const moduleId = <?php echo $module->id; ?>;
    
    // Convertir FormData a objeto
    for (let [key, value] of formData.entries()) {
        if (!answers[key]) answers[key] = [];
        answers[key].push(value);
    }
    
    // Simplificar arrays de una sola respuesta
    Object.keys(answers).forEach(function(key) {
        if (answers[key].length === 1) answers[key] = answers[key][0];
    });
    
    console.log('Sending exam submission:', { 
        exam_id: examId, 
        answers: answers, 
        module_id: moduleId 
    });
    
    fetch(baseUrl + 'api/exam/submit', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ 
            exam_id: examId, 
            answers: answers, 
            module_id: moduleId 
        })
    })
    .then(function(res) { 
        console.log('Response status:', res.status);
        return res.json(); 
    })
    .then(function(data) {
        console.log('Response data:', data);
        if (data.success) {
            showExamResult(data.result || data);
        } else {
            showError(data.message || 'Error al enviar el examen');
        }
    })
    .catch(function(error) {
        console.error('Error submitting exam:', error);
        showError('Error de conexión al enviar el examen');
    });
}

function showError(message) {
    let html = '<div class="alert alert-danger" role="alert">';
    html += '<i class="bi bi-exclamation-triangle"></i> ' + message;
    html += '</div>';
    html += '<div class="text-center">';
    html += '<button class="btn btn-primary" onclick="location.reload()">Reintentar</button>';
    html += '</div>';
    document.querySelector('.main-content .p-4').innerHTML = html;
}

function showExamResult(result) {
    let html = '<div class="text-center p-5">';
    
    if (result.passed !== undefined) {
        html += '<h3 class="mb-4">' + (result.passed ? '<i class="bi bi-check-circle text-success"></i> ¡Aprobado!' : '<i class="bi bi-x-circle text-danger"></i> No Aprobado') + '</h3>';
        html += '<p class="lead">Tu puntaje: ' + result.score + '%</p>';
        html += '<p>Puntaje mínimo requerido: ' + (result.pass_score || 70) + '%</p>';
    } else {
        html += '<h3 class="mb-4"><i class="bi bi-check-circle text-success"></i> Examen Enviado</h3>';
        html += '<p class="lead">El examen se ha enviado correctamente.</p>';
    }
    
    if (result.passed) {
        html += '<div class="alert alert-success mb-4"><i class="bi bi-trophy"></i> ¡Felicitaciones! Has completado exitosamente este módulo.</div>';
        html += '<a href="<?php echo url('course/' . $module->course_id . '/modules'); ?>" class="btn btn-primary btn-lg">Continuar con el Siguiente Módulo</a>';
    } else if (result.passed !== undefined) {
        html += '<div class="alert alert-warning mb-4"><i class="bi bi-arrow-repeat"></i> Puedes intentar nuevamente el examen.</div>';
        html += '<button class="btn btn-outline-primary btn-lg" onclick="loadExam(' + moduleId + ')">Reintentar Examen</button>';
    } else {
        html += '<button class="btn btn-primary btn-lg" onclick="loadExam(' + moduleId + ')">Ver Resultados</button>';
    }
    
    html += '</div>';
    document.querySelector('.main-content .p-4').innerHTML = html;
}
</script>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/layout.php'; ?>
