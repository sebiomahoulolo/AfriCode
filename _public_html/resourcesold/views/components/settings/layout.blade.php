@props(['heading', 'subheading'])

<div class="space-y-6">
    @if($heading ?? null)
        <div>
            <h3 class="text-lg font-medium">{{ $heading }}</h3>
            @if($subheading ?? null)
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $subheading }}</p>
            @endif
        </div>
    @endif
    {{ $slot }}
</div>
