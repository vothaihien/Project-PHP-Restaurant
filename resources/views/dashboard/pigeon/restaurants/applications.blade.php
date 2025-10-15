@extends('layouts.dashboard.app')

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-lg-7 d-flex">
    <!-- Header container -->
    <div class="container-fluid">
        <div class="header-body">
            <div class="row">
                <div class="col-md-12 {{ $class ?? '' }}">
                    <h1 class="display-2 text-white">Restaurant Applications</h1>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="{{ route('pigeon.index') }}"><i
                                        class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pigeon.restaurants') }}">Restaurants</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Applications</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="#" class="btn btn-sm btn-neutral">Filters</a>
                </div>
            </div>
            @if (Session::has('success'))
            <div class="row">
                <div class="col col-md-6 offset-md-3 text-center">
                    <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
                        <span class="alert-icon"><i class="fa fa-trash" aria-hidden="true"></i></span>
                        <span class="alert-text">{!! Session::get('success') !!}</span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-12 mb-5 mb-xl-0">
            <div class="card shadow">
                <!-- Card header -->
                <div class="card-header border-0">
                    <h3 class="mb-0">Restaurants</h3>
                </div>
                <!-- Table -->
                <div class="table-responsive" data-toggle="list"
                    data-list-values='["name", "description", "price", "category"]'>
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">Restaurant Name</th>
                                <th scope="col" class="sort" data-sort="price">Email</th>
                                <th scope="col" class="sort" data-sort="price">Phone</th>
                                <th scope="col" class="sort" data-sort="category">Category</th>
                                <th scope="col" class="sort" data-sort="city">City</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @forelse($restaurants as $restaurant)
                            <tr>
                                <th scope="row">
                                    <div class="media align-items-center">
                                        <a href="#" class="avatar rounded-circle mr-3">
                                            @if($restaurant->image)
                                            <img alt="Image" src="{{ url('storage/' . $restaurant->image) }}">
                                            @endif
                                        </a>
                                        <div class="media-body">
                                            <span class="name mb-0 text-sm">{{$restaurant->name}}</span>
                                        </div>
                                    </div>
                                </th>
                                <td>
                                    <span class="badge badge-dot mr-4">
                                        <span class="status">{{$restaurant->email}}</span>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-dot mr-4">
                                        <span class="status">{{$restaurant->phone}}</span>
                                    </span>
                                </td>
                                <td>
                                    {{App\Category::find($restaurant->category_id)->name}}
                                </td>
                                <td>
                                    {{$restaurant->address->city ?? 'N/A'}}
                                </td>
                                <td>
                                    <button class="btn btn-sm" data-toggle="tooltip"
                                        onclick="window.location ='{{route('pigeon.restaurantDetails', $restaurant->slug)}}'">
                                        <i class="fas fa-info-circle"></i> View
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <td></td>
                            <td></td>
                            <td class="mx-auto"><b>Currently, there are no new applications.</b></td>
                            <td></td>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                @if($restaurants->hasPages())
                <div class="card-footer">
                    {{ $restaurants->links('vendor.pagination.bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
    </div>

    @include('layouts.dashboard.footers.auth')
</div>
@endsection