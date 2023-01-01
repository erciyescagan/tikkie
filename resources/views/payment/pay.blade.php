{{\App\Models\User::find($payment->user_id)->name}} sizden {{$payment->amount_per_person}} TL istiyor.<br>

Cüzdanınız ile ödeme yapmak için lütfen giriş yapın. Veya üye değilseniz kayıt olun.

@if(\Illuminate\Support\Facades\Auth::check())
Güncel Bakiyeniz: {{\Illuminate\Support\Facades\Auth::user()->account->wallet_balance}} TL
@endif

<form action="{{route('payment.pay', ['hash' => Request::segment(2)])}}" method="POST">
    @csrf
    @method('POST')
    <input type="text" name="from" value="" placeholder="from">
    @if(\Illuminate\Support\Facades\Auth::check())
    <input type="hidden" name="user_id" value="{{\Illuminate\Support\Facades\Auth::id()}}">
    @endif
    <input type="submit" value="Öde">
</form>
