<?php

class EmailNotification implements Notification
{
    public function __construct(private string $destinatario)
    {
    }

    public function send(string $message): void
    {
        echo "[EMAIL] para {$this->destinatario}: {$message}<br>";
    }
}