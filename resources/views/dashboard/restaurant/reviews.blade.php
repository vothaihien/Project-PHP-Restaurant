@extends('layouts.dashboard.app')

@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-lg-7 d-flex">
        <!-- Header container -->
        <div class="container-fluid">
            <div class="header-body">
                <div class="row">
                    <div class="col-md-12 {{ $class ?? '' }}">
                        <h1 class="display-2 text-white">Phản hồi khách hàng</h1>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-lg-6 col-7">
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{ route('restaurant.index') }}"><i
                                            class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">Nhà hàng</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Phản hồi</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-6 col-5 text-right">
                        <a href="#" class="btn btn-sm btn-neutral">Lọc</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 mb-5 mb-xl-0">
                <div class="card shadow">
                    <!-- Card header -->
                    <div class="card-header border-0">
                        <h3 class="mb-0">Đánh giá: <span class="{{ App\Models\Review::getAvgColor($avgRating) }}"
                                style="font-size: large">{{ $avgRating }}</span></h3>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive" data-toggle="list"
                        data-list-values='[ "rating", "comment", "created_at"]'>
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="sort" data-sort="rating">Đánh giá</th>
                                    <th scope="col" class="sort" data-sort="comment">Bình luận</th>
                                    <th scope="col" class="sort" data-sort="created_at">Ngày tạo</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach($reviews as $review)
                                    <tr>
                                        <td>
                                            <span class="{{ optional($review)->getColor() ?? 'text-muted' }}"
                                                style="font-size: larger">{{ $review->rating }}</span>
                                        </td>
                                        <td>
                                            {{ $review->comment ?? 'Không có' }}
                                        </td>
                                        <td>
                                            {{ Carbon\Carbon::parse($review->created_at)->toDayDateTimeString() }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    @if($reviews->hasPages())
                        <div class="card-footer">
                            {{ $reviews->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @include('layouts.dashboard.footers.auth')
    </div>
@endsection