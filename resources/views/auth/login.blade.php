@extends('layouts.masterLogin')

@section('content')

<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        <!-- Register -->
        <div class="card">
          <div class="card-body">

            <div class="text-center">
              <h2 class="mb-3 f-w-600">{{ __('INICIAR SESION') }}</h2>
              <hr style="height: 5px; border-width: 0; color: #d18e4b; background-color: #d18e4b;">
            </div>

            <form method="POST" class="mb-3" action="{{ route('login') }}">
                @csrf
              <div class="mb-3">
                <input 
                  id="email"
                  type="email"
                  class="form-control 
                  @error('email') is-invalid @enderror" 
                  name="email" value="{{ old('email') }}" 
                  placeholder="Usuario"
                  required autocomplete="email" autofocus
                  />
              </div>
                @error('email')
                <div class="alert alert-danger" role="alert">
                    {{ $message }}
                </div>
                @enderror
              <div class="mb-3 form-password-toggle">
                <div class="d-flex justify-content-between">
                  <label class="form-label" for="password">Contraseña</label>
                  @if (Route::has('password.request'))
                  <a href="{{ route('password.request') }}">
                  </a>
                   @endif
                
                </div>
                <div class="input-group input-group-merge">
                  <input 
                    id="password" 
                    type="password" 
                    class="form-control @error('password') is-invalid @enderror" 
                    name="password" 
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    required autocomplete="current-password"
                    />
                </div>

                @error('password')
                <div class="alert alert-danger" role="alert">
                    {{ $message }}
                </div>
                @enderror


              </div>
              <div class="mb-3">

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        {{ __('Recordar datos de usuario') }}
                    </label>
                </div>

              </div>
              <div class="mb-3">
                <button type="submit" class="btn btn-primary d-grid w-100">
                    {{ __('Iniciar sesión') }}
                </button>
              </div>
            </form>

           <!--  <p class="text-center">
              <span>No tienes cuenta?</span>
              <a href="{{ route('register') }}">
                <span>Registrate</span>
              </a>
            </p> -->
          </div>
        </div>
        <!-- /Register -->
      </div>
    </div>
  </div>
@endsection
