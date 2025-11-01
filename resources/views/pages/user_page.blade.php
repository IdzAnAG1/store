@extends('layouts.main')

@section('title', 'Личный кабинет')

@section('content')
    <div class="max-w-7xl mx-auto">
        {{-- Хлебные крошки --}}
        <nav class="text-sm text-gray-400 mb-6">
            <a href="{{ url('/') }}" class="hover:text-white/80">Главная</a>
            <span class="mx-2 text-white/30">/</span>
            <span class="text-white/70">Личный кабинет</span>
        </nav>

        {{-- Шапка страницы --}}
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-black bg-clip-text text-transparent bg-gradient-to-r from-amber-500 via-amber-300 to-white">
                    Личный кабинет
                </h1>
                <p class="mt-2 text-sm text-gray-400">Управляйте профилем, заказами и настройками</p>
            </div>
            <button id="logout-btn"
                    class="inline-flex items-center gap-2 rounded-xl bg-red-500/20 hover:bg-red-500/30 ring-1 ring-red-500/30 text-red-300 px-4 py-2 text-sm font-medium transition">
                <svg width="16" height="16" viewBox="0 0 24 24"><path fill="currentColor" d="M16 17v-2H7V9h9V7l4 4zM3 5h8V3H3a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8v-2H3z"/></svg>
                Выйти
            </button>
        </div>

        {{-- Контент 2 колонки --}}
        <div class=" grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- Сайдбар --}}
            <aside class=" lg:col-span-4 xl:col-span-3 space-y-6">
                {{-- Карточка пользователя / загрузка --}}
                <div class="rounded-2xl ring-1 ring-white/10 supports-[backdrop-filter]:bg-white/5 p-5">
                    <div id="user-card-skeleton" class="animate-pulse">
                        <div class="h-16 w-16 rounded-full bg-white/10 mb-3"></div>
                        <div class="h-5 bg-white/10 w-40 mb-2 rounded"></div>
                        <div class="h-4 bg-white/10 w-56 rounded"></div>
                    </div>

                    <div id="user-card" class="hidden">
                        <div class="grid h-16 w-16 place-items-center rounded-full bg-amber-500/20 ring-1 ring-amber-400/30 m-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" class="text-amber-300">
                                <path fill="currentColor" d="M12 12a5 5 0 1 0-5-5a5 5 0 0 0 5 5m0 2c-5 0-9 2.5-9 5.5V22h18v-2.5C21 16.5 17 14 12 14"/>
                            </svg>
                        </div>
                        <div class="mb-3 mt-3 text-white font-semibold" id="username">—</div>
                        <div class="mt-2 mb-2 text-sm text-gray-400" id="user-email">—</div>
                        <div class="mt-4 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Телефон</span>
                                <span id="user-phone" class="text-white">—</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Роль</span>
                                <span id="user-role" class="text-white">—</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">С нами с</span>
                                <span id="user-created" class="text-white">—</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Быстрые действия --}}
                <div class="rounded-2xl ring-1 ring-white/10 bg-white/5 p-4">
                    <div class="text-xs uppercase tracking-wide text-white/60 mb-3">Навигация</div>
                    <nav class="grid gap-2 text-sm">
                        <a href="/dashboard/profile" class="flex items-center justify-between rounded-lg px-3 py-2 hover:bg-white/10 transition">
                            Отзывы
                            <svg width="16" height="16" viewBox="0 0 24 24" class="opacity-70"><path fill="currentColor" d="m9 18 6-6-6-6"/></svg>
                        </a>
                        <a href="/dashboard/orders" class="flex items-center justify-between rounded-lg px-3 py-2 hover:bg-white/10 transition">
                            Мои заказы
                            <svg width="16" height="16" viewBox="0 0 24 24" class="opacity-70"><path fill="currentColor" d="m9 18 6-6-6-6"/></svg>
                        </a>
                        <a href="/favorites" class="flex items-center justify-between rounded-lg px-3 py-2 hover:bg-white/10 transition">
                            Избранное
                            <svg width="16" height="16" viewBox="0 0 24 24" class="opacity-70"><path fill="currentColor" d="m9 18 6-6-6-6"/></svg>
                        </a>
                    </nav>
                </div>
            </aside>

            {{-- Основной контент --}}
            <section class="lg:col-span-8 xl:col-span-9 space-y-6">
                {{-- Статусы/ошибки --}}
                <div id="dashboard-status" class="rounded-2xl ring-1 ring-white/10 bg-white/5 p-5 text-gray-400">
                    Загружаем данные…
                </div>

                {{-- Карточки-виджеты --}}
                <div id="widgets" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="/dashboard/orders" class="rounded-2xl ring-1 ring-white/10 bg-white/5 p-4 hover:bg-white/10 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm text-gray-400">Заказы</div>
                                <div class="mt-1 text-lg font-semibold text-white">История и статусы</div>
                            </div>
                            <svg width="28" height="28" viewBox="0 0 24 24" class="text-amber-300">
                                <path fill="currentColor" d="M7 18a2 2 0 1 0 0 4a2 2 0 0 0 0-4m10 0a2 2 0 1 0 0 4a2 2 0 0 0 0-4M7.16 14h9.45a2 2 0 0 0 1.9-1.37L21 6H6.21L5.27 3H2v2h2l3.6 9.59l-.95 2.34A2 2 0 0 0 8.5 19H19v-2H8.42z"/>
                            </svg>
                        </div>
                    </a>

                    <a href="/favorites" class="rounded-2xl ring-1 ring-white/10 bg-white/5 p-4 hover:bg-white/10 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm text-gray-400">Избранное</div>
                                <div class="mt-1 text-lg font-semibold text-white">Товары и подборки</div>
                            </div>
                            <svg width="28" height="28" viewBox="0 0 24 24" class="text-amber-300">
                                <path fill="currentColor" d="M12 21.35L10.55 20.03C5.4 15.36 2 12.28 2 8.5C2 6 4 4 6.5 4C8.04 4 9.54 4.81 10.35 6.09C11.16 4.81 12.66 4 14.2 4C16.7 4 18.7 6 18.7 8.5C18.7 12.28 15.3 15.36 10.15 20.04L12 21.35Z"/>
                            </svg>
                        </div>
                    </a>
                </div>

                {{-- Последние действия (пример заглушки) --}}
                <div id="activity" class="hidden rounded-2xl ring-1 ring-white/10 bg-white/5 p-4">
                    <div class="text-sm text-white/80 font-semibold mb-3">Последние действия</div>

                </div>
            </section>
        </div>
    </div>

    {{-- Скрипт инициализации --}}
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const statusEl   = document.getElementById('dashboard-status');
            const widgetsEl  = document.getElementById('widgets');
            const activityEl = document.getElementById('activity');
            const cardSkel   = document.getElementById('user-card-skeleton');
            const cardEl     = document.getElementById('user-card');
            const logoutBtn  = document.getElementById('logout-btn');

            const token = localStorage.getItem('auth_token');
            if (!token) {
                statusEl.classList.remove('hidden');
                statusEl.innerHTML = '<span class="text-red-400">Требуется авторизация. Перенаправляем…</span>';
                setTimeout(() => location.href = '/login', 1200);
                return;
            }

            try {
                const res = await fetch('/api/v1/user', {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });

                if (!res.ok) throw new Error('Unauthorized');

                const user = await res.json();

                // Заполняем карточку
                document.getElementById('username').textContent   = user.username ?? 'Без имени';
                document.getElementById('user-email').textContent = user.email ?? '—';
                document.getElementById('user-phone').textContent = user.phone ?? '—';
                document.getElementById('user-role').textContent  = (user.role && user.role.role_name) ? user.role.role_name : 'Покупатель';
                document.getElementById('user-created').textContent = new Date(user.created_at).toLocaleDateString('ru-RU');

                // Показ/скрытие
                cardSkel.classList.add('hidden');
                cardEl.classList.remove('hidden');
                statusEl.classList.add('hidden');
                widgetsEl.classList.remove('hidden');
                activityEl.classList.remove('hidden');
            } catch (e) {
                console.error(e);
                localStorage.removeItem('auth_token');
                statusEl.innerHTML = '<span class="text-red-400">Сессия истекла. Перенаправляем на вход…</span>';
                setTimeout(() => location.href = '/login', 1200);
            }

            logoutBtn?.addEventListener('click', () => {
                localStorage.removeItem('auth_token');
                location.href = '/login';
            });
        });
    </script>
@endsection
