<header class="sticky top-0 z-[99999] w-full bg-gradient-to-b from-[#0B0E11] to-[#12161A] text-white/90 shadow-[0_1px_0_0_rgba(255,255,255,0.06)]">
    <!-- Верхняя узкая полоса -->
    <div class="mx-auto max-w-7xl px-6">
        <div class="flex items-center justify-between py-2 text-xs text-gray-300">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-2">
                    <svg width="14" height="14" viewBox="0 0 24 24" class="opacity-70">
                        <path fill="currentColor" d="M12 2C7.03 2 3 6.03 3 11c0 5.25 7.38 10.64 8.04 11.09a1 1 0 0 0 1.12 0C13.62 21.64 21 16.25 21 11c0-4.97-4.03-9-9-9m0 12.5A3.5 3.5 0 1 1 12 7.5a3.5 3.5 0 0 1 0 7Z"/>
                    </svg>
                    Москва
                </span>
                <a href="{{ url('/deals') }}" class="hover:text-white transition">Акции</a>
                <a href="{{ url('/stores') }}" class="hover:text-white transition">Магазины</a>
            </div>
            <div class="flex items-center gap-4">
                <a href="tel:88005553535" class="hover:text-white transition">8-800-555-35-35</a>
                <a href="https://t.me/Susano_no_mikoto" target="_blank" rel="noopener" class="hover:text-white transition">
                    @include('svg.telegram')
                </a>
            </div>
        </div>
    </div>

    <!-- Средняя полоса (логотип + поиск + иконки) -->
    <div class="relative z-[9000] backdrop-blur supports-[backdrop-filter]:bg-white/5 bg-white/0">
        <div class="mx-auto max-w-7xl px-6 py-4">
            <div class="flex items-center gap-4">
                <!-- Бургер -->
                <button
                    x-data="{open:false}"
                    @click="open = !open; document.getElementById('mobileMenu').classList.toggle('hidden')"
                    class="md:hidden -ml-2 inline-flex h-10 w-10 items-center justify-center rounded-lg ring-1 ring-white/10 hover:bg-white/5">
                    <svg width="22" height="22" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M3 6h18v2H3zm0 5h18v2H3zm0 5h18v2H3z"/>
                    </svg>
                </button>

                <!-- Логотип -->
                <a href="{{ url('/') }}" class="group relative inline-flex items-center gap-3 shrink-0">
                    <span class="grid h-9 w-9 place-items-center rounded-xl bg-amber-500/10 ring-1 ring-amber-400/30 shadow-[0_0_20px_rgba(245,158,11,0.15)] group-hover:ring-amber-300/50 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30px" height="30px" viewBox="0 0 24 24" fill="none">
                            <path d="M7 8L3 12L7 16" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M17 8L21 12L17 16" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M14 4L9.8589 19.4548" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span class="inline-block leading-none tracking-[.01em] text-2xl md:text-3xl lg:text-[2rem] font-black">
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-amber-500 via-amber-350 to-white drop-shadow-[0_1px_0_rgba(0,0,0,0.6)]">
                            {{ config('app.name') }}
                        </span>
                        <span class="block h-px w-full bg-gradient-to-r from-transparent via-amber-400/60 to-transparent scale-x-0 group-hover:scale-x-100 origin-center transition"></span>
                    </span>
                </a>

                <!-- Поиск -->
                <form x-data="liveSearch()" class="flex-1" @submit.prevent>
                    <div class="flex w-full items-center gap-2 rounded-xl bg-white/5 ring-1 ring-white/10 px-3 py-2" x-ref="searchWrapper">
                        <svg width="18" height="18" viewBox="0 0 24 24" class="opacity-70">
                            <path fill="currentColor" d="m21.53 20.47l-4.8-4.8A7.5 7.5 0 1 0 4.5 12a7.5 7.5 0 0 0 12.66 5.23l4.8 4.8zM6 12a6 6 0 1 1 6 6a6 6 0 0 1-6-6"/>
                        </svg>

                        <input
                            x-model="q"
                            @input.debounce.300ms="search"
                            @keydown.escape="close()"
                            @focus="open = !!q"
                            class="w-full bg-transparent placeholder:text-gray-400 text-sm focus:outline-none"
                            type="search" name="q" autocomplete="off" placeholder="Поиск по каталогу…">

                        <button type="button" class="rounded-lg bg-amber-500/90 hover:bg-amber-500 text-black px-4 py-2 text-sm font-medium transition"
                                @click="goToResults()">
                            Найти
                        </button>
                    </div>

                    <!-- Выпадающий список поиска (телепорт) -->
                    <template x-teleport="body">
                        <div
                            x-cloak
                            x-show="open"
                            x-transition.opacity.scale
                            @click.outside="close()"
                            class="fixed z-[9500] rounded-xl bg-[#1A1D21] backdrop-blur-xl ring-1 ring-white/10 shadow-lg"
                            :style="{
                                left: $refs.searchWrapper.getBoundingClientRect().left + window.scrollX + 'px',
                                top:  $refs.searchWrapper.getBoundingClientRect().bottom + window.scrollY + 14 + 'px',
                                width: $refs.searchWrapper.offsetWidth + 'px',
                                maxWidth: 'calc(100vw - 2rem)'
                            }"
                        >
                            <template x-if="loading">
                                <div class="px-4 py-3 text-sm text-white/70">Ищем…</div>
                            </template>

                            <template x-if="!loading && items.length === 0 && q.length">
                                <div class="px-4 py-3 text-sm text-white/50">Ничего не найдено</div>
                            </template>

                            <ul class="max-h-96 overflow-auto divide-y divide-white/5">
                                <template x-for="item in items" :key="item.id">
                                    <li>
                                        <a :href="`/products/${item.id}`"
                                           class="flex items-center justify-between gap-3 px-4 py-3 hover:bg-white/5 transition rounded-lg">
                                            <span class="text-sm" x-text="item.name"></span>
                                            <span class="text-sm text-white/70" x-text="formatPrice(item.price)"></span>
                                        </a>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </template>
                </form>

                <!-- Кнопки справа -->
                <nav class="ml-auto hidden md:flex items-center gap-2">
                    <a href="{{ url('/compare') }}" class="inline-flex items-center gap-2 rounded-lg px-3 py-2 ring-1 ring-white/10 hover:bg-white/5">
                        <svg width="18" height="18" viewBox="0 0 24 24"><path fill="currentColor" d="M3 5h6v14H3zM15 9h6v10h-6z"/></svg>
                        <span class="text-sm">Сравнение</span>
                    </a>
                    <a href="{{ url('/favorites') }}" class="inline-flex items-center gap-2 rounded-lg px-3 py-2 ring-1 ring-white/10 hover:bg-white/5">
                        <svg width="18" height="18" viewBox="0 0 24 24"><path fill="currentColor" d="M12 21.35L10.55 20.03C5.4 15.36 2 12.28 2 8.5C2 6 4 4 6.5 4C8.04 4 9.54 4.81 10.35 6.09C11.16 4.81 12.66 4 14.2 4C16.7 4 18.7 6 18.7 8.5C18.7 12.28 15.3 15.36 10.15 20.04L12 21.35Z"/></svg>
                        <span class="text-sm">Избранное</span>
                    </a>
                    <a href="{{ url('/cart') }}" class="inline-flex items-center gap-2 rounded-lg px-3 py-2 bg-amber-500/90 hover:bg-amber-500 text-black transition">
                        <svg width="18" height="18" viewBox="0 0 24 24"><path fill="currentColor" d="M7 18a2 2 0 1 0 0 4a2 2 0 0 0 0-4m10 0a2 2 0 1 0 0 4a2 2 0 0 0 0-4M7.16 14h9.45a2 2 0 0 0 1.9-1.37L21 6H6.21L5.27 3H2v2h2l3.6 9.59l-.95 2.34A2 2 0 0 0 8.5 19H19v-2H8.42z"/></svg>
                        <span class="text-sm font-medium">Корзина</span>
                    </a>
                    <a href="/auth/login">
                        @include("svg.user")
                    </a>
                </nav>
            </div>

            <!-- Поиск (мобилка) -->
            <form action="{{ url('/search') }}" class="mt-3 md:hidden">
                <div class="flex items-center gap-2 rounded-xl bg-white/5 ring-1 ring-white/10 px-3 py-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" class="opacity-70">
                        <path fill="currentColor" d="m21.53 20.47l-4.8-4.8A7.5 7.5 0 1 0 4.5 12a7.5 7.5 0 0 0 12.66 5.23l4.8 4.8zM6 12a6 6 0 1 1 6 6a6 6 0 0 1-6-6"/>
                    </svg>
                    <input class="w-full bg-transparent placeholder:text-gray-400 text-sm focus:outline-none" type="search" name="q" placeholder="Поиск по каталогу…">
                    <button class="rounded-lg bg-amber-500/90 hover:bg-amber-500 text-black px-3 py-2 text-sm font-medium transition">Найти</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Нижняя полоса (каталог) — переписана под Alpine + teleport -->
    <div class="relative z-[9000] backdrop-blur supports-[backdrop-filter]:bg-white/5 bg-white/0">
        <div class="mx-auto max-w-7xl px-6">
            <div class="hidden md:flex items-center gap-6 py-3 text-sm">

                <!-- Каталог -->
                <div x-data="catalogMenu()" x-init="init()" class="relative">
                    <button
                        x-ref="catalogBtn"
                        type="button"
                        @click="toggle($refs.catalogBtn)"
                        class="inline-flex items-center gap-2 rounded-lg px-3 py-1.5 bg-white/5 ring-1 ring-white/10 hover:bg-white/10 select-none">
                        <svg width="18" height="18" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M3 4h8v8H3zM13 4h8v8h-8zM3 14h8v8H3zM13 14h8v8h-8z"/>
                        </svg>
                        <span>Каталог</span>
                        <svg class="ml-1 transition-transform duration-200" :class="open ? 'rotate-180' : ''" width="14" height="14" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M7 10l5 5 5-5z"/>
                        </svg>
                    </button>

                    <!-- Панель каталога (телепорт) -->
                    <template x-teleport="body">
                        <div
                            x-cloak
                            x-show="open"
                            x-transition.opacity.scale
                            @keydown.window.escape="close()"
                            @click.outside="close()"
                            class="fixed z-[99998]  rounded-2xl bg-[#111418]/95 backdrop-blur-xl ring-1 ring-white/10 shadow-2xl p-3 sm:p-4"
                            :style="{
                                left:  pos.left + 'px',
                                top:   pos.top  + 'px',
                                width: panelWidth + 'px',
                                maxWidth: 'calc(100vw - 2rem)'
                            }"
                        >
                            <div class="px-1 pb-3 border-b border-white/10">
                                <div class="flex items-center gap-3">
                                    <span class="text-sm text-white/80">Категории</span>
                                    <span class="text-[11px] px-2 py-0.5 rounded bg-white/5 ring-1 ring-white/10 text-white/60">все разделы</span>
                                </div>
                            </div>

                            <div class="mt-3 grid grid-cols-2 md:grid-cols-3 gap-2 sm:gap-3">
                                @foreach($rootCategories as $cat)
                                    <div class="rounded-lg ring-1 ring-white/10 hover:bg-white/5 transition p-3">
                                        <a href="{{ url('/catalog/'.$cat->category_id) }}" class="block text-sm font-medium text-white hover:text-amber-300 transition">
                                            {{ $cat->category_name }}
                                        </a>
                                        @if($cat->children->isNotEmpty())
                                            <ul class="mt-2 space-y-1.5">
                                                @foreach($cat->children as $child)
                                                    <li>
                                                        <a href="{{ url('/catalog/'.$child->category_id) }}" class="block pl-3 text-[13px] text-white/70 hover:text-white transition">
                                                            {{ $child->category_name }}
                                                        </a>
                                                        @if($child->children->isNotEmpty())
                                                            <ul class="mt-1 ml-4 border-l border-white/10 pl-3 space-y-1">
                                                                @foreach($child->children as $gchild)
                                                                    <li>
                                                                        <a href="{{ url('/catalog/'.$gchild->category_id) }}" class="block text-[12px] text-white/60 hover:text-white/80 transition">
                                                                            {{ $gchild->category_name }}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-3 px-1 pt-3 border-t border-white/10 flex items-center justify-between">
                                <a href="{{ url('/catalog') }}" class="text-[13px] text-white/70 hover:text-white">Все категории</a>
                                <a href="{{ url('/promo') }}" class="inline-flex items-center gap-1.5 text-[13px] text-amber-300 hover:text-amber-200">
                                    Акции и подборки
                                    <svg width="14" height="14" viewBox="0 0 24 24"><path fill="currentColor" d="m13 5 7 7-7 7v-5H4v-4h9z"/></svg>
                                </a>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Остальные ссылки -->
                <a href="{{ url('/kitchen') }}" class="hover:text-white transition">Кухня</a>
                <a href="{{ url('/home') }}" class="hover:text-white transition">Для дома</a>
                <a href="{{ url('/climate') }}" class="hover:text-white transition">Климат</a>
                <a href="{{ url('/smart') }}" class="hover:text-white transition">Умный дом</a>
                <a href="{{ url('/install') }}" class="hover:text-white transition">Установка</a>
            </div>
            <!-- TODO Убрать две менюшки на мобилке поправить слайдер возможно тоже убрать -->
            <!-- Мобильное меню -->
            <div id="mobileMenu" class="md:hidden hidden">
                <nav class="grid gap-1 py-3">
                    <a href="{{ url('/catalog') }}" class="rounded-lg px-3 py-2 bg-white/5 ring-1 ring-white/10">Каталог</a>
                    <a href="{{ url('/kitchen') }}" class="rounded-lg px-3 py-2 hover:bg-white/5">Кухня</a>
                    <a href="{{ url('/home') }}" class="rounded-lg px-3 py-2 hover:bg-white/5">Для дома</a>
                    <a href="{{ url('/climate') }}" class="rounded-lg px-3 py-2 hover:bg-white/5">Климат</a>
                    <a href="{{ url('/smart') }}" class="rounded-lg px-3 py-2 hover:bg-white/5">Умный дом</a>
                    <a href="{{ url('/install') }}" class="rounded-lg px-3 py-2 hover:bg-white/5">Установка</a>
                </nav>
            </div>
        </div>
    </div>
</header>
