<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class PointageNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $type; // 'arrivée' ou 'départ'
    protected $heure;

    public function __construct($user, $type, $heure)
    {
        $this->user = $user;
        $this->type = $type;
        $this->heure = $heure;
    }

    // Notification stockée en base de données
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
{
    return [
        'titre' => 'Nouveau pointage : ' . ucfirst($this->type),
        'message' => $this->user->name . ' a pointé à ' . $this->heure . ' (' . $this->type . ')',
        'user_id' => $this->user->id,
        'type' => $this->type,
        'heure' => $this->heure,
        'url' => route('employe.historique', ['user' => $this->user->id]),
    ];
}

}
