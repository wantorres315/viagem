<div class="mb-4">
    <label for="{{ $name }}" class="block mb-1.5 text-sm font-medium text-gray-700">{{ $label }}</label>
    <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
            @if(isset($icon))
                {!! $icon !!}
            @else
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 14a4 4 0 01-8 0m8 0a4 4 0 00-8 0m8 0V7a4 4 0 10-8 0v7m8 0a4 4 0 01-8 0" />
                </svg>
            @endif
        </span>
        <input type="{{ $type ?? 'text' }}" id="{{ $name }}" name="{{ $name }}" value="{{ old($name, $value ?? '') }}" {{ $attributes }}
            class="block w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-none @error($name) border-red-500 @enderror">
        @error($name)
            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>
