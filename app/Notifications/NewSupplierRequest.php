<?php

namespace App\Notifications;

use App\Models\SupplierRequest;
use FontLib\Table\Type\name;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Benwilkins\FCM\FcmMessage;

class NewSupplierRequest extends Notification
{
    use Queueable;
    /**
     * @var SupplierRequest
     */
    private $supplierRequest;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(SupplierRequest $supplierRequest)
    {
        //
        $this->supplierRequest = $supplierRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','fcm'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toFcm($notifiable)
    {
        $message = new FcmMessage();
        $notification = [
            //'title'        => "New Order #".$this->order->id." to ".$this->order->productOrders[0]->product->market->name,
            'title'        => "New Vendor Stock Supply #".$this->supplierRequest->sr_code." ",
            'body'         => $this->supplierRequest->market->name,
            //'icon'         => $this->order->productOrders[0]->product->market->getFirstMediaUrl('image', 'thumb'),
            'icon'         => '',
            'click_action' => "FLUTTER_NOTIFICATION_CLICK",
            'id' => '1',
            'status' => 'done',
        ];
        $message->content($notification)->data($notification)->priority(FcmMessage::PRIORITY_HIGH);

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->supplierRequest['id'],
            'title'    => "New Vendor Stock Supply #".$this->supplierRequest->sr_code." "
        ];
    }
}