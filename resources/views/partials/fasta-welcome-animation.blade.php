@if(session('fasta_welcome_message'))
    <style>
        .fasta-welcome-overlay {
            position: fixed;
            inset: 0;
            z-index: 100000;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(12, 36, 27, 0.42);
            backdrop-filter: blur(8px);
            animation: fastaWelcomeFade 3s ease forwards;
        }

        .fasta-welcome-panel {
            width: min(460px, calc(100vw - 40px));
            border-radius: 24px;
            background: #ffffff;
            padding: 34px 28px;
            text-align: center;
            box-shadow: 0 26px 80px rgba(12, 36, 27, 0.22);
            animation: fastaWelcomeLift 0.65s cubic-bezier(.16, 1, .3, 1) both;
        }

        .fasta-welcome-mark {
            width: 72px;
            height: 72px;
            margin: 0 auto 18px;
            border-radius: 22px;
            display: grid;
            place-items: center;
            background: #039d55;
            color: #ffffff;
            font-size: 32px;
            font-weight: 800;
            box-shadow: 0 16px 34px rgba(3, 157, 85, 0.28);
        }

        .fasta-welcome-panel h2 {
            margin: 0;
            color: #15332a;
            font-size: 30px;
            line-height: 1.18;
            letter-spacing: 0;
        }

        .fasta-welcome-panel p {
            margin: 10px 0 0;
            color: #657a72;
            font-size: 14px;
        }

        @keyframes fastaWelcomeLift {
            from {
                opacity: 0;
                transform: translateY(18px) scale(.96);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes fastaWelcomeFade {
            0%, 78% {
                opacity: 1;
                visibility: visible;
            }
            100% {
                opacity: 0;
                visibility: hidden;
            }
        }
    </style>
    <div class="fasta-welcome-overlay" aria-live="polite">
        <div class="fasta-welcome-panel">
            <div class="fasta-welcome-mark">F</div>
            <h2>{{ session('fasta_welcome_message') }}</h2>
            <p>{{ translate('Fasta Deliveries') }}</p>
        </div>
    </div>
    <script>
        setTimeout(function () {
            var overlay = document.querySelector('.fasta-welcome-overlay');
            if (overlay) {
                overlay.remove();
            }
        }, 3200);
    </script>
@endif
