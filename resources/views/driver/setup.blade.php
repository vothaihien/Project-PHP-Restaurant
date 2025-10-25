@extends('layouts.app')

@section('title', 'Become a partner driver')

@section('content')
<div class="container pt-2" style="height: 66vh">
    <div class="row">
        <div class="col-md-6">
            <img src="{{ asset('svg/license.png') }}" class="w-100 p-3"/>
        </div>
        <div class="col-md-6 modal-body ">
            <form enctype="multipart/form-data" action='{{ route('driver.storeDriversLicense') }}' method="POST" class="form-signin" style="border-radius: 0.25rem">
                @csrf
                <h1 class="h2 mb-3 font-weight-normal">Enter Your Drivers License</h1>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <input id="license_number" type="text" placeholder="License Number (5-20 characters)" class="form-control top @error('license_number') is-invalid @enderror" name="license_number" value="{{ old('license_number') }}" required autocomplete="license_number" maxlength="20">
                
                <input id="dob" type="date" placeholder="Date of Birth" max="{{ now()->subYears(18)->format('Y-m-d') }}" class="form-control middle @error('dob') is-invalid @enderror" name="dob" value="{{ old('dob') }}" required autocomplete="dob">
                
                <input id="reference_number" type="text" placeholder="Reference Number (5-15 characters)" class="form-control middle @error('reference_number') is-invalid @enderror" name="reference_number" value="{{ old('reference_number') }}" required autocomplete="reference_number" maxlength="15">
                
                <div class="input-group">
                    <input id="valid_on" type="date" placeholder="Valid On" max="{{ date('Y-m-d') }}" class="form-control bottom @error('valid_on') is-invalid @enderror" name="valid_on" value="{{ old('valid_on') }}" required autocomplete="valid_on">
                    <input id="expires_on" type="date" placeholder="Expires On" min="{{ date('Y-m-d') }}" class="form-control bottom @error('expires_on') is-invalid @enderror" name="expires_on" value="{{ old('expires_on') }}" required autocomplete="expires_on">
                </div>
                <button type="submit" class="btn btn-primary mt-3">{{ __('Save Drivers License') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
