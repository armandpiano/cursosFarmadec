<?php

return [
    'client_id'     => getenv('GOOGLE_CLIENT_ID') ?: 'tu-client-id-aqui',
    'client_secret' => getenv('GOOGLE_CLIENT_SECRET') ?: 'tu-client-secret-aqui',
    'redirect_uri'  => 'http://localhost/cursosFarmadec/google-callback.php',
];
