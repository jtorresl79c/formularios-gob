@extends('layouts.masterLogin')

@section('content')

<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner py-4">
        <!-- Forgot Password -->
        <div class="card">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center">
              <a href="index.html" class="app-brand-link gap-2">
                <span class="app-brand-text demo text-body fw-bolder">CFL</span>
              </a>
            </div>
             @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
              @endif
            <!-- /Logo -->
            <h4 class="mb-2">Has olvidado tu contraseña? 🔒</h4>
            <p class="mb-4">Ingrese su correo electrónico y le enviaremos instrucciones para restablecer su contraseña</p>
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
              <div class="mb-3">
                <label for="email" class="form-label">Correo electroníco</label>

                <input 
                    id="email" 
                    type="email" 
                    class="form-control 
                    @error('email') is-invalid @enderror" 
                    name="email" value="{{ old('email') }}" 
                    placeholder="Ingresa tu correo electroníco"
                    required autocomplete="email" autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>

              <button type="submit" class="btn btn-primary d-grid w-100">
                {{ __('Enviar enlace de restablecimiento') }}
            </button>
            </form>
            <div class="text-center">
              <a href="{{ route('login') }}" class="d-flex align-items-center justify-content-center">
                <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                Regresar a inciar sesión
              </a>
            </div>
          </div>
        </div>
        <!-- /Forgot Password -->
      </div>
    </div>
  </div>
@endsection
