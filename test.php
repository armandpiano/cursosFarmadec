<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Test de Corrección - Google OAuth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <h2>🎯 Test de Corrección - Google OAuth Callback</h2>
        <p class="lead">Esta prueba verifica que el callback puede leer correctamente los tokens JSON</p>
        
        <div class="row">
            <div class="col-md-6">
                <h4>1. Test con Token Simulado</h4>
                <button onclick="testToken()" class="btn btn-primary">🧪 Enviar Token Test</button>
                <div id="test-result" class="mt-3"></div>
            </div>
            <div class="col-md-6">
                <h4>2. Test con Google Real</h4>
                <?php
                $config = include __DIR__ . '/src/Config/google.local.php';
                if (isset($config['client_id']) && strpos($config['client_id'], 'TU_GOOGLE_CLIENT_ID') === false):
                ?>
                    <div id="g_id_onload"
                         data-client_id="<?php echo htmlspecialchars($config['client_id']); ?>"
                         data-callback="handleGoogleResponse">
                    </div>
                    <div class="g_id_signin" data-type="standard" data-size="large"></div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        ⚠️ Google OAuth no configurado
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mt-4">
            <h4>3. Debug del Último Request</h4>
            <div id="debug-log" class="border p-3 bg-light" style="max-height: 300px; overflow-y: auto;">
                <small>Aguardando datos...</small>
            </div>
        </div>
    </div>

    <script src="https://accounts.google.com/gsi/client"></script>
    <script>
        let debugLog = [];
        
        function addLog(message, type = 'info') {
            const timestamp = new Date().toLocaleTimeString();
            const color = type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info';
            debugLog.push(`[${timestamp}] <span class="text-${color}">${message}</span>`);
            document.getElementById('debug-log').innerHTML = debugLog.join('<br>');
        }
        
        function testToken() {
            addLog('🧪 Iniciando test con token simulado...', 'info');
            
            const testToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.mock_token_' + Date.now();
            
            document.getElementById('test-result').innerHTML = `
                <div class="alert alert-info">
                    <strong>🔄 Enviando token simulado...</strong>
                </div>
            `;
            
            addLog('Enviando POST request con JSON...', 'info');
            
            fetch('google-callback.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ id_token: testToken })
            })
            .then(res => {
                addLog(`📥 Respuesta: ${res.status} ${res.statusText}`, res.ok ? 'success' : 'error');
                return res.json();
            })
            .then(data => {
                addLog('📄 Datos JSON recibidos', 'info');
                
                if (data.success) {
                    document.getElementById('test-result').innerHTML = `
                        <div class="alert alert-success">
                            <strong>✅ ¡EXCELENTE!</strong> Callback funciona perfectamente<br>
                            El token se leyó correctamente desde JSON
                        </div>
                    `;
                    addLog('🎉 ÉXITO: Callback lee tokens JSON correctamente', 'success');
                } else {
                    document.getElementById('test-result').innerHTML = `
                        <div class="alert alert-${data.error.includes('Token') ? 'warning' : 'danger'}">
                            <strong>${data.error.includes('Token') ? '✅ PERFECTO' : '❌ Error'}:</strong><br>
                            ${data.message || data.error}<br>
                            <small>Debug: ${JSON.stringify(data.debug, null, 2)}</small>
                        </div>
                    `;
                    
                    if (data.message && data.message.includes('Token')) {
                        addLog('🎉 PERFECTO: Callback lee JSON correctamente (error de token esperado)', 'success');
                    } else {
                        addLog(`❌ Error: ${data.message || data.error}`, 'error');
                    }
                }
            })
            .catch(error => {
                addLog(`❌ Error de red: ${error.message}`, 'error');
                document.getElementById('test-result').innerHTML = `
                    <div class="alert alert-danger">
                        <strong>❌ Error de conexión:</strong> ${error.message}
                    </div>
                `;
            });
        }
        
        function handleGoogleResponse(response) {
            addLog('🎯 Respuesta de Google recibida', 'success');
            addLog(`Token length: ${response.credential.length}`, 'info');
            
            const resultDiv = document.getElementById('test-result');
            resultDiv.innerHTML = `
                <div class="alert alert-info">
                    <strong>🔄 Procesando token de Google...</strong>
                </div>
            `;
            
            fetch('google-callback.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ id_token: response.credential })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    resultDiv.innerHTML = `
                        <div class="alert alert-success">
                            <strong>✅ ¡Google OAuth funcionando!</strong><br>
                            Usuario: ${data.user_info?.name}<br>
                            Email: ${data.user_info?.email}<br>
                            <small>Redirigiendo en 2 segundos...</small>
                        </div>
                    `;
                    addLog('🎉 Google OAuth exitoso', 'success');
                    
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2000);
                } else {
                    resultDiv.innerHTML = `
                        <div class="alert alert-danger">
                            <strong>❌ Error:</strong><br>
                            ${data.message}
                        </div>
                    `;
                    addLog(`❌ Error: ${data.message}`, 'error');
                }
            })
            .catch(error => {
                addLog(`❌ Error: ${error.message}`, 'error');
                resultDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <strong>❌ Error de conexión:</strong> ${error.message}
                    </div>
                `;
            });
        }
    </script>
</body>
</html>