<section class="relative left-1/2 right-1/2 -mx-[50vw] w-screen h-[480px] overflow-hidden">
    {{-- фон --}}
    <img src="https://rusnord.ru/uploads/posts/2025-01/40a9c329dba2278c9775798067ebae2d.jpg"
         alt="" class="absolute inset-0 h-full w-full object-cover" />

    {{-- затемнение --}}
    <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/30 to-transparent"></div>

    {{-- контент --}}
    <div class="z-10 mx-auto max-w-7xl h-full
              px-7 md:px-10 lg:px-12
              py-6 md:py-10
              flex flex-col md:flex-row items-stretch
              gap-8 md:gap-10 lg:gap-12">
        {{-- левая колонка (слайдер) --}}
        <div class="w-full md:w-1/2 lg:w-[56%]">
            <div class="h-full rounded-2xl ring-1 ring-white/10
          bg-white/10 supports-[backdrop-filter]:bg-white/5 backdrop-blur-md
          shadow-[0_10px_30px_rgba(0,0,0,0.25)]
          p-0">
                <div class="h-full w-full rounded-2xl overflow-hidden">
                    <img
                        src="https://www.ferra.ru/imgs/2018/11/26/18/2681499/8dd4e1177f139cb2502f586b9add534ce0af6885.jpg"
                        alt="Product image"
                        class="w-full h-full object-fill"
                    >
                </div>
            </div>
        </div>

        {{-- правая колонка (текст) --}}
        <div class="w-full md:w-1/2 lg:w-[44%] self-center">
            <div class="max-w-xl md:max-w-none space-y-6 md:space-y-7 lg:space-y-8">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold tracking-tight text-white
                   leading-tight text-balance drop-shadow-[0_2px_10px_rgba(0,0,0,0.35)]">
                    Топ-электроника для дома и работы
                </h1>

                <p class="backdrop-blur-xl
                  rounded-2xl
                  text-white/90 md:text-lg leading-relaxed
                  p-6 md:p-8 lg:p-10">
                    Ноутбуки, смартфоны, умные устройства и аксессуары. Премиальный выбор, честные цены, быстрая доставка.
                </p>

                <div class="mt-8 relative z-10">
                    <div class="flex items-center justify-between">
                        <a href="{{ url('/api/v1/products') }}" class="inline-flex h-12 items-center justify-center rounded-xl px-6 bg-amber-500/90 hover:bg-amber-400 text-black font-semibold ring-1 ring-amber-300/40 transition">
                            Смотреть каталог
                        </a>
                        <a href="{{ url('/') }}" class="backdrop-blur-xl inline-flex h-12 items-center justify-center rounded-xl px-6 bg-white/10 hover:bg-white/15 text-white font-medium ring-1 ring-white/10 transition">
                            Акции и новинки
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
{{--

    Todo Сделать докер контейнеры для апп и для бд и начать писать CI/CD

--}}
