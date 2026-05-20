<div class="relative" x-data="{
    dropdownOpen: false,
    toggleDropdown() {
        this.dropdownOpen = !this.dropdownOpen;
    },
    closeDropdown() {
        this.dropdownOpen = false;
    }
}" @click.away="closeDropdown()">
    <!-- User Button -->
    <button
        class="flex items-center text-gray-700"
        @click.prevent="toggleDropdown()"
        type="button"
    >
        

       <span class="block mr-1 font-medium text-theme-sm">{{auth()->user()->name}}</span>

        <!-- Chevron Icon -->
        <svg
            class="w-5 h-5 transition-transform duration-200"
            :class="{ 'rotate-180': dropdownOpen }"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Dropdown Start -->
    <div
        x-show="dropdownOpen"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-[17px] flex w-[260px] flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg z-50"
        style="display: none;"
    >
        <!-- User Info em cima, Sign Out embaixo -->
        <div class="flex flex-col gap-2">
            <div>
                <span class="block font-medium text-gray-700 text-theme-sm">{{ auth()->user()->name }}</span>
                <span class="mt-0.5 block text-theme-xs text-gray-500">{{ auth()->user()->email }}</span>
            </div>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="flex items-center gap-2 px-3 py-2 font-medium text-gray-700 rounded-lg group text-theme-sm hover:bg-gray-100 hover:text-gray-700">
                    <span class="text-gray-500 group-hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Sign out
                    </span>
                </button>
            </form>
        </div>
    </div>
    <!-- Dropdown End -->
</div>
