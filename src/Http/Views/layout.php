<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - ' : ''; ?>Farmadec LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo url('assets/css/custom.css'); ?>">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --sidebar-bg: #f8f9fa;
            --sidebar-width: 280px;
        }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0;
            padding: 0;
            background-color: #f5f7fa;
        }
        
        /* Layout principal */
        .main-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            border-right: 1px solid #dee2e6;
            padding: 20px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 20px;
        }
        
        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
            text-decoration: none;
            display: block;
        }
        
        .sidebar-brand:hover {
            color: var(--secondary-color);
        }
        
        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-nav li {
            margin: 0;
        }
        
        .sidebar-nav a {
            display: block;
            padding: 12px 20px;
            color: #495057;
            text-decoration: none;
            border-radius: 0;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background-color: rgba(102, 126, 234, 0.1);
            color: var(--primary-color);
            border-left-color: var(--primary-color);
        }
        
        .sidebar-nav i {
            margin-right: 10px;
            width: 20px;
        }
        
        /* Contenido principal */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Header superior */
        .top-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .top-header-left {
            display: flex;
            align-items: center;
        }
        
        .top-header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .user-name {
            font-weight: 500;
        }
        
        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            text-decoration: none;
            transform: translateY(-1px);
        }
        
        .mobile-toggle {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            margin-right: 15px;
            display: none;
        }
        
        .content-area {
            flex: 1;
            padding: 30px;
        }
        
        /* Componentes */
        .card { 
            border-radius: 15px; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
            transition: transform 0.2s; 
            margin-bottom: 20px;
            border: none;
        }
        .card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .btn-primary { 
            background: var(--primary-color); 
            border: none; 
            border-radius: 8px;
            font-weight: 500;
        }
        .btn-primary:hover { 
            background: var(--secondary-color); 
            transform: translateY(-1px);
        }
        .progress-bar { 
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color)); 
            border-radius: 10px;
        }
        
        /* Footer */
        footer {
            background: #f8f9fa;
            border-top: 1px solid #dee2e6;
            margin-top: auto;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .mobile-toggle {
                display: block;
            }
            
            .content-area {
                padding: 20px;
            }
            
            .top-header {
                padding: 15px 20px;
            }
            
            .top-header-right {
                gap: 10px;
            }
            
            .user-name {
                display: none;
            }
        }
        
        @media (min-width: 769px) {
            .mobile-toggle {
                display: none;
            }
        }
    </style>
</head>
<body>
    <?php if (isset($_SESSION['user_id'])): ?>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="<?php echo url('app'); ?>" class="sidebar-brand">
                <i class="bi bi-mortarboard-fill"></i> Farmadec LMS
            </a>
        </div>
        
        <nav>
            <ul class="sidebar-nav">
                <li>
                    <a href="<?php echo url('app'); ?>" class="<?php echo basename($_SERVER['PHP_SELF']) === 'index.php' && $_GET['url'] === 'app' ? 'active' : ''; ?>">
                        <i class="bi bi-house-fill"></i>
                        Mis Cursos
                    </a>
                </li>
                <li>
                    <a href="<?php echo url('profile'); ?>" class="<?php echo basename($_SERVER['PHP_SELF']) === 'index.php' && $_GET['url'] === 'profile' ? 'active' : ''; ?>">
                        <i class="bi bi-person-circle"></i>
                        Mi Perfil
                    </a>
                </li>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <li class="mt-3">
                    <div class="text-muted small px-3">Administración</div>
                </li>
                <li>
                    <a href="<?php echo url('admin'); ?>">
                        <i class="bi bi-gear-fill"></i>
                        Panel Admin
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <!-- Contenido principal -->
    <div class="main-content">
        <!-- Header superior -->
        <div class="top-header">
            <div class="top-header-left">
                <button class="mobile-toggle" id="mobileToggle">
                    <i class="bi bi-list"></i>
                </button>
            </div>
            
            <div class="top-header-right">
                <div class="user-info">
                    <img src="<?php echo $_SESSION['user_avatar'] ?? 'https://via.placeholder.com/40'; ?>" 
                         alt="Avatar" class="user-avatar">
                    <span class="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                </div>
                
                <a href="<?php echo url('logout'); ?>" class="logout-btn">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="d-none d-md-inline">Salir</span>
                </a>
            </div>
        </div>

        <!-- Área de contenido -->
        <div class="content-area">
            <?php echo $content ?? ''; ?>
        </div>
    </div>

    <?php else: ?>
    <!-- Sin autenticación - usar layout simple -->
    <div class="container-fluid p-0">
        <?php echo $content ?? ''; ?>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <script>
        // Toggle sidebar en móvil
        document.getElementById('mobileToggle')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        });
        
        // Cerrar sidebar al hacer click fuera en móvil
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const mobileToggle = document.getElementById('mobileToggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !mobileToggle.contains(e.target) &&
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });
        
        // Cerrar sidebar al redimensionar ventana
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth > 768) {
                sidebar.classList.remove('show');
            }
        });
    </script>
    
    <?php if (isset($scripts)): echo $scripts; endif; ?>
</body>
</html>
