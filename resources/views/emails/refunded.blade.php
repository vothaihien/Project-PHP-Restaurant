@component('mail::message')
# Đơn hàng đã được hoàn tiền

Đơn hàng của bạn đã được hoàn tiền.

**Mã đơn hàng:** {{ $order->id }}<br>
**Tổng tiền:** ${{ $order->billing_total }}<br>

**Các món đã đặt:**<br>
@foreach ($order->menu_items as $menu)
* Tên: {{ $menu->name }} <br>
Giá: ${{ $menu->price }} <br>
Số lượng: {{ $menu->pivot->quantity }} <br><br>
@endforeach

Bạn có thể xem thêm chi tiết đơn hàng bằng cách đăng nhập vào website của chúng tôi.
@component('mail::button', ['url' => config('app.url'), 'color' => 'green'])
Truy cập Website
@endcomponent

Trân trọng,<br/>
Đội ngũ Pigeon
@endcomponent
