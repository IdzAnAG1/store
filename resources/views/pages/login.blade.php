@extends('layouts.page_without_header')

@section('title', 'Вход в аккаунт')

@section('content')
    <div class="w-full flex items-center justify-center px-4 py-6 sm:py-8">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <a href="{{ url('/') }}" class="flex items-center justify-center mx-auto mb-4">
                <span class="grid h-12 w-10 place-items-center rounded-xl bg-amber-500/10 ring-1 ring-amber-400/30 shadow-[0_0_20px_rgba(245,158,11,0.15)]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M7 8L3 12L7 16" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M17 8L21 12L17 16" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M14 4L9.8589 19.4548" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                </a>
                <h1 class="text-2xl md:text-3xl font-black bg-clip-text text-transparent bg-gradient-to-r from-amber-500 via-amber-350 to-white mb-4">
                    Вход в аккаунт
                </h1>
                <p class="mt-2 text-sm text-gray-400">
                    Войдите, чтобы получить доступ к личному кабинету
                </p>
            </div>

            <div class="rounded-2xl ring-1 ring-white/10 bg-white/10 supports-[backdrop-filter]:bg-white/5 backdrop-blur-md shadow-xl p-5 sm:p-6 md:p-7">
                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-lg bg-red-500/10 ring-1 ring-red-500/30 text-sm text-red-300">
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                <form id="loginForm">
                    @csrf
                    <div class="space-y-4">
                        <div class="mb-2">
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Электронная почта</label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                required
                                autocomplete="email"
                                autofocus
                                value="{{ old('email') }}"
                                class="w-full px-3 py-3 sm:px-4 sm:py-3 rounded-xl bg-white/5 ring-1 ring-white/10 placeholder:text-gray-400 text-white focus:outline-none focus:ring-amber-500/50 focus:bg-white/10 transition text-sm"
                                placeholder="user@example.com"
                            >
                        </div>

                        <div class="mb-2">
                            <div class="flex items-center justify-between mb-2">
                                <label for="password" class="block text-sm font-medium text-gray-300">Пароль</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-sm text-amber-400 hover:text-amber-300 transition">
                                        Забыли пароль?
                                    </a>
                                @endif
                            </div>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                autocomplete="current-password"
                                class="w-full px-3 py-3 sm:px-4 sm:py-3 rounded-xl bg-white/5 ring-1 ring-white/10 placeholder:text-gray-400 text-white focus:outline-none focus:ring-amber-500/50 focus:bg-white/10 transition text-sm"
                                placeholder="••••••••"
                            >
                        </div>

                        <div class="flex items-center mt-4 mb-6">
                            <input
                                id="remember_me"
                                name="remember"
                                type="checkbox"
                                class="h-4 w-4 rounded border-white/20 bg-white/5 text-amber-500 focus:ring-amber-500/50 focus:ring-offset-0"
                            >
                            <label for="remember_me" class="ml-2 block text-sm text-gray-300">
                                Запомнить меня
                            </label>
                        </div>

                        <button
                            type="submit"
                            class="w-full py-2.5 sm:py-3 px-4 rounded-xl bg-amber-500/90 hover:bg-amber-500 text-black font-semibold ring-1 ring-amber-300/40 transition shadow-[0_4px_12px_rgba(245,158,11,0.2)] text-sm sm:text-base"
                        >
                            Войти
                        </button>
                    </div>
                </form>

                <div class="mt-4 py-2 border-t border-white/10 text-center">
                    <p class="text-sm text-gray-400">
                        Нет аккаунта?
                        <a href="{{ route('register') }}" class="hover:text-white transition" >
                            Зарегистрироваться
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('loginForm');
            if (!form) return;

            // Получаем элемент meta
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (!csrfMeta) {
                console.error('CSRF-токен не найден. Добавьте <meta name="csrf-token" content="{{ csrf_token() }}"> в <head>.');
                return;
            }

            // Получаем значение токена
            const csrfToken = csrfMeta.getAttribute('content');

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const data = Object.fromEntries(new FormData(this).entries());

                const response = await fetch('/api/v1/auth', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(data)
                });

                const contentType = response.headers.get('content-type') || '';
                if (!contentType.includes('application/json')) {
                    console.error('Сервер вернул не JSON:', await response.text());
                    return;
                }

                const result = await response.json();

                if (!response.ok) {
                    alert(result.message ?? 'Ошибка авторизации');
                    return;
                }

                // Сохраняем токен
                localStorage.setItem('auth_token', result.access_token);

                // Редиректы по роли
                const role = (result.role || '').toLowerCase();
                const redirectMap = {
                    admin:   'dashboard/admin',   // страница для админа
                    manager: 'dashboard/manager', // страница для менеджера
                    default: 'dashboard/user',    // обычный кабинет
                };

                window.location.href = redirectMap[role] ?? redirectMap.default;
            });
        });
    </script>
@endsection

