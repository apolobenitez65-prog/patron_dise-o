<?php

require_once __DIR__ . '/../src/Legacy/Notification.php';
require_once __DIR__ . '/../src/Legacy/LegacyNotifier.php';
require_once __DIR__ . '/../src/Legacy/LegacyNotifierAdapter.php';

$notificacion = new LegacyNotifierAdapter(
    new LegacyNotifier()
);

ob_start();
$notificacion->send('Pedido creado');
$resultado = ob_get_clean();

$esperado = '[LEGACY] Pedido creado<br>';

if ($resultado !== $esperado) {
    throw new RuntimeException(
        'El adaptador no produjo el resultado esperado'
    );
}

echo "OK - Adapter funciona correctamente." . PHP_EOL;