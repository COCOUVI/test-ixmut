<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TacheEnvoyeeNotification extends Notification
{
    use Queueable;

    protected $tache;

    public function __construct($tache)
    {
        $this->tache = $tache;
    }

    // Canal de notification
    public function via($notifiable)
    {
        return ['database'];
    }

    // Contenu stocké en base (essentiel pour les notifications dans le header)
    public function toDatabase($notifiable)
    {
        return [
            'titre' => 'Nouvelle tâche',
            'type' => 'tache',
            'message' => 'Tâche envoyée par ' . $this->tache->user->name,
            'user_id' => $this->tache->user_id,
            'url' => route('employe.historique', ['user' => $this->tache->user_id]),
        ];
    }
}
