<style>
    @keyframes splash-fade-in {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    @keyframes splash-pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }
    
    @keyframes splash-loading {
        0% {
            width: 0%;
        }
        100% {
            width: 100%;
        }
    }
    
    @keyframes splash-spinner {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
    
    .app-splash__logo-img {
        animation: splash-fade-in 0.6s ease-out, splash-pulse 2s ease-in-out 0.6s infinite;
    }
    
    .app-splash__loader {
        margin-top: 3rem;
        width: 40px;
        height: 40px;
        border: 3px solid rgba(255, 255, 255, 0.2);
        border-top: 3px solid white;
        border-radius: 50%;
        animation: splash-spinner 1s linear infinite;
    }
</style>

<div class="app-splash" id="appSplash">
    <div class="app-splash__pattern"></div>
    <div class="app-splash__glow app-splash__glow--1"></div>
    <div class="app-splash__glow app-splash__glow--2"></div>

    <div style="display: flex; flex-direction: column; align-items: center; position: relative; z-index: 10;">
        <img src="{{ asset('images/telkomsel-logo.png') }}" alt="Telkomsel Logo" class="app-splash__logo-img" style="max-width: 20%; height: auto;">
        <div class="app-splash__loader"></div>
    </div>
</div>
