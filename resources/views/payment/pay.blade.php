{{$info->from}} sizden {{$info->amount}} TL istiyor.<br>

Güncel Bakiyeniz: {{\Illuminate\Support\Facades\Auth::user()->account->wallet_balance}} TL

<form action="{{route('payment.pay', ['hash' => Request::segment(2)])}}" method="POST">
    @csrf
    @method('POST')
    <input type="submit" value="Öde">
</form>
