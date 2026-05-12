@extends('layouts.app')

@section('title', __('About') . ' - 4est.info')

@section('content')
    {{-- Greetings Section --}}
    <section class="mb-12">
        <div class="bg-slate-800 rounded-xl p-8 md:p-12">
            <h1 class="text-3xl md:text-4xl font-bold mb-6">
                {{ app()->getLocale() === 'ro' ? 'Salut!' : 'Hello!' }}
            </h1>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
                <div class="md:col-span-2 space-y-4 text-slate-300">
                    <p>
                        {{ app()->getLocale() === 'ro'
                            ? 'Sunt Ionel Padure, un pasionat de tehnologie si creatie. Imi petrec timpul liber creand obiecte unice folosind taierea laser, imprimarea 3D si prelucrarea lemnului.'
                            : 'I am Ionel Padure, a technology and creativity enthusiast. I spend my free time creating unique objects using laser cutting, 3D printing, and woodworking.' }}
                    </p>
                    <p>
                        {{ app()->getLocale() === 'ro'
                            ? 'Fiecare creatie este rezultatul imaginatiei, planificarii atente si al orelor de munca. De la proiecte decorative la obiecte functionale, explor mereu noi modalitati de a aduce ideile la viata.'
                            : 'Each creation is the result of imagination, careful planning, and hours of work. From decorative projects to functional objects, I am always exploring new ways to bring ideas to life.' }}
                    </p>
                </div>
                <div class="flex justify-center">
                    <img src="{{ asset('storage/res/forest.jpg') }}" alt="Ionel Padure"
                         class="rounded-lg w-full max-w-xs object-cover shadow-lg">
                </div>
            </div>
        </div>
    </section>

    {{-- My Journey Section --}}
    <section class="mb-12">
        <h2 class="text-2xl font-bold mb-6">
            {{ app()->getLocale() === 'ro' ? 'Povestea mea' : 'My Journey' }}
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-slate-800 rounded-xl overflow-hidden">
                <img src="{{ asset('storage/res/pionier.jpg') }}" alt="Journey"
                     class="w-full h-64 object-cover">
                <div class="p-6">
                    <p class="text-slate-300">
                        {{ app()->getLocale() === 'ro'
                            ? 'Pasiunea mea pentru creatie a inceput inca din copilarie, cand construiam tot felul de obiecte din lemn si materiale reciclate. Cu timpul, am descoperit lumea fascinanta a tehnologiei de fabricatie digitala.'
                            : 'My passion for creation started in childhood, when I used to build all sorts of objects from wood and recycled materials. Over time, I discovered the fascinating world of digital fabrication technology.' }}
                    </p>
                </div>
            </div>
            <div class="bg-slate-800 rounded-xl p-6 flex flex-col justify-center space-y-4">
                <p class="text-slate-300">
                    {{ app()->getLocale() === 'ro'
                        ? 'Astazi, folosesc un laser CO2, imprimante 3D si diverse unelte pentru a transforma ideile in realitate. Fiecare proiect este o provocare noua si o oportunitate de a invata ceva.'
                        : 'Today, I use a CO2 laser, 3D printers, and various tools to turn ideas into reality. Every project is a new challenge and an opportunity to learn something.' }}
                </p>
                <p class="text-slate-300">
                    {{ app()->getLocale() === 'ro'
                        ? 'Prin acest site si canalul meu de YouTube, impartasesc procesul de creatie si sper sa inspir si pe altii sa exploreze aceasta lume fascinanta.'
                        : 'Through this website and my YouTube channel, I share the creative process and hope to inspire others to explore this fascinating world.' }}
                </p>
            </div>
        </div>
    </section>

    {{-- Get in Touch Section --}}
    <section class="mb-12">
        <h2 class="text-2xl font-bold mb-6">
            {{ app()->getLocale() === 'ro' ? 'Ia legatura' : 'Get in Touch' }}
        </h2>
        <div class="flex flex-wrap gap-4">
            <a href="https://www.youtube.com/@4-est" target="_blank" rel="noopener"
               class="flex items-center gap-3 bg-slate-800 hover:bg-slate-700 rounded-lg px-6 py-4 transition-colors">
                <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                </svg>
                <div>
                    <div class="font-semibold">YouTube</div>
                    <div class="text-sm text-slate-400">@4-est</div>
                </div>
            </a>
            <a href="https://www.instagram.com/padureionel" target="_blank" rel="noopener"
               class="flex items-center gap-3 bg-slate-800 hover:bg-slate-700 rounded-lg px-6 py-4 transition-colors">
                <svg class="w-6 h-6 text-pink-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/>
                </svg>
                <div>
                    <div class="font-semibold">Instagram</div>
                    <div class="text-sm text-slate-400">@padureionel</div>
                </div>
            </a>
            <a href="https://www.etsy.com/shop/4estInfo" target="_blank" rel="noopener"
               class="flex items-center gap-3 bg-slate-800 hover:bg-slate-700 rounded-lg px-6 py-4 transition-colors">
                <svg class="w-6 h-6 text-orange-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8.559 3.074c0-.155.063-.218.249-.218h5.261c1.402 0 2.645 1.245 3.098 3.258l.093.404h.838l-.093-4.878H17.63c-.155 0-.218.063-.311.249l-.404.809c-.404-.031-2.027-.218-3.429-.218H5.988v.838h.776c1.214 0 1.556.373 1.556 1.618v12.487c0 1.245-.342 1.618-1.556 1.618h-.776v.838h8.628c1.649 0 3.489-.218 4.144-.249l.653-5.156h-.838l-.342 1.12c-.622 2.027-1.865 3.447-4.424 3.447H9.882c-.653 0-.964-.311-.964-.964v-6.244h2.645c1.649 0 2.178.622 2.458 2.178h.838V9.318h-.838c-.28 1.556-.809 2.178-2.458 2.178H8.918V3.074h-.359z"/>
                </svg>
                <div>
                    <div class="font-semibold">Etsy</div>
                    <div class="text-sm text-slate-400">4estInfo</div>
                </div>
            </a>
        </div>
    </section>

    {{-- Motto Section --}}
    <section class="mb-12">
        <div class="relative rounded-xl overflow-hidden">
            <img src="{{ asset('storage/res/motto.jpg') }}" alt="Motto" class="w-full h-64 object-cover">
            <div class="absolute inset-0 bg-slate-900/70 flex items-center justify-center">
                <blockquote class="text-center px-8">
                    <p class="text-2xl md:text-3xl font-light italic text-white">
                        {{ app()->getLocale() === 'ro'
                            ? '"Creativitatea este inteligenta care se distreaza."'
                            : '"Creativity is intelligence having fun."' }}
                    </p>
                    <footer class="mt-4 text-[#39792D] font-medium">— Albert Einstein</footer>
                </blockquote>
            </div>
        </div>
    </section>
@endsection
