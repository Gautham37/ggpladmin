<?php
/**
 * File name: StatusChangedOrder.php
 * Last modified: 2020.04.30 at 08:21:09
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\Notifications;

use App\Models\SupplierRequest;
use Benwilkins\FCM\FcmMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PriceUpdatedSupplierRequest extends Notification
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
        return ['database', 'fcm'];
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
        
        if($this->supplierRequest->sr_status==0) {
            $status = 'Rejected';
        } elseif($this->supplierRequest->sr_status==1) {
            $status = 'Pending';
        } elseif($this->supplierRequest->sr_status==2) {
            $status = 'Approved';
        } elseif($this->supplierRequest->sr_status==3) {
            $status = 'Purchased';
        } elseif($this->supplierRequest->sr_status==4) {
            $status = 'Order Picked Up';
        }
        
        $notification = [
            'title' => trans('lang.notification_your_order', ['order_id' => $this->supplierRequest->id, 'order_status' => $status]),
            //'text' => $this->order->productOrders[0]->product->market->name,
            //'image' => $this->order->productOrders[0]->product->market->getFirstMediaUrl('image', 'thumb')
            'text' => '',
            'image' => ''
        ];
        $data = [
            'click_action' => "FLUTTER_NOTIFICATION_CLICK",
            'sound' => 'default',
            'id' => 'orders',
            'status' => 'done',
            'message' => $notification,
        ];
        $message->content($notification)->data($data)->priority(FcmMessage::PRIORITY_HIGH);

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
        if($this->supplierRequest->sr_status==0) {
            $status = 'Rejected';
        } elseif($this->supplierRequest->sr_status==1) {
            $status = 'Pending';
        } elseif($this->supplierRequest->sr_status==2) {
            $status = 'Approved';
        } elseif($this->supplierRequest->sr_status==3) {
            $status = 'Purchased';
        } elseif($this->supplierRequest->sr_status==4) {
            $status = 'Order Picked Up';
        }
        return [
            'order_id' => $this->supplierRequest['id'],
            'title'    => "Vendor Supply #" . $this->supplierRequest->sr_code . " Price Details Updated",
        ];
    }
}