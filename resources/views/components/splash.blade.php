<style>
    .app-splash{
        position:fixed;
        inset:0;
        z-index:9999;
        display:flex;
        align-items:center;
        justify-content:center;
        background:
            radial-gradient(circle at 18% 12%, rgba(255,255,255,.12) 0%, transparent 42%),
            radial-gradient(circle at 88% 88%, rgba(0,0,0,.30) 0%, transparent 55%),
            linear-gradient(135deg,#f87171 0%,#dc2626 32%,#991b1b 68%,#450a0a 100%);
        overflow:hidden;
        animation:splashFadeOut .8s ease forwards;
        animation-delay:3s;
    }

    .app-splash__pattern{
        position:absolute;
        inset:0;
        opacity:.12;
        background-image:radial-gradient(rgba(255,255,255,.6) 1px,transparent 1px);
        background-size:22px 22px;
    }

    .app-splash__glow{
        position:absolute;
        border-radius:9999px;
        filter:blur(70px);
    }

    .app-splash__glow--1{
        width:420px;
        height:420px;
        top:-130px;
        left:-110px;
        background:#fca5a5;
        opacity:.35;
    }

    .app-splash__glow--2{
        width:380px;
        height:380px;
        bottom:-150px;
        right:-90px;
        background:#fde68a;
        opacity:.16;
    }

    @keyframes splashFadeOut{
        to{
            opacity:0;
            visibility:hidden;
            pointer-events:none;
        }
    }

    @keyframes splash-fade-in{
        from{
            opacity:0;
            transform:scale(.8);
        }
        to{
            opacity:1;
            transform:scale(1);
        }
    }

    @keyframes splash-pulse{
        0%,100%{transform:scale(1);}
        50%{transform:scale(1.05);}
    }

    @keyframes splash-spinner{
        from{transform:rotate(0deg);}
        to{transform:rotate(360deg);}
    }

    .app-splash__logo-img{
        animation:splash-fade-in .6s ease-out,splash-pulse 2s ease-in-out .6s infinite;
    }

    .app-splash__loader{
        margin-top:3rem;
        width:40px;
        height:40px;
        border:3px solid rgba(255,255,255,.2);
        border-top:3px solid white;
        border-radius:50%;
        animation:splash-spinner 1s linear infinite;
    }
</style>

<div class="app-splash" id="appSplash">
    <div class="app-splash__pattern"></div>
    <div class="app-splash__glow app-splash__glow--1"></div>
    <div class="app-splash__glow app-splash__glow--2"></div>

    <div style="display:flex;flex-direction:column;align-items:center;position:relative;z-index:10;">
        <img src="{{ asset('images/telkomsel-logo.png') }}"
             class="app-splash__logo-img"
             style="max-width:20%;height:auto;">

        <div class="app-splash__loader"></div>
    </div>
</div>