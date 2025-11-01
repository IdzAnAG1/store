{{-- resources/views/dashboard/admin.blade.php --}}
@extends('layouts.main')

@section('title', 'Админ-панель')

@section('content')
    <div
        x-data="adminPage()"
        x-init="init()"
        class="space-y-8">

        {{-- Заголовок + быстрые действия --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-black bg-clip-text text-transparent bg-gradient-to-r from-amber-500 via-amber-350 to-white">
                    Админ-панель
                </h1>
                <p class="text-sm text-gray-400 mt-1">
                    Управление магазином: товары, заказы, пользователи и аналитика.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button @click="syncCatalog()" class="inline-flex items-center gap-2 rounded-xl ">
                    <svg width="16" height="16" viewBox="0 0 24 24"><path fill="currentColor" d="M12 6V3L8 7l4 4V8c2.76 0 5 2.24 5 5c0 .34-.03.67-.1.98l1.53 1.53C18.8 14.71 19 13.88 19 13c0-3.87-3.13-7-7-7m-6.9.02L3.57 7.55C3.2 9.29 3 10.12 3 11c0 3.87 3.13 7 7 7v3l4-4l-4-4v3c-2.76 0-5-2.24-5-5c0-.34.03-.67.1-.98z"/></svg>
                    Синхронизировать каталог
                </button>
                <a href="{{ url('/admin/products/create') }}" class="inline-flex items-center gap-2 rounded-xl bg-amber-500/90 hover:bg-amber-500 text-black ring-1 ring-amber-300/40 px-4 py-2 text-sm font-semibold transition">
                    <svg width="16" height="16" viewBox="0 0 24 24"><path fill="currentColor" d="M19 13H5v-2h14m-7 9a9 9 0 1 1 0-18a9 9 0 0 1 0 18"/></svg>
                    Новый товар
                </a>
            </div>
        </div>

        {{-- Метрики --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="rounded-2xl ring-1 supports-[backdrop-filter]:bg-white/5 p-5">
                <div class="text-sm text-gray-400">Выручка (24ч)</div>
                <div class="mt-2 text-2xl font-bold" x-text="fmtCurrency(stats.revenue_24h)"></div>
                <div class="mt-1 text-xs" :class="stats.revenue_delta >=0 ? 'text-emerald-400' : 'text-red-400'">
                    <span x-text="trendText(stats.revenue_delta)"></span> за сутки
                </div>
            </div>
            <div class="rounded-2xl ring-1 supports-[backdrop-filter]:bg-white/5  p-5">
                <div class="text-sm text-gray-400">Заказы (24ч)</div>
                <div class="mt-2 text-2xl font-bold" x-text="stats.orders_24h"></div>
                <div class="mt-1 text-xs" :class="stats.orders_delta >=0 ? 'text-emerald-400' : 'text-red-400'">
                    <span x-text="trendText(stats.orders_delta)"></span> за сутки
                </div>
            </div>
            <div class="rounded-2xl ring-1 supports-[backdrop-filter]:bg-white/5 p-5">
                <div class="text-sm text-gray-400">Новые пользователи</div>
                <div class="mt-2 text-2xl font-bold" x-text="stats.new_users"></div>
                <div class="mt-1 text-xs text-gray-400">за последние 7 дней</div>
            </div>
            <div class="rounded-2xl ring-1 supports-[backdrop-filter]:bg-white/5 p-5">
                <div class="text-sm text-gray-400">Остатки (крит.)</div>
                <div class="mt-2 text-2xl font-bold" x-text="stats.low_stock"></div>
                <div class="mt-1 text-xs text-gray-400">SKU на минимуме</div>
            </div>
        </div>

        {{-- Табы --}}
        <div x-data="{tab: 'orders'}" class="space-y-4 mt-4">
            <div class="flex items-center gap-2">
                <button
                    @click="tab='orders'"
                    :class="tab==='orders' ? 'bg-white/10 ring-white/20' : 'bg-white/5 ring-white/10 hover:bg-white/10'"
                    class="px-3 py-2 rounded-lg ring-1 text-sm transition">
                    Заказы
                </button>
                <button
                    @click="tab='products'"
                    :class="tab==='products' ? 'bg-white/10 ring-white/20' : 'bg-white/5 ring-white/10 hover:bg-white/10'"
                    class="px-3 py-2 rounded-lg ring-1 text-sm transition">
                    Товары
                </button>
                <button
                    @click="tab='users'"
                    :class="tab==='users' ? 'bg-white/10 ring-white/20' : 'bg-white/5 ring-white/10 hover:bg-white/10'"
                    class="px-3 py-2 rounded-lg ring-1 text-sm transition">
                    Пользователи
                </button>

                <div class="ml-auto relative">
                    <input
                        x-model="filters.query"
                        @input.debounce.300ms="applyFilter()"
                        type="search" placeholder="Поиск…"
                        class="w-64 bg-white/5 ring-1 ring-white/10 rounded-lg px-3 py-2 text-sm placeholder:text-gray-400 focus:outline-none">
                    <div class="absolute right-2 top-2.5 opacity-70">
                        <svg width="16" height="16" viewBox="0 0 24 24"><path fill="currentColor" d="m21.53 20.47l-4.8-4.8A7.5 7.5 0 1 0 4.5 12a7.5 7.5 0 0 0 12.66 5.23zM6 12a6 6 0 1 1 6 6a6 6 0 0 1-6-6"/></svg>
                    </div>
                </div>
            </div>

            {{-- Таб "Заказы" --}}
            <div x-show="tab==='orders'" x-cloak class="rounded-2xl ring-1 ring-white/10 bg-white/5 p-4 mt-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-gray-400">
                        <tr class="border-b border-white/10">
                            <th class="text-left py-2 px-2">#</th>
                            <th class="text-left py-2 px-2">Покупатель</th>
                            <th class="text-left py-2 px-2">Сумма</th>
                            <th class="text-left py-2 px-2">Статус</th>
                            <th class="text-left py-2 px-2">Создан</th>
                            <th class="py-2 px-2"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="o in filteredOrders" :key="o.id">
                            <tr class="border-b border-white/5 hover:bg-white/5">
                                <td class="py-2 px-2" x-text="o.id"></td>
                                <td class="py-2 px-2" x-text="o.customer"></td>
                                <td class="py-2 px-2" x-text="fmtCurrency(o.total)"></td>
                                <td class="py-2 px-2">
                                    <span class="text-xs px-2 py-1 rounded-lg ring-1"
                                          :class="statusClass(o.status)"
                                          x-text="statusText(o.status)">
                                    </span>
                                </td>
                                <td class="py-2 px-2" x-text="formatDate(o.created_at)"></td>
                                <td class="py-2 px-2 text-right">
                                    <a :href="`/admin/orders/${o.id}`" class="text-amber-300 hover:text-amber-200">Открыть</a>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredOrders.length===0">
                            <td colspan="6" class="py-6 text-center text-gray-400">Нет заказов</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Таб "Товары" --}}
            <div x-show="tab==='products'" x-cloak class="rounded-2xl ring-1 ring-white/10 bg-white/5 p-4 mt-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-gray-400">
                        <tr class="border-b border-white/10">
                            <th class="text-left py-2 px-2">Артикул</th>
                            <th class="text-left py-2 px-2">Название</th>
                            <th class="text-left py-2 px-2">Цена</th>
                            <th class="text-left py-2 px-2">Остаток</th>
                            <th class="py-2 px-2"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="p in filteredProducts" :key="p.sku">
                            <tr class="border-b border-white/5 hover:bg-white/5">
                                <td class="py-2 px-2" x-text="p.sku"></td>
                                <td class="py-2 px-2">
                                    <div class="flex items-center gap-2">
                                        <div class="h-8 w-8 rounded-md bg-white/10 ring-1 ring-white/10 overflow-hidden">
                                            <img :src="p.image ?? 'https://via.placeholder.com/64x64?text=IMG'" class="h-full w-full object-cover" alt="">
                                        </div>
                                        <a :href="`/products/${p.id}`" class="hover:text-white" x-text="p.name"></a>
                                    </div>
                                </td>
                                <td class="py-2 px-2" x-text="fmtCurrency(p.price)"></td>
                                <td class="py-2 px-2">
                                    <span :class="p.stock <= 3 ? 'text-red-300' : 'text-emerald-300'" x-text="p.stock"></span>
                                </td>
                                <td class="py-2 px-2 text-right">
                                    <a :href="`/admin/products/${p.id}/edit`" class="text-amber-300 hover:text-amber-200">Редактировать</a>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredProducts.length===0">
                            <td colspan="5" class="py-6 text-center text-gray-400">Нет товаров</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Таб "Пользователи" --}}
            <div x-show="tab==='users'" x-cloak class="rounded-2xl ring-1 ring-white/10 bg-white/5 p-4 mt-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-gray-400">
                        <tr class="border-b border-white/10">
                            <th class="text-left py-2 px-2">ID</th>
                            <th class="text-left py-2 px-2">Имя</th>
                            <th class="text-left py-2 px-2">Email</th>
                            <th class="text-left py-2 px-2">Роль</th>
                            <th class="text-left py-2 px-2">Создан</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="u in filteredUsers" :key="u.user_id ?? u.id">
                            <tr class="border-b border-white/5 hover:bg-white/5">
                                <td class="py-2 px-2" x-text="u.user_id ?? u.id"></td>
                                <td class="py-2 px-2" x-text="u.username ?? '-'"></td>
                                <td class="py-2 px-2" x-text="u.email"></td>
                                <td class="py-2 px-2">
                                    <span class="text-xs px-2 py-1 rounded-lg ring-1 ring-white/15 bg-white/5" x-text="u.role?.role_name ?? 'customer'"></span>
                                </td>
                                <td class="py-2 px-2" x-text="formatDate(u.created_at)"></td>
                            </tr>
                        </template>
                        <tr x-show="filteredUsers.length===0">
                            <td colspan="5" class="py-6 text-center text-gray-400">Нет пользователей</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- Тосты / статусный бар --}}
        <div class="fixed bottom-4 right-4" x-show="toast.show" x-transition x-cloak>
            <div class="rounded-xl bg-white/10 ring-1 ring-white/10 px-4 py-2 text-sm">
                <span x-text="toast.text"></span>
            </div>
        </div>

    </div>

    {{-- ЛОГИКА СТРАНИЦЫ --}}
    <script>
        function adminPage() {
            return {
                // данные
                me: null,
                stats: { revenue_24h: 0, revenue_delta: 0, orders_24h: 0, orders_delta: 0, new_users: 0, low_stock: 0 },
                orders: [],
                products: [],
                users: [],
                // фильтры
                filters: { query: '' },
                // тост
                toast: { show: false, text: '' },

                get filteredOrders() {
                    const q = this.filters.query.toLowerCase().trim();
                    if (!q) return this.orders;
                    return this.orders.filter(o =>
                        String(o.id).includes(q) ||
                        (o.customer ?? '').toLowerCase().includes(q) ||
                        String(o.total).includes(q)
                    );
                },
                get filteredProducts() {
                    const q = this.filters.query.toLowerCase().trim();
                    if (!q) return this.products;
                    return this.products.filter(p =>
                        (p.name ?? '').toLowerCase().includes(q) ||
                        (p.sku ?? '').toLowerCase().includes(q)
                    );
                },
                get filteredUsers() {
                    const q = this.filters.query.toLowerCase().trim();
                    if (!q) return this.users;
                    return this.users.filter(u =>
                        (u.username ?? '').toLowerCase().includes(q) ||
                        (u.email ?? '').toLowerCase().includes(q) ||
                        (u.role?.role_name ?? '').toLowerCase().includes(q)
                    );
                },

                async init() {
                    // проверяем токен
                    const token = localStorage.getItem('auth_token');
                    if (!token) {
                        this.redirect('/auth/login');
                        return;
                    }

                    // тянем текущего пользователя
                    const meRes = await fetch('/api/v1/user', {
                        headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
                    }).catch(() => null);

                    if (!meRes || !meRes.ok) {
                        this.redirect('/auth/login');
                        return;
                    }

                    this.me = await meRes.json();
                    const role = (this.me.role?.role_name || '').toLowerCase();
                    if (role !== 'admin') {
                        // сюда можно поставить страницу менеджера, если нужно
                        this.redirect('/dashboard/user');
                        return;
                    }

                    // грузим метрики/данные (с поэтапной деградацией)
                    await Promise.allSettled([
                        this.loadStats(token),
                        this.loadOrders(token),
                        this.loadProducts(token),
                        this.loadUsers(token),
                    ]);
                },

                async loadStats(token) {
                    try {
                        const r = await fetch('/api/v1/admin/stats', {
                            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
                        });
                        if (r.ok) { this.stats = await r.json(); return; }
                    } catch (_) {}
                    // fallback — мок
                    this.stats = { revenue_24h: 245000, revenue_delta: +7.4, orders_24h: 39, orders_delta: -3.0, new_users: 12, low_stock: 5 };
                },

                async loadOrders(token) {
                    try {
                        const r = await fetch('/api/v1/orders?limit=25', {
                            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
                        });
                        if (r.ok) { this.orders = await r.json(); return; }
                    } catch (_) {}
                    // fallback — мок
                    this.orders = [
                        { id: 1029, customer: 'Иван Петров', total: 12990, status: 'paid', created_at: new Date().toISOString() },
                        { id: 1028, customer: 'ООО Альфа', total: 54990, status: 'shipped', created_at: new Date(Date.now()-86400000).toISOString() },
                        { id: 1027, customer: 'Мария Сидорова', total: 3990, status: 'new', created_at: new Date(Date.now()-3600*1000).toISOString() },
                    ];
                },

                async loadProducts(token) {
                    try {
                        const r = await fetch('/api/v1/products?limit=50', {
                            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
                        });
                        if (r.ok) { this.products = (await r.json()).products ?? await r.json(); return; }
                    } catch (_) {}
                    // fallback — мок
                    this.products = [
                        { id: 1, sku: 'NB-001', name: 'Ноутбук 15"', price: 69990, stock: 2, image: null },
                        { id: 2, sku: 'PH-010', name: 'Смартфон X', price: 39990, stock: 11, image: null },
                        { id: 3, sku: 'VC-221', name: 'Пылесос Pro', price: 15990, stock: 0, image: null },
                    ];
                },

                async loadUsers(token) {
                    try {
                        const r = await fetch('/api/v1/users?limit=50', {
                            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
                        });
                        if (r.ok) { this.users = await r.json(); return; }
                    } catch (_) {}
                    // fallback — мок
                    this.users = [
                        { id: 1, username: 'admin', email: 'admin@example.com', role: { role_name: 'admin' }, created_at: new Date().toISOString() },
                        { id: 2, username: 'manager1', email: 'mgr@example.com', role: { role_name: 'manager' }, created_at: new Date().toISOString() },
                        { id: 3, username: 'user123', email: 'u@example.com', role: { role_name: 'customer' }, created_at: new Date().toISOString() },
                    ];
                },

                // действия
                async syncCatalog() {
                    this.toastShow('Запустили синхронизацию…');
                    const token = localStorage.getItem('auth_token');
                    try {
                        const r = await fetch('/api/v1/admin/sync', {
                            method: 'POST',
                            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
                        });
                        if (r?.ok) this.toastShow('Синхронизация завершена');
                        else this.toastShow('Не удалось синхронизировать');
                    } catch (e) {
                        this.toastShow('Ошибка сети при синхронизации');
                    }
                },

                // утилиты
                redirect(path) { window.location.href = path; },
                toastShow(text) { this.toast.text = text; this.toast.show = true; setTimeout(()=>this.toast.show=false, 2200); },
                applyFilter() { /* реактивные геттеры сами отфильтруют */ },
                fmtCurrency(v) { if (v==null) return '—'; return new Intl.NumberFormat('ru-RU',{style:'currency',currency:'RUB'}).format(v); },
                formatDate(s) { try { return new Date(s).toLocaleString('ru-RU'); } catch { return s ?? '—'; } },
                trendText(d){ const v=Number(d||0); const sign=v>0?'+':''; return `${sign}${v}%`; },
                statusClass(st){
                    const m = { new:'text-gray-300 ring-white/20 bg-white/5', paid:'text-emerald-300 ring-emerald-300/25 bg-emerald-500/10', shipped:'text-amber-200 ring-amber-300/25 bg-amber-500/10', canceled:'text-red-300 ring-red-300/25 bg-red-500/10' };
                    return m[st] ?? 'text-gray-300 ring-white/20 bg-white/5';
                },
                statusText(st){
                    const m = { new:'Новый', paid:'Оплачен', shipped:'Отгружен', canceled:'Отменён' };
                    return m[st] ?? st ?? '—';
                },
            }
        }
    </script>
@endsection
