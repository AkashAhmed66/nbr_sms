{{--
<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="login" :value="__('Email or Username')" />
            <x-text-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (\Illuminate\Support\Facades\Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
--}}

@php
  $configData = Helper::appClasses();
  $customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Login - Page')

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/@form-validation/form-validation.scss'
  ])
@endsection

@section('page-style')
  @vite([
    'resources/assets/vendor/scss/pages/page-auth.scss'
  ])
@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/@form-validation/popular.js',
    'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/auto-focus.js'
  ])
@endsection

@section('page-script')
  @vite([
    'resources/assets/js/pages-auth.js'
  ])
@endsection

@section('content')
<div class="d-flex align-items-stretch min-vh-100">
  <!-- Left Side - Image Only -->
  <div class="col-lg-8 d-none d-lg-flex p-0" 
       style="background-image: url('{{asset('assets/img/login-image.png')}}');
              background-size: cover; 
              background-position: center; 
              background-repeat: no-repeat;">
  </div>

  <!-- Right Side - Login Form -->
  <div class="col-lg-4 col-12 d-flex align-items-center justify-content-center p-0" style="background: #fafbfc;">
    <div class="w-100 p-5" style="max-width: 480px;">
      
      <!-- Logo Section -->
      <div class="text-center mb-5">
        <div class="app-brand justify-content-center mb-4">
          <a href="{{url('/')}}" class="app-brand-link gap-2 text-decoration-none">
            <span class="app-brand-logo demo">@include('_partials.macros',["width"=>12,"withbg"=>'var(--bs-primary)'])</span>
            <span class="app-brand-text demo text-heading fw-bold fs-3" style="color: var(--bs-primary);">
              {{config('variables.templateName')}}
            </span>
          </a>
        </div>
      </div>

      <!-- Welcome Section -->
      <div class="text-center mb-5">
        <h3 class="fw-bold text-dark mb-2">Welcome Back!</h3>
        <p class="text-muted mb-0">Sign in to access your SMS dashboard</p>
      </div>

      <!-- Login Form -->
      <form id="formAuthentication" action="{{route('login')}}" method="POST" class="needs-validation" novalidate>
        @csrf
        
        <!-- Email/Username Field -->
        <div class="mb-4">
          <div class="form-floating form-floating-outline">
            <input type="text" 
                   class="form-control @error('email') is-invalid @enderror" 
                   id="email" 
                   name="login" 
                   placeholder="Enter your email or username"
                   value="{{old('login')}}"
                   required 
                   autofocus 
                   autocomplete="username"
                   style="padding: 1rem; height: auto; border-radius: 12px; border: 2px solid #e9ecef; transition: all 0.3s ease;">
            <label for="email" class="fw-medium">
              <i class="ri-user-line me-2"></i>Email or Username
            </label>
          </div>
          @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
        </div>

        <!-- Password Field -->
        <div class="mb-4">
          <div class="form-password-toggle">
            <div class="input-group input-group-merge">
              <div class="form-floating form-floating-outline">
                <input type="password" 
                       id="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       name="password" 
                       placeholder="Enter your password"
                       required
                       autocomplete="current-password"
                       style="padding: 1rem; height: auto; border-radius: 12px 0 0 12px; border: 2px solid #e9ecef; transition: all 0.3s ease;" />
                <label for="password" class="fw-medium">
                  <i class="ri-lock-line me-2"></i>Password
                </label>
              </div>
              <span class="input-group-text cursor-pointer" 
                    style="border-radius: 0 12px 12px 0; border: 2px solid #e9ecef; border-left: none; background: #f8f9fa;">
                <i class="ri-eye-off-line"></i>
              </span>
            </div>
          </div>
          @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="remember-me" name="remember">
            <label class="form-check-label text-muted" for="remember-me">
              Remember me
            </label>
          </div>
          {{-- <a href="{{route('password.request')}}" class="text-primary text-decoration-none fw-medium">
            Forgot password?
          </a> --}}
        </div>

        <!-- Login Button -->
        <div class="mb-4">
          <button class="btn btn-primary w-100 py-3 fw-semibold" 
                  type="submit"
                  style="border-radius: 12px; font-size: 1.1rem; box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.3); transition: all 0.3s ease;">
            <i class="ri-login-box-line me-2"></i>
            Sign In
          </button>
        </div>
      </form>

      <!-- Additional Info -->
      <div class="text-center mt-5">
        <p class="text-muted small mb-0">
          Secure login powered by advanced encryption
        </p>
      </div>
    </div>
  </div>
</div>

<style>
/* Enhanced Form Styles */
.form-floating-outline .form-control:focus {
  border-color: var(--bs-primary) !important;
  box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.25) !important;
  transform: translateY(-1px);
}

.form-floating-outline .form-control:focus + label {
  color: var(--bs-primary) !important;
}

/* Button Hover Effects */
.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(var(--bs-primary-rgb), 0.4) !important;
}

/* Responsive Design */
@media (max-width: 991.98px) {
  .min-vh-100 > div {
    min-height: 100vh;
  }
}

/* Form Validation Styles */
.form-control.is-invalid {
  border-color: #dc3545 !important;
}

.form-control.is-valid {
  border-color: #198754 !important;
}

/* Loading Animation for Button */
.btn-primary:active {
  transform: scale(0.98);
}

/* Enhanced Input Group */
.input-group-text:hover {
  background-color: #e9ecef !important;
}

/* App Brand Enhancement */
.app-brand-link:hover .app-brand-text {
  transform: scale(1.05);
  transition: transform 0.3s ease;
}
</style>
@endsection

