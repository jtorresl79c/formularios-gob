@extends('layouts.masterLogin')

@section('content')

<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        <!-- Register -->
        <div class="card">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center">
              <a href="{{ url('/') }}" class="app-brand-link gap-2">
                <span class="app-brand-text demo text-body fw-bolder">CFL</span>
              </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-2">La aventura comienza aquí 🚀</h4>
            <p class="mb-4">¡Haga que la administración de su aplicación sea fácil y divertida!</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">{{ __('NOMBRE') }}</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    @error('name')
                        <div class="alert alert-danger" role="alert">
                        {{ $message }}
                        </span>
                    @enderror    
                </div>
              
                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('CORREO ELECTRÓNICO') }}</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                    @error('email')
                        <div class="alert alert-danger" role="alert">
                            {{ $message }}
                        </span>
                     @enderror
                </div>
                
                 <div class="mb-3 form-password-toggle">
                    <label class="form-label" for="password">{{ __('CONTRASEÑA') }}</label>
                    <div class="input-group input-group-merge">
                      <input
                        type="password"
                        id="password"
                        class="form-control"
                        @error('password') is-invalid @enderror" 
                        name="password"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                        aria-describedby="new-password"
                      />
                      <span class="input-group-text cursor-pointer"><i class="bi bi-eye-slash"></i></span>
                    </div>
                  </div>
              
                 <div class="mb-3 form-password-toggle">
                    <label for="password-confirm" class="form-label">{{ __('CONFIRMAR CONTRASEÑA') }}</label>
                    <div class="input-group input-group-merge">
                        <input 
                            id="password-confirm" 
                            type="password" 
                            class="form-control"
                            name="password_confirmation" 
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            required autocomplete="new-password"
                            />
                        <span class="input-group-text cursor-pointer"><i class="bi bi-eye-slash"></i></span>
                    </div>
                    @error('password')
                    <div class="alert alert-danger" role="alert">
                       {{ $message }}
                   </span>
                    @enderror
                </div>
           
              <div class="mb-3">
                <button type="submit" class="btn btn-primary d-grid w-100">
                      {{ __('Registrarme') }}
                </button>
              </div>
            </form>

            <p class="text-center">
              <span>Ya tienes una cuenta</span>
              <a href="{{ route('login') }}">
                <span>Iniciar sesión</span>
              </a>
            </p>
          </div>
        </div>
        <!-- /Register -->
      </div>
    </div>
  </div>
@endsection
