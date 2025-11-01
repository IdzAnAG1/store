@props([
  'title',
  'image' => null,
  'items' => [],
])

<a {{ $attributes->merge(['class' =>
    'block rounded-2xl border border-white/5 bg-[#12161A] ring-1 ring-black/40 hover:border-white/10 transition']) }}
   href="{{ url('/catalog') }}">
    <div class="p-5 grid grid-cols-[80px,1fr] gap-4 items-center">
        <div class="w-20 h-20 overflow-hidden rounded-lg ring-1 ring-white/10 bg-[#0E1115]">
            @if($image)
                <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover" />
            @endif
        </div>
        <div>
            <div class="font-semibold">{{ $title }}</div>
            @if($items)
                <ul class="mt-2 space-y-1 text-sm text-gray-300">
                    @foreach($items as $item)
                        <li class="truncate">— {{ $item }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</a>@props([
  'title',
  'image' => null,
  'items' => [],
])

<a {{ $attributes->merge(['class' =>
    'block rounded-2xl border border-white/5 bg-[#12161A] ring-1 ring-black/40 hover:border-white/10 transition']) }}
   href="{{ url('/catalog') }}">
    <div class="p-5 grid grid-cols-[80px,1fr] gap-4 items-center">
        <div class="w-20 h-20 overflow-hidden rounded-lg ring-1 ring-white/10 bg-[#0E1115]">
            @if($image)
                <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover" />
            @endif
        </div>
        <div>
            <div class="font-semibold">{{ $title }}</div>
            @if($items)
                <ul class="mt-2 space-y-1 text-sm text-gray-300">
                    @foreach($items as $item)
                        <li class="truncate">— {{ $item }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</a>
