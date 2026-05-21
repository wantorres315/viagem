@extends('layouts.fullscreen-layout')

@section('content')
    <div class="relative z-1 bg-white p-6 sm:p-0">
        <div class="relative flex h-screen w-full flex-col justify-center sm:p-0 lg:flex-row">
            <!-- Form -->
            <div class="flex w-full flex-1 flex-col lg:w-1/2">
                
                <div class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center">
                    <div>
                        <div class="mb-5 sm:mb-8">
                            <h1 class="text-title-sm sm:text-title-md mb-2 font-semibold text-gray-800">
                                Login
                            </h1>
                            <p class="text-sm text-gray-500">
                                Entre com seu usuario e senha
                            </p>
                        </div>
                        <div>
                            
                            
                            <!-- Formulário Breeze -->
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <!-- Session Status -->
                                <x-auth-session-status class="mb-4" :status="session('status')" />

                                <div class="space-y-5">
                                    <!-- Email -->
                                    <div>
                                        <x-input-label for="email" :value="__('Email')" />
                                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                    </div>
                                    <!-- Password -->
                                    <div>
                                        <x-input-label for="password" :value="__('Password')" />
                                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    </div>
                                    <!-- Remember Me -->
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <label for="remember_me" class="inline-flex items-center">
                                                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                                                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                                            </label>
                                        </div>
                                        @if (Route::has('password.request'))
                                            <a class="text-brand-500 hover:text-brand-600 text-sm" href="{{ route('password.request') }}">
                                                {{ __('Esqueceu sua senha?') }}
                                            </a>
                                        @endif
                                    </div>
                                    <!-- Button -->
                                    <div>
                                        <x-primary-button class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 flex w-full items-center justify-center rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                                            {{ __('Log in') }}
                                        </x-primary-button>
                                    </div>
                                </div>
                            </form>
                            <div class="mt-5">
                                <p class="text-center text-sm font-normal text-gray-700 sm:text-start">
                                    Não Tem conta?
                                    <a href="/register" class="text-brand-500 hover:text-brand-600">Se registre</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-brand-950 relative hidden h-full w-full items-center lg:grid lg:w-1/2">
                <div class="z-1 flex items-center justify-center">
                    <!-- ===== Common Grid Shape Start ===== -->
                    <x-common.common-grid-shape/>
                    <div class="flex max-w-xs flex-col items-center">
                        <a href="/" class="mb-4 block">
                            <img src="./images/logo/auth_logo.png" alt="Logo" />
                        </a>
                        <p class="text-center text-gray-400">
                            Bora Viajar?
                        </p>
                    </div>
                </div>
            </div>
           
        </div>
    </div>
@endsection
