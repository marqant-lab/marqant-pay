<p>
    {{ __('Payment failed.') }}<br>
    Billable email: {{ $payment->billable->email }}
    Billable provider: {{ $payment->provider }}
    Amount: {{ $payment->amount }}<br>
    Status: {{ $payment->status }}<br>
    Payment ID: {{ $payment->id }}<br>
</p>
