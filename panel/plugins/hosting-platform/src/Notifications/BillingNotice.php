<?php

namespace BrandedHosting\Platform\Notifications;

use BrandedHosting\Platform\Models\HostingOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BillingNotice extends Notification implements ShouldQueue
{
    use Queueable;

    public const INVOICE = 'invoice';
    public const REMINDER = 'reminder';
    public const FINAL_REMINDER = 'final_reminder';
    public const SUSPENDED = 'suspended';
    public const RENEWED = 'renewed';

    /**
     * @param  HostingOrder  $order  The original order that owns the server (its renewal is looked up for the link).
     */
    public function __construct(
        public string $type,
        public HostingOrder $order,
        public ?HostingOrder $renewal = null,
    ) {}

    /** @return string[] */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $name = $this->order->plan_snapshot['plan_name'] ?? 'your server';
        $due = $this->order->paid_until?->format('j F Y');
        $target = $this->renewal ?? $this->order;
        $url = route('hosting.order.tracking', ['uuid' => $target->uuid]);
        $amount = $this->renewal ? $this->renewal->formatted_total : null;

        $mail = (new MailMessage)->greeting('Hello ' . $notifiable->username . ',');

        return match ($this->type) {
            self::INVOICE => $mail
                ->subject("Renewal due for {$name}")
                ->line("{$name} is paid until {$due}. To keep it running, please renew before then.")
                ->line("Amount: {$amount}. Pay as you did before and upload your slip on the order page.")
                ->action('Renew now', $url),
            self::REMINDER => $mail
                ->subject("Reminder: {$name} expires on {$due}")
                ->line("We have not received payment for renewing {$name}. It is paid until {$due}.")
                ->action('Renew now', $url),
            self::FINAL_REMINDER => $mail
                ->subject("Final reminder: {$name} will be suspended")
                ->line("{$name} runs out on {$due}. After that your server is suspended until the renewal is paid. Your data is kept.")
                ->action('Renew now', $url),
            self::SUSPENDED => $mail
                ->subject("{$name} has been suspended")
                ->line('The renewal was not paid, so we suspended your server. Your files and settings are kept.')
                ->line('Pay the renewal and it is switched back on once we verify the payment.')
                ->action('Renew now', $url),
            default => $mail
                ->subject("Renewal received for {$name}")
                ->line("Thank you. {$name} is now paid until {$due}.")
                ->action('View order', $url),
        };
    }
}
