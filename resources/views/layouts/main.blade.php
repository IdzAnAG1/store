<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', config('app.name'))</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#1A1D21] text-white">
    @include("components.header")

<main class="max-w-6xl mx-auto px-6 py-10 ">
    @yield('content')
</main>

<footer class="max-w-6xl mx-auto px-6 py-8 text-sm text-gray-500">
    © {{ date('Y-m-d') }} {{ config('app.name') }}
</footer>
    <script defer src="https://unpkg.com/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <script>
    function liveSearch() {
        return {
            q: '',
            items: [],
            open: false,
            loading: false,
            ctrl: null,

            async search() {
                const q = this.q.trim();
                this.open = !!q;
                this.items = [];
                if (!q) return;

                // Отменяем предыдущий запрос, если пользователь продолжает печатать
                if (this.ctrl) this.ctrl.abort();
                this.ctrl = new AbortController();

                this.loading = true;
                try {
                    const res = await fetch(`{{ route('search.suggest') }}?q=${encodeURIComponent(q)}`, {
                        signal: this.ctrl.signal
                    });
                    this.items = await res.json();
                } catch (e) {
                    // игнор, если abort
                } finally {
                    this.loading = false;
                }
            },

            close() {
                this.open = false;
            },

            goToResults() {
                // Если хочешь полноценную страницу результатов:
                window.location.href = `/search?q=${encodeURIComponent(this.q)}`;
            },

            formatPrice(p) {
                if (p == null) return '';
                return new Intl.NumberFormat('ru-RU', { style: 'currency', currency: 'RUB' }).format(p);
            }
        }
    }
    function catalogMenu() {
        return {
            open: false,
            panelWidth: 544,
            pos: { left: 0, top: 0 },

            toggle(btn) {
                if (this.open) {
                    this.close();
                    return;
                }
                // Гарантируем, что btn — это элемент
                this.measure(btn);
                this.open = true;
            },

            close() {
                this.open = false;
            },

            measure(btn) {
                // Защита от ошибок
                if (!btn || !btn.getBoundingClientRect) return;

                const rect = btn.getBoundingClientRect();
                this.pos.left = rect.left + window.scrollX;
                this.pos.top = rect.bottom + window.scrollY + 8;
            },

            // Инициализация с отслеживанием ресайза/скролла
            init() {
                const update = () => {
                    if (this.open && this.$refs.catalogBtn) {
                        this.measure(this.$refs.catalogBtn);
                    }
                };

                // Пересчитываем при ресайзе и скролле
                window.addEventListener('resize', update, { passive: true });
                window.addEventListener('scroll', update, { passive: true });

                // Один раз после загрузки — чтобы учесть шрифты, изображения и т.п.
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', update);
                } else {
                    setTimeout(update, 100); // небольшая задержка на layout
                }

                // Отписка при уничтожении (опционально, но хорошо для SPA)
                this.$watch('open', (isOpen) => {
                    if (!isOpen) {
                        // можно убрать слушатели, если нужно
                    }
                });
            }
        }
    }
    </script>
</body>

</html>
