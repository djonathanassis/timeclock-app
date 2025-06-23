<?php

declare(strict_types = 1);

namespace App\Notifications;

use App\Models\TimeEntry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TimeEntryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Cria uma nova instância da notificação.
     */
    public function __construct(
        public readonly TimeEntry $timeEntry
    ) {
    }

    /**
     * Obtém os canais de entrega da notificação.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Obtém a representação do email da notificação.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $user = $this->timeEntry->user;
        $recordedAt = $this->timeEntry->recorded_at;
        $formattedDate = $recordedAt->format('d/m/Y');
        $formattedTime = $recordedAt->format('H:i:s');

        return (new MailMessage)
            ->subject("Registro de Ponto - {$user->name}")
            ->greeting("Olá {$notifiable->name},")
            ->line("Um novo registro de ponto foi realizado pelo funcionário {$user->name}.")
            ->line("Data: {$formattedDate}")
            ->line("Hora: {$formattedTime}")
            ->action('Ver Detalhes', url('/time-entries'))
            ->line('Este é um e-mail automático, por favor não responda.');
    }

    /**
     * Obtém o array de representação da notificação.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $user = $this->timeEntry->user;
        $recordedAt = $this->timeEntry->recorded_at;

        return [
            'time_entry_id' => $this->timeEntry->id,
            'user_id' => $user->id,
            'user_name' => $user->name,
            'recorded_at' => $recordedAt->format('Y-m-d\TH:i:s.u\Z'),
            'formatted_date' => $recordedAt->format('d/m/Y'),
            'formatted_time' => $recordedAt->format('H:i:s'),
        ];
    }
} 