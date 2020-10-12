@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif


            <div class="card">
                <div class="card-header">Cambiar Contrasena:</div>

                <div class="card-body">
                    <form method="POST" action="{{route('user.updatePassword')}}" aria-label="Configuracion de la contrasena">
                        @csrf

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Contrasena actual</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="newPassword" class="col-md-4 col-form-label text-md-right">Nueva contrasena</label>

                            <div class="col-md-6">
                                <input id="newPassword" type="password" class="form-control" name="newPassword" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="newPasswordConfirm" class="col-md-4 col-form-label text-md-right">Confirmar nueva contrasena</label>

                            <div class="col-md-6">
                                <input id="newPasswordConfirm" type="password" class="form-control" name="newPasswordConfirmation" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Cambiar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
