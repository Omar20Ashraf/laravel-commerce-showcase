<!DOCTYPE html>
<html>
<head><title>Cart</title></head>
<body>
<div class="container">
    <h1>Your Cart</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="cart-items">
        @foreach($cartItems as $item)
            <div class="cart-item">
                <p>{{ $item->serviceItem->name }} (Qty: {{ $item->qty }})</p>
            </div>
        @endforeach
    </div>
</div>
</body>
</html>
