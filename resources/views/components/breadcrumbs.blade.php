@props(['items' => []])

@if(count($items) > 0)
    <nav aria-label="パンくずリスト" class="mb-6">
        <ol class="flex flex-wrap items-center gap-2 text-sm" style="color: rgba(42, 46, 71, 0.7); font-family: 'Noto Sans JP', sans-serif;">
            @foreach($items as $index => $item)
                <li class="flex items-center">
                    @if($index > 0)
                        <span class="mx-2" style="color: rgba(42, 46, 71, 0.5);">/</span>
                    @endif
                    @if(isset($item['url']) && !$loop->last)
                        <a href="{{ $item['url'] }}" class="hover:text-[#F8A38A] transition-colors" wire:navigate>
                            {{ $item['label'] }}
                        </a>
                    @else
                        <span style="color: #2A2E47; font-weight: 600;">{{ $item['label'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif

