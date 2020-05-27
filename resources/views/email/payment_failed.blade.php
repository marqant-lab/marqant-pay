<p>
    {{ __('Payment failed.') }}<br>
    {{ $payment->amount }}<br>
    {{ __('You needs to update your payment method at') }}&nbsp;
    <a href="{{ config('marqant-pay.payment_urls.base_url') }}{{ config('marqant-pay.payment_urls.payment_sub_url') }}">
        {{ __('Here') }}
    </a>.
</p>
