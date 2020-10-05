@extends('layouts.app', ['page' => __('User Profile'), 'pageSlug' => 'profile'])

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ __('Details') }}</h5>
                </div>
                <div class="col-8">
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="home">Home</a></li>
                        <li class="breadcrumb-item "><a href="../vendors">Vendors</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="bc_current">View</li>
                        </ol>
                    </nav>
                </div>

                <form method="post" action="{{ route('profile.update') }}" autocomplete="off">
                    <div class="card-body">

                            <div class="form-group">
                                <label>{{ __('Refferal By') }} : </label>
                                <label>{{ $vendor[0]->refferal_by }}</label>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Email') }} : </label>
                                <label>{{ $vendor[0]->email }}</label>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Website') }} : </label>
                                <label>{{ $vendor[0]->website }}</label>
                            </div>
                    </div>
                    <div class="card-footer" style="display: none">
                        <button type="submit" class="btn btn-fill btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ __('Package Details') }}</h5>
                </div>

                    <div class="card-body">

                        <div class="form-group">
                            <table class="table tablesorter " id="">
                                <thead class=" text-primary">
                                    <th>Slno</th>
                                    <th>Package Name</th>
                                    <th>Renewed Date</th>
                                    <th>Amount</th>
                                </thead>
                                @if(count($renewal)>0)
                                <?php $i=1; ?>
                                @foreach($renewal as $key=>$value)
                                <tr>
                                    <td>{{ $i++  }}</td>
                                    <td>{{ $value->type }}</td>
                                    <td>{{ $value->renewal_date }}</td>
                                    <td>{{ $value->amount_paid }}</td>
                                </tr>
                                @endforeach
                                @endif
                            </table>
                        </div>


                    </div>
                    <div class="card-body" style="display: none">
                        @csrf
                        @method('put')

                        @include('alerts.success', ['key' => 'password_status'])

                        <div class="form-group{{ $errors->has('old_password') ? ' has-danger' : '' }}">
                            <label>{{ __('Current Password') }}</label>
                            <input type="password" name="old_password" class="form-control{{ $errors->has('old_password') ? ' is-invalid' : '' }}" placeholder="{{ __('Current Password') }}" value="" required>
                            @include('alerts.feedback', ['field' => 'old_password'])
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                            <label>{{ __('New Password') }}</label>
                            <input type="password" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('New Password') }}" value="" required>
                            @include('alerts.feedback', ['field' => 'password'])
                        </div>
                        <div class="form-group">
                            <label>{{ __('Confirm New Password') }}</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="{{ __('Confirm New Password') }}" value="" required>
                        </div>
                    </div>
                    <div class="card-footer" style="display: none">
                        <button type="submit" class="btn btn-fill btn-primary">{{ __('Change password') }}</button>
                    </div>

            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-user">
                <div class="card-body">
                    <p class="card-text">
                        <div class="author">
                            <div class="block block-one"></div>
                            <div class="block block-two"></div>
                            <div class="block block-three"></div>
                            <div class="block block-four"></div>
                            <a href="#">
                                <img class="avatar" src="{{ asset('black') }}/img/bg5.jpg" alt="">
                                <h5 class="title">{{ $vendor[0]->name }}</h5>
                            </a>
                            <p class="description">
                                {{ $vendor[0]->contact_number }}
                            </p>
                        </div>
                    </p>
                    <div class="card-description">
                        {{ $vendor[0]->description }}
                    </div>
                </div>
               <!-- <div class="card-footer">
                    <div class="button-container">
                        <button class="btn btn-icon btn-round btn-facebook">
                            <i class="fab fa-facebook"></i>
                        </button>
                        <button class="btn btn-icon btn-round btn-twitter">
                            <i class="fab fa-twitter"></i>
                        </button>
                        <button class="btn btn-icon btn-round btn-google">
                            <i class="fab fa-google-plus"></i>
                        </button>
                    </div>
                </div>-->
            </div>
        </div>
    </div>
@endsection
