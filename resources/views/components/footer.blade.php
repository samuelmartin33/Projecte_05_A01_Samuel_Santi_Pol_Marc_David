{{--
    Componente: <x-footer />
    Uso: Pie de página estilo colofón editorial — fondo ink, texto paper.
--}}
<footer class="bg-ink text-paper border-t border-ink">
    <div class="max-w-7xl mx-auto px-6 sm:px-10">

        {{-- Fila superior: wordmark + tagline --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center
                    justify-between gap-4 py-8 border-b border-paper/10">
            <div>
                <span class="font-display font-black text-2xl tracking-brutal select-none">VIBEZ</span>
                <p class="font-mono text-xs uppercase tracking-widest text-paper/35 mt-1">
                    La plataforma de tu escena
                </p>
            </div>
            <nav class="flex gap-8 font-mono text-xs uppercase tracking-widest text-paper/35">
                <a href="{{ route('home') }}" class="hover:text-paper transition-colors duration-100">Explorar</a>
                <a href="{{ route('trabajos.index') }}" class="hover:text-paper transition-colors duration-100">Trabajo</a>
                <a href="#" class="hover:text-paper transition-colors duration-100">Privacidad</a>
                <a href="#" class="hover:text-paper transition-colors duration-100">Contacto</a>
            </nav>
        </div>

        {{-- Fila inferior: copyright + fecha --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center
                    justify-between gap-2 py-5">
            <p class="font-mono text-xs text-paper/25">
                © {{ date('Y') }} VIBEZ — Todos los derechos reservados.
            </p>
            <p class="font-mono text-xs text-paper/20">
                {{ now()->format('d.m.y — H:i') }}
            </p>
        </div>

    </div>
</footer>
