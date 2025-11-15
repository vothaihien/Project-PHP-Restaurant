<footer class="container py-5" id="footer">
    <div class="row">
        <div class="col-12 col-md">
            <img src="{{asset('svg/dove.svg')}}" height="30px">
            <small class="d-block mb-3 text-muted">© {{ now()->year }}</small>
        </div>
        <div class="col-6 col-md">
            <h5>Tài xế</h5>
            <ul class="list-unstyled text-small">
                <li><a class="text-muted" href="{{route('login.driver')}}">Đăng nhập tài xế</a></li>
                <li><a class="text-muted" href="{{route('register.driver')}}">Lái xe cùng chúng tôi</a></li>
                <li><a class="text-muted" href="#">Tính năng nhóm</a></li>
                <li><a class="text-muted" href="#">Hỗ trợ tài xế</a></li>
            </ul>
        </div>
        <div class="col-6 col-md">
            <h5>Nhà hàng</h5>
            <ul class="list-unstyled text-small">
                <li><a class="text-muted" href="{{route('login.restaurant')}}">Đăng nhập nhà hàng</a></li>
                <li><a class="text-muted" href="{{route('register.restaurant')}}">Đối tác với chúng tôi</a></li>
                <li><a class="text-muted" href="#">Ưu đãi</a></li>
                <li><a class="text-muted" href="#">Hỗ trợ nhà hàng</a></li>
            </ul>
        </div>
        <div class="col-6 col-md">
            <h5>Địa điểm</h5>
            <ul class="list-unstyled text-small">
                <li><a class="text-muted" href="{{ route('home.index') }}">Quận 1</a></li>
                <li><a class="text-muted" href="#">Quận 3</a></li>
                <li><a class="text-muted" href="#">Bình Thạnh</a></li>
                <li><a class="text-muted" href="#">Phú Nhuận</a></li>
            </ul>
        </div>
        <div class="col-6 col-md">
            <h5>Về chúng tôi</h5>
            <ul class="list-unstyled text-small">
                <li><a class="text-muted" href="#">Đội ngũ</a></li>
                <li><a class="text-muted" href="#">Tuyển dụng</a></li>
                <li><a class="text-muted" href="{{ route('privacy') }}">Bảo mật</a></li>
                <li><a class="text-muted" href="#">Điều khoản</a></li>
            </ul>
        </div>
    </div>
</footer>
