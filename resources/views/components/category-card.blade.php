@props([
  'title',
  'image' => null,
  'tags' => [],
])

<a {{ $attributes->merge(['class' =>
    'group relative overflow-hidden rounded-2xl border border-white/5 bg-[#111418] ring-1 ring-black/40
     hover:border-amber-500/30 hover:ring-amber-500/20 transition']) }} href="{{ url('/catalog') }}">
    <div class="p-5 flex items-center gap-4">
        <div class="shrink-0 w-28 h-28 overflow-hidden rounded-xl ring-1 ring-white/10 bg-[#0E1115]">
            @if($image)
                <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover group-hover:scale-105 transition" />
            @endif
        </div>
        <div class="min-w-0">
            <div class="text-base font-semibold text-white">{{ $title }}</div>
            @if($tags)
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach($tags as $tag)
                        <span class="text-xs text-gray-300 bg-white/5 ring-1 ring-white/10 px-2 py-1 rounded-md">
              {{ $tag }}
            </span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</a>
