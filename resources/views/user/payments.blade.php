
@foreach($payments as $payment)

    <span>{{$payment->to}}</span><br>
    <span>{{$payment->amount}}</span>

@endforeach

