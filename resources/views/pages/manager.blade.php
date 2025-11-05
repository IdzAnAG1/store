{{-- resources/views/dashboard/manager.blade.php --}}
@extends('layouts.main')

@section('title','Панель менеджера')

@section('content')
    <div x-data="managerPage()" x-init="init()" class="space-y-8">

        {{-- Заголовок + действия --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-black bg-clip-text text-transparent bg-gradient-to-r from-amber-500 via-amber-350 to-white">
                    Панель менеджера
                </h1>
                <p class="text-sm text-gray-400 mt-1">Управление товарами: создание, описания, фото, остатки, цены.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button
                    @click="openCreate()"
                    class="inline-flex items-center gap-2 rounded-xl
                     bg-gradient-to-r from-amber-500 via-[#F8C15C] to-white
                     text-black ring-1 ring-amber-300/40
                     px-4 py-2 text-sm font-semibold
                     hover:brightness-105 active:brightness-95 transition">
                    <svg width="16" height="16" viewBox="0 0 24 24"><path fill="currentColor" d="M19 13H5v-2h14m-7 9a9 9 0 1 1 0-18a9 9 0 0 1 0 18"/></svg>
                    Новый товар
                </button>
                <button @click="reload()" class="inline-flex items-center gap-2 rounded-xl bg-white/10 hover:bg-white/15 text-white ring-1 ring-white/10 px-4 py-2 text-sm transition">
                    Обновить
                </button>
            </div>
        </div>

        {{-- Фильтры --}}
        <div class="flex flex-wrap items-center gap-3 mb-6">
            <input x-model="filters.q" @input.debounce.250ms="applyFilter()"
                   type="search" placeholder="Поиск: имя, SKU, категория…"
                   class="w-64 bg-white/5 ring-1 ring-white/10 rounded-lg px-3 py-2 text-sm placeholder:text-gray-400 focus:outline-none">
            <select x-model.number="filters.stock" @change="applyFilter()"
                    class="bg-white/5 ring-1 ring-white/10 rounded-lg px-3 py-2 text-sm">
                <option :value="null">Все остатки</option>
                <option value="0">Только нулевые</option>
                <option value="1">> 0</option>
            </select>
            <select x-model="filters.sort" @change="applyFilter()"
                    class="bg-white/5 ring-1 ring-white/10 rounded-lg px-3 py-2 text-sm">
                <option value="-updated_at">Новые изменения</option>
                <option value="price">Цена ↑</option>
                <option value="-price">Цена ↓</option>
                <option value="name">Название A–Z</option>
                <option value="-name">Название Z–A</option>
            </select>
        </div>

        {{-- Таблица товаров --}}
        <div class="rounded-2xl ring-1 ring-white/10 bg-white/5 p-4">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-gray-400">
                    <tr class="border-b border-white/10">
                        <th class="text-left py-2 px-2">ID</th>
                        <th class="text-left py-2 px-2">Фото</th>
                        <th class="text-left py-2 px-2">Название</th>
                        <th class="text-left py-2 px-2">SKU</th>
                        <th class="text-left py-2 px-2">Категория</th>
                        <th class="text-left py-2 px-2">Цена</th>
                        <th class="text-left py-2 px-2">Остаток</th>
                        <th class="py-2 px-2 text-right">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template x-for="p in filtered" :key="p.id">
                        <tr class="border-b border-white/5 hover:bg-white/5">
                            <td class="py-2 px-2" x-text="p.id"></td>
                            <td class="py-2 px-2">
                                <div class="h-10 w-10 rounded-lg bg-white/10 ring-1 ring-white/10 overflow-hidden">
                                    <img :src="p.image ?? 'https://via.placeholder.com/80x80?text=IMG'" class="h-full w-full object-cover" alt="">
                                </div>
                            </td>
                            <td class="py-2 px-2">
                                <a :href="`/products/${p.id}`" class="hover:text-white" x-text="p.name"></a>
                            </td>
                            <td class="py-2 px-2" x-text="p.sku ?? '—'"></td>
                            <td class="py-2 px-2" x-text="p.category_name ?? '—'"></td>
                            <td class="py-2 px-2" x-text="fmtCurrency(p.price)"></td>
                            <td class="py-2 px-2">
              <span :class="p.stock_quantity <= 3 ? 'text-red-300' : 'text-emerald-300'"
                    x-text="p.stock_quantity ?? 0"></span>
                            </td>
                            <td class="py-2 px-2 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <button @click="openEdit(p)" class="text-emerald-300 hover:text-emerald-200">Изм.</button>
                                    <button @click="openImage(p)" class="text-amber-300 hover:text-amber-200">Фото</button>
                                    <button @click="confirmDelete(p)" class="text-red-300 hover:text-red-200">Удалить</button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filtered.length===0">
                        <td colspan="8" class="py-6 text-center text-gray-400">Ничего не найдено</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Модалка: создать/редактировать --}}
        <div x-show="modals.edit.show" x-transition x-cloak class="fixed inset-0 z-50 grid place-items-center bg-black p-4">
            <div class="max-w-2xl rounded-2xl ring-1 ring-black/10 bg-white p-6">
                <h3 class="text-lg text-black font-semibold mb-4" x-text="modals.edit.mode==='create' ? 'Новый товар' : 'Редактировать товар'"></h3>
                <form @submit.prevent="saveProduct">
                    <div class="grid md:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-black text-sm mb-1">Название</label>
                            <input x-model="form.name" required
                                   class="w-full bg-gray-100 text-gray-900 placeholder:text-gray-400
                        border border-gray-300 rounded-lg px-3 py-2
                        focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                        </div>

                        <div>
                            <label class="block text-black text-sm mb-1">SKU</label>
                            <input x-model="form.sku"
                                   class="w-full bg-gray-100 text-gray-900 placeholder:text-gray-400
                        border border-gray-300 rounded-lg px-3 py-2
                        focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                        </div>

                        <div>
                            <label class="block text-black text-sm mb-1">Цена</label>
                            <input type="number" step="0.01" x-model.number="form.price" required
                                   class="w-full bg-gray-100 text-gray-900 placeholder:text-gray-400
                        border border-gray-300 rounded-lg px-3 py-2
                        focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                        </div>

                        <div>
                            <label class="block text-black text-sm mb-1">Остаток</label>
                            <input type="number" x-model.number="form.stock_quantity" min="0"
                                   class="w-full bg-gray-100 text-gray-900 placeholder:text-gray-400
                        border border-gray-300 rounded-lg px-3 py-2
                        focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-black text-sm mb-1">Категория</label>
                            <select x-model.number="form.category_id"
                                    class="w-full bg-gray-100 text-gray-900 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                <option :value="null">— без категории —</option>
                                <template x-for="c in categories" :key="c.id">
                                    <option :value="c.id" x-text="c.name"></option>
                                </template>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Выберите категорию из списка</p>
                        </div>

                        <div class="md:col-span-2 mb-2">
                            <label class="block text-black text-sm mb-1">Описание</label>
                            <textarea x-model="form.description" rows="4"
                                      class="w-full bg-gray-100 text-gray-900 placeholder:text-gray-400
                           border border-gray-300 rounded-lg px-3 py-2
                           focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500"></textarea>
                        </div>

                    </div>

                    <div class="mt-6 flex items-center justify-end gap-2">
                        <button type="button" @click="closeEdit()"
                                class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-black border border-gray-300">
                            Отмена
                        </button>
                        <button type="submit"
                                class="px-4 py-2 rounded-lg bg-gradient-to-r from-amber-500 via-amber-300 to-white
                       text-black font-semibold ring-1 ring-amber-300/40 hover:brightness-105">
                            Сохранить
                        </button>
                    </div>
                </form>
            </div>
        </div>


        {{-- Модалка: фото --}}
        <div x-show="modals.image.show" x-transition x-cloak class="fixed inset-0 z-50 grid place-items-center bg-black/50 p-4">
            <div class="w-full max-w-lg rounded-2xl ring-1 ring-white/10 bg-white/5 p-6">
                <h3 class="text-lg font-semibold mb-4">Фото товара</h3>
                <div class="flex items-start gap-4">
                    <div class="h-28 w-28 rounded-xl overflow-hidden ring-1 ring-white/10 bg-white/10">
                        <img :src="current?.image ?? 'https://via.placeholder.com/120x120?text=IMG'" class="h-full w-full object-cover" alt="">
                    </div>
                    <div class="flex-1">
                        <input type="file" x-ref="file" accept="image/*"
                               class="block w-full text-sm file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-emerald-500/90 file:text-black hover:file:bg-emerald-500">
                        <p class="text-xs text-gray-400 mt-1">PNG/JPG до 5 МБ.</p>
                        <div class="mt-3 flex gap-2">
                            <button @click="uploadImage" class="px-4 py-2 rounded-lg bg-emerald-500/90 hover:bg-emerald-500 text-black ring-1 ring-emerald-300/40 font-semibold">Загрузить</button>
                            <button @click="removeImage" class="px-4 py-2 rounded-lg bg-red-500/20 hover:bg-red-500/30 text-red-200 ring-1 ring-red-300/30">Удалить</button>
                        </div>
                    </div>
                </div>
                <div class="mt-6 text-right">
                    <button @click="closeImage()" class="px-4 py-2 rounded-lg bg-white/10 hover:bg-white/15 ring-1 ring-white/10">Готово</button>
                </div>
            </div>
        </div>

        {{-- Тост --}}
        <div class="fixed bottom-4 right-4" x-show="toast.show" x-transition x-cloak>
            <div class="rounded-xl bg-white/10 ring-1 ring-white/10 px-4 py-2 text-sm">
                <span x-text="toast.text"></span>
            </div>
        </div>

    </div>

    <script>
        function managerPage(){
            return {
                // данные
                me:null,
                items:[],
                filtered:[],
                current:null,
                filters:{ q:'', stock:null, sort:'-updated_at' },
                modals:{
                    edit:{ show:false, mode:'create' },
                    image:{ show:false }
                },
                form:{ id:null, name:'', sku:'', price:0, stock_quantity:0, description:'', category_id:null, category_name:'', image:null },

                async init(){
                    const token = localStorage.getItem('auth_token');
                    if(!token){ return this.redirect('/auth/login'); }

                    // Проверим роль — только manager
                    const meRes = await fetch('/api/v1/user', { headers:{ 'Authorization':'Bearer '+token, 'Accept':'application/json' } }).catch(()=>null);
                    if(!meRes || !meRes.ok){ return this.redirect('/auth/login'); }
                    this.me = await meRes.json();
                    const role = (this.me.role?.role_name || '').toLowerCase();
                    if(role !== 'manager'){ return this.redirect('/dashboard/user'); }

                    await this.reload();
                },

                async reload(){
                    const token = localStorage.getItem('auth_token');
                    try{
                        const r = await fetch('/api/v1/products?limit=200', { headers:{ 'Authorization':'Bearer '+token, 'Accept':'application/json' }});
                        if(r.ok){
                            const payload = await r.json();
                            // поддержим оба варианта ответа
                            this.items = (payload.products ?? payload ?? []).map(p => ({
                                id: p.id ?? p.product_id,
                                name: p.name,
                                sku: p.sku,
                                price: Number(p.price),
                                stock_quantity: Number(p.stock_quantity ?? p.stock ?? 0),
                                description: p.description ?? '',
                                category_id: p.category_id ?? null,
                                category_name: p.category?.category_name ?? p.category_name ?? null,
                                image: p.image_url ?? p.image ?? null,
                                updated_at: p.updated_at ?? null,
                            }));
                            this.applyFilter();
                            this.toastShow('Данные обновлены');
                            return;
                        }
                    }catch(_){}
                    // Если API не готов — оставим пусто
                    this.items = []; this.filtered = [];
                },

                applyFilter(){
                    const q = this.filters.q.toLowerCase().trim();
                    let arr = this.items.slice();

                    if(q){
                        arr = arr.filter(p =>
                            (p.name||'').toLowerCase().includes(q) ||
                            (p.sku||'').toLowerCase().includes(q) ||
                            (p.category_name||'').toLowerCase().includes(q)
                        );
                    }
                    if(this.filters.stock === 0){ arr = arr.filter(p => (p.stock_quantity ?? 0) === 0); }
                    if(this.filters.stock === 1){ arr = arr.filter(p => (p.stock_quantity ?? 0) > 0); }

                    const s = this.filters.sort;
                    const desc = s.startsWith('-'); const key = desc ? s.slice(1) : s;
                    arr.sort((a,b)=>{
                        const av = a[key] ?? ''; const bv = b[key] ?? '';
                        if(av==bv) return 0;
                        return (av>bv ? 1 : -1) * (desc ? -1 : 1);
                    });

                    this.filtered = arr;
                },

                // модалки
                openCreate(){
                    this.modals.edit.mode = 'create';
                    this.form = { id:null, name:'', sku:'', price:0, stock_quantity:0, description:'', category_id:null, category_name:'', image:null };
                    this.modals.edit.show = true;
                },
                openEdit(p){
                    this.modals.edit.mode = 'edit';
                    this.form = { ...p };
                    this.modals.edit.show = true;
                },
                closeEdit(){ this.modals.edit.show = false; },

                openImage(p){ this.current = p; this.modals.image.show = true; },
                closeImage(){ this.modals.image.show = false; this.current=null; },

                // CRUD
                async saveProduct(){
                    const token = localStorage.getItem('auth_token');
                    const isCreate = this.modals.edit.mode === 'create';
                    const url = isCreate ? '/api/v1/manager/products' : `/api/v1/manager/products/${this.form.id}`;
                    const method = isCreate ? 'POST' : 'PUT';

                    const body = {
                        name: this.form.name,
                        sku: this.form.sku || null,
                        price: Number(this.form.price),
                        stock_quantity: Number(this.form.stock_quantity || 0),
                        description: this.form.description || null,
                        // если у тебя категории по id — передавай category_id
                        category_id: this.form.category_id ?? null,
                    };

                    try{
                        const r = await fetch(url, {
                            method,
                            headers:{ 'Authorization':'Bearer '+token, 'Accept':'application/json', 'Content-Type':'application/json' },
                            body: JSON.stringify(body)
                        });
                        if(!r.ok){ throw new Error('save failed'); }
                        this.toastShow(isCreate ? 'Товар создан' : 'Товар сохранён');
                        this.modals.edit.show = false;
                        await this.reload();
                    }catch(e){
                        this.toastShow('Ошибка сохранения');
                    }
                },

                async confirmDelete(p){
                    if(!confirm(`Удалить товар «${p.name}»?`)) return;
                    const token = localStorage.getItem('auth_token');
                    try{
                        const r = await fetch(`/api/v1/manager/products/${p.id}`, {
                            method:'DELETE',
                            headers:{ 'Authorization':'Bearer '+token, 'Accept':'application/json' }
                        });
                        if(!r.ok) throw 0;
                        this.toastShow('Товар удалён');
                        await this.reload();
                    }catch(_){ this.toastShow('Ошибка удаления'); }
                },

                async uploadImage(){
                    if(!this.current){ return; }
                    const file = this.$refs.file.files?.[0];
                    if(!file){ return this.toastShow('Выберите файл'); }
                    if(file.size > 5*1024*1024){ return this.toastShow('Файл больше 5 МБ'); }

                    const token = localStorage.getItem('auth_token');
                    const fd = new FormData();
                    fd.append('image', file);

                    try{
                        const r = await fetch(`/api/v1/manager/products/${this.current.id}/image`, {
                            method:'POST',
                            headers:{ 'Authorization':'Bearer '+token, 'Accept':'application/json' },
                            body: fd
                        });
                        if(!r.ok) throw 0;
                        this.toastShow('Фото обновлено');
                        this.$refs.file.value = '';
                        await this.reload();
                    }catch(_){ this.toastShow('Ошибка загрузки фото'); }
                },

                async removeImage(){
                    if(!this.current) return;
                    const token = localStorage.getItem('auth_token');
                    try{
                        const r = await fetch(`/api/v1/manager/products/${this.current.id}/image`, {
                            method:'DELETE',
                            headers:{ 'Authorization':'Bearer '+token, 'Accept':'application/json' }
                        });
                        if(!r.ok) throw 0;
                        this.toastShow('Фото удалено');
                        await this.reload();
                    }catch(_){ this.toastShow('Не удалось удалить фото'); }
                },

                // утилиты
                redirect(p){ window.location.href = p; },
                toastShow(t){ this.toast={show:true,text:t}; setTimeout(()=>this.toast.show=false,2200); },
                fmtCurrency(v){ if(v==null) return '—'; return new Intl.NumberFormat('ru-RU',{style:'currency',currency:'RUB'}).format(v); },
                toast:{show:false,text:''},
            }
        }
    </script>
@endsection
