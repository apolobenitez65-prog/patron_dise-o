<?php

/**
 * Sistema heredado del laboratorio.
 *
 * Esta clase pertenece a un proveedor externo.
 * Conservamos su metodo original.
 */
class LegacyNotifier
{
    public function sendMessage(string $text): void
    {
        echo "[LEGACY] {$text}<br>";
    }
}