@props([
    'title',
    'subtitle' => '',
    'gradient' => 'gradient-ocean', // Default gradient
    'actions' => null,
    'breadcrumbs' => null
])

<div class="relative bg-{{ $gradient }} overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-black/5">
        <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
    </div>
    
    <!-- Glassmorphism Overlay -->
    <div class="absolute inset-0 glass"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <!-- Breadcrumbs -->
        @if($breadcrumbs)
            <nav class="mb-6">
                <ol class="flex items-center space-x-2 text-sm">
                    {{ $breadcrumbs }}
                </ol>
            </nav>
        @endif
        
        <div class="text-center">
            <!-- Title -->
            <h1 class="text-4xl md:text-5xl font-display font-bold text-white mb-4 text-shadow-lg animate-fade-in">
                {{ $title }}
            </h1>
            
            <!-- Subtitle -->
            @if($subtitle)
                <p class="text-lg md:text-xl text-white/90 mb-8 max-w-3xl mx-auto leading-relaxed animate-slide-up">
                    {{ $subtitle }}
                </p>
            @endif
            
            <!-- Actions -->
            @if($actions)
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-slide-up">
                    {{ $actions }}
                </div>
            @endif
        </div>
    </div>
    
    <!-- Floating Elements -->
    <div class="absolute top-10 left-10 w-20 h-20 bg-white/10 rounded-full blur-xl animate-pulse"></div>
    <div class="absolute bottom-10 right-10 w-32 h-32 bg-white/5 rounded-full blur-2xl animate-pulse" style="animation-delay: 1s;"></div>
    <div class="absolute top-1/2 left-1/4 w-16 h-16 bg-white/5 rounded-full blur-xl animate-pulse" style="animation-delay: 2s;"></div>
</div>

@push('styles')
<style>
    .bg-grid-pattern {
        background-image:
            linear-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
        background-size: 20px 20px;
    }
    
    .glass {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .animate-fade-in {
        animation: fadeIn 0.8s ease-out;
    }
    
    .animate-slide-up {
        animation: slideUp 0.8s ease-out 0.2s both;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush