<?php

namespace Marqant\MarqantPay\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PaymentFailed
 *
 * @package Marqant\MarqantPay\Mail
 */
class PaymentFailed extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public Model $payment;

    private $email_template = 'payment_failed_email_view';

    /**
     * Create a new message instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $Payment
     */
    public function __construct(Model $Payment)
    {
        $this->payment = $Payment;
    }

    /**
     * @param string $email_template
     *
     * @return PaymentFailed
     */
    public function setEmailTemplate(string $email_template): PaymentFailed
    {
        $this->email_template = $email_template;

        return $this;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = config('marqant-pay.' . $this->email_template);

        return $this->view($view)
            ->subject(__('Payment failed.') . ' ' . __('Requires payment method.'))
            ->with([
                'payment' => $this->payment,
            ]);
    }
}
