<footer class="bg-slate-800 border-t border-slate-700 mt-auto">
    <div class="container mx-auto max-w-7xl px-4 py-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            {{-- Branding --}}
            <div class="flex items-center gap-2">
                <img src="{{ asset('storage/res/logo_sm.png') }}" alt="4est" class="h-6 w-6">
                <span class="text-[#39792D] font-bold">4est.info</span>
            </div>

            {{-- Social Links --}}
            <div class="flex items-center gap-4">
                <a href="https://www.youtube.com/@4-est" target="_blank" rel="noopener"
                   class="text-slate-400 hover:text-[#39792D] transition-colors" title="YouTube">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                    </svg>
                </a>
                <a href="https://www.instagram.com/padureionel" target="_blank" rel="noopener"
                   class="text-slate-400 hover:text-[#39792D] transition-colors" title="Instagram">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/>
                    </svg>
                </a>
                <a href="https://www.etsy.com/shop/4estInfo" target="_blank" rel="noopener"
                   class="text-slate-400 hover:text-[#39792D] transition-colors" title="Etsy">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8.559 3.074c0-.155.063-.218.249-.218h5.261c1.402 0 2.645 1.245 3.098 3.258l.093.404h.838l-.093-4.878H17.63c-.155 0-.218.063-.311.249l-.404.809c-.404-.031-2.027-.218-3.429-.218H5.988v.838h.776c1.214 0 1.556.373 1.556 1.618v12.487c0 1.245-.342 1.618-1.556 1.618h-.776v.838h8.628c1.649 0 3.489-.218 4.144-.249l.653-5.156h-.838l-.342 1.12c-.622 2.027-1.865 3.447-4.424 3.447H9.882c-.653 0-.964-.311-.964-.964v-6.244h2.645c1.649 0 2.178.622 2.458 2.178h.838V9.318h-.838c-.28 1.556-.809 2.178-2.458 2.178H8.918V3.074h-.359z"/>
                    </svg>
                </a>
            </div>

            {{-- Copyright --}}
            <p class="text-slate-500 text-sm">
                &copy; 2024-{{ date('Y') }} 4est.info
            </p>
        </div>
    </div>
</footer>
