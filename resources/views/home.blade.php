@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        <h2>Home - User Dashboard</h2>
                        <div class="form-group row mb-0 mt-3">
                            <div class="col-md-8 offset-md-4">
                                <a href="{{ url('/oauth2/logout') }}" class="btn btn-warning">Cognito Logout</a>
                            </div>
                        </div>
                        <div class="form-group row mb-0 mt-3">
                            <div class="col-md-8 offset-md-4">
                                <a href="{{ url('/oauth2/switch-account') }}" class="btn btn-warning">Switch Account</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
