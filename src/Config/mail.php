<?php
// Configuración de correo PHPMailer

return [
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'username' => 'tu_email@gmail.com',
    'password' => 'tu_app_password',
    'encryption' => 'tls',
    'from_email' => 'noreply@farmadec.com',
    'from_name' => 'Farmadec LMS',
];


if (file_exists(__DIR__ . '/mailConfig.php')) {
    $config = array_merge($config, require __DIR__ . '/mailConfig.php');
}

return $config;