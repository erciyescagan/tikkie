<form action="{{route('payment.store')}}" method="POST">
    @csrf
    @method('POST')
    <input name="amount" type="text" placeholder="amount"><br>
    <input name="to" type="text" placeholder="to?">
    <input name="is_permanent" type="checkbox" value=" ">
    <input type="submit">
</form>
