<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kantor Pak RT - Level Pro</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&family=Reenie+Beanie&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary: #4ade80;
            --primary-dark: #15803d;
            --danger: #ef4444;
            --danger-dark: #b91c1c;
            --bg: #f3f4f6;
            --paper: #fff;
            --text: #1f2937;
            --accent: #6366f1;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            user-select: none;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg);
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            color: var(--text);
        }

        /* HEADER */
        header {
            background: white;
            padding: 0.75rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            z-index: 10;
        }

        .stats-container {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .stat-group {
            text-align: center;
        }

        .stat-label {
            font-size: 0.7rem;
            color: #6b7280;
            font-weight: 700;
            text-transform: uppercase;
        }

        .stat-val {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text);
        }

        .stat-val.score {
            color: var(--primary-dark);
        }

        .stat-val.timer {
            color: var(--danger-dark);
            font-variant-numeric: tabular-nums;
        }

        /* GAME AREA - CENTERING FIX */
        #game-area {
            flex: 1;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #e5e7eb;
            background-image:
                radial-gradient(#9ca3af 1px, transparent 1px),
                radial-gradient(#9ca3af 1px, transparent 1px);
            background-position: 0 0, 20px 20px;
            background-size: 40px 40px;
            overflow: hidden;
        }

        #damage-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(239, 68, 68, 0.3);
            pointer-events: none;
            opacity: 0;
            z-index: 40;
            transition: opacity 0.1s;
        }

        /* CARD STACK CONTAINER */
        #card-stack {
            width: 360px;
            height: 520px;
            position: relative;
            z-index: 5;
        }

        @media (min-height: 800px) {
            #card-stack {
                height: 600px;
                width: 400px;
            }
        }

        /* DOCUMENT CARD */
        .document-card {
            width: 100%;
            height: 100%;
            background: var(--paper);
            border-radius: 6px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1), 0 0 0 1px #d1d5db;
            padding: 2rem;
            position: absolute;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            transform-origin: 50% 120%;
            transition: transform 0.4s cubic-bezier(0.2, 0.8, 0.2, 1), opacity 0.3s ease;
        }

        .document-card.swiped-right {
            transform: translateX(120vw) rotate(30deg);
            opacity: 0;
        }

        .document-card.swiped-left {
            transform: translateX(-120vw) rotate(-30deg);
            opacity: 0;
        }

        /* INTERIOR CARD DESIGN */
        .doc-header {
            border-bottom: 2px dashed #e5e7eb;
            padding-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .doc-header h2 {
            font-size: 1.2rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .doc-header span {
            font-size: 0.7rem;
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
            color: #6b7280;
        }

        .doc-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            position: relative;
        }

        .field {
            background: #f9fafb;
            padding: 0.75rem;
            border-radius: 8px;
            border: 1px solid #f3f4f6;
        }

        .field label {
            font-size: 0.7rem;
            color: #9ca3af;
            font-weight: 700;
            text-transform: uppercase;
            display: block;
            margin-bottom: 2px;
        }

        .field .value {
            font-weight: 600;
            font-size: 1.05rem;
            color: var(--text);
        }

        .field .value.alert {
            color: var(--danger);
            font-weight: 800;
        }

        .signature-area {
            margin-top: auto;
            text-align: right;
            padding-right: 1rem;
        }

        .signature {
            font-family: 'Reenie Beanie', cursive;
            font-size: 2.5rem;
            color: #1f2937;
            transform: rotate(-10deg);
            display: inline-block;
        }

        .signature.fake {
            font-family: 'Courier New', monospace;
            color: #ef4444;
            font-size: 1.2rem;
            transform: rotate(0);
            letter-spacing: -1px;
            border: 2px solid #ef4444;
            padding: 0.25rem;
        }

        .stamp-mark {
            position: absolute;
            bottom: 2rem;
            left: 1rem;
            width: 90px;
            height: 90px;
            border: 4px double;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 900;
            font-size: 1rem;
            transform: rotate(-20deg);
            opacity: 0.7;
            mix-blend-mode: multiply;
            z-index: 2;
        }

        .stamp-mark.blue {
            color: #2563eb;
            border-color: #2563eb;
        }

        /* CONTROLS */
        .controls {
            position: absolute;
            bottom: 2rem;
            display: flex;
            gap: 4rem;
            width: 100%;
            justify-content: center;
            z-index: 20;
            pointer-events: none;
        }

        .btn-action {
            pointer-events: auto;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2rem;
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transition: transform 0.1s;
        }

        .btn-action:active {
            transform: scale(0.9);
        }

        .btn-reject {
            background: var(--danger);
        }

        .btn-approve {
            background: var(--primary-dark);
        }

        .key-hint {
            position: absolute;
            bottom: -25px;
            width: 100%;
            text-align: center;
            color: #374151;
            font-weight: 700;
            font-size: 0.8rem;
            opacity: 0.7;
        }

        /* OVERLAYS */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 100;
        }

        .modal {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            text-align: center;
            max-width: 450px;
            width: 90%;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border: 1px solid #e5e7eb;
        }

        .btn-start {
            background: #111827;
            color: white;
            border: none;
            padding: 1rem 3rem;
            font-size: 1.1rem;
            font-weight: 700;
            border-radius: 12px;
            cursor: pointer;
            width: 100%;
            margin-top: 1.5rem;
            transition: transform 0.1s;
        }

        .btn-start:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .hidden {
            display: none !important;
        }

        /* COMBO & FEEDBACK */
        .combo-meter {
            position: absolute;
            top: 2rem;
            right: 2rem;
            text-align: right;
            transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .combo-val {
            font-size: 3rem;
            font-weight: 900;
            color: #f59e0b;
            line-height: 1;
            text-shadow: 2px 2px 0px white;
        }

        .combo-label {
            font-size: 0.9rem;
            font-weight: 700;
            color: #d97706;
            letter-spacing: 2px;
        }

        .feedback {
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 50;
            pointer-events: none;
            opacity: 0;
            font-size: 2.5rem;
            font-weight: 900;
            text-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            white-space: nowrap;
        }

        .feedback.perf {
            color: #8b5cf6;
            font-size: 3.5rem;
        }

        .feedback.good {
            color: #10b981;
        }

        .feedback.bad {
            color: #ef4444;
        }

        .feedback.bonus {
            color: #06b6d4;
            font-size: 2rem;
        }
    </style>
</head>

<body>

    <!-- START -->
    <div id="start-screen" class="overlay">
        <div class="modal">
            <h1>Kantor Pak RT 🇮🇩</h1>
            <p style="color: #6b7280; margin-bottom: 1.5rem;">Cek dokumen warga dengan teliti!</p>

            <div style="background: #f3f4f6; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <div style="font-size: 0.8rem; color: #6b7280; font-weight: 700;">REKOR TERTINGGI</div>
                <div id="best-score-display" style="font-size: 2rem; font-weight: 800; color: var(--primary-dark);">0
                </div>
            </div>

            <ul
                style="text-align: left; font-size: 0.9rem; color: #4b5563; margin-bottom: 2rem; list-style: none; space-y: 0.5rem;">
                <li>📅 <strong>Tanggal</strong>: Tidak boleh lewat.</li>
                <li>✍️ <strong>Tanda Tangan</strong>: Nama harus cocok.</li>
                <li>🛡️ <strong>Stempel</strong>: Wajib ada untuk surat resmi.</li>
            </ul>

            <button class="btn-start" onclick="game.start()">MULAI KERJA</button>
        </div>
    </div>

    <!-- GAME OVER -->
    <div id="game-over-screen" class="overlay hidden">
        <div class="modal">
            <h1 id="end-title">Selesai!</h1>
            <div style="font-size: 4rem; font-weight: 800; color: var(--text); margin: 0.5rem 0;" id="final-score">0
            </div>
            <p id="new-record-msg" style="color: #f59e0b; font-weight: 700; margin-bottom: 1rem;" class="hidden">✨ REKOR
                BARU! ✨</p>
            <p id="final-msg" style="color: #6b7280;">Warga senang dengan pelayanan Anda.</p>
            <button class="btn-start" onclick="game.start()">MAIN LAGI</button>
            <button onclick="location.href='index.php'"
                style="margin-top: 1rem; background: transparent; border: none; color: #6b7280; font-weight: 600; cursor: pointer;">Kembali
                ke Dashboard</button>
        </div>
    </div>

    <!-- HUD -->
    <header>
        <div style="font-weight: 800; font-size: 1.4rem;">SITAWAR <span style="font-weight: 400; color: #9ca3af;">|
                Admin</span></div>
        <div class="stats-container">
            <div class="stat-group">
                <div class="stat-label">Sisa Waktu</div>
                <div class="stat-val timer" id="timer">60</div>
            </div>
            <div class="stat-group">
                <div class="stat-label">Skor</div>
                <div class="stat-val score" id="score">0</div>
            </div>
        </div>
    </header>

    <!-- MAIN AREA -->
    <div id="game-area">
        <div id="damage-overlay"></div>
        <div id="card-stack">
            <!-- Cards go here -->
        </div>

        <!-- Combo UI -->
        <div class="combo-meter hidden" id="combo-ui">
            <div class="combo-val" id="combo-val">x1</div>
            <div class="combo-label">COMBO</div>
        </div>

        <!-- Feedback -->
        <div class="feedback" id="feedback"></div>

        <!-- Controls -->
        <div class="controls">
            <div style="position: relative;">
                <button class="btn-action btn-reject" onclick="game.handleSwipe('left')">✕</button>
                <div class="key-hint">A / Kiri</div>
            </div>
            <div style="position: relative;">
                <button class="btn-action btn-approve" onclick="game.handleSwipe('right')">✓</button>
                <div class="key-hint">D / Kanan</div>
            </div>
        </div>
    </div>

    <script>
        const GameConfig = {
            duration: 60,
            baseScore: 10,
            penalty: 20
        };

        const DataAssets = {
            names: ["Budi Santoso", "Siti Aminah", "Joko Widodo", "Rina Wati", "Andi Pratama", "Dewi Sartika", "Agus Salim", "Ratna Sari"],
            types: [
                { title: "Surat Pengantar", stamp: false },
                { title: "Keterangan Domisili", stamp: true },
                { title: "Permohonan KTP", stamp: true },
                { title: "Izin Keramaian", stamp: true },
                { title: "Laporan Kehilangan", stamp: false },
                { title: "Surat Kematian", stamp: true }
            ]
        };

        class SoundManager {
            constructor() {
                this.ctx = new (window.AudioContext || window.webkitAudioContext)();
            }
            playTone(freq, type, duration) {
                if (this.ctx.state === 'suspended') this.ctx.resume();
                const osc = this.ctx.createOscillator();
                const gain = this.ctx.createGain();
                osc.type = type;
                osc.frequency.setValueAtTime(freq, this.ctx.currentTime);
                gain.gain.setValueAtTime(0.1, this.ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, this.ctx.currentTime + duration);
                osc.connect(gain);
                gain.connect(this.ctx.destination);
                osc.start();
                osc.stop(this.ctx.currentTime + duration);
            }
            correct() { this.playTone(800, 'sine', 0.2); setTimeout(() => this.playTone(1200, 'sine', 0.4), 100); }
            wrong() { this.playTone(150, 'sawtooth', 0.4); }
            swipe() { this.playTone(300, 'triangle', 0.1); }
            bonus() { this.playTone(1500, 'square', 0.1); setTimeout(() => this.playTone(1800, 'square', 0.2), 100); }
        }

        class Game {
            constructor() {
                this.score = 0;
                this.time = GameConfig.duration;
                this.combo = 0;
                this.active = false;
                this.isProcessing = false; // Prevents double submission
                this.currentCard = null;
                this.audio = new SoundManager();
                this.timerInterval = null;

                this.els = {
                    score: document.getElementById('score'),
                    timer: document.getElementById('timer'),
                    stack: document.getElementById('card-stack'),
                    combo: document.getElementById('combo-ui'),
                    comboVal: document.getElementById('combo-val'),
                    feedback: document.getElementById('feedback'),
                    startScreen: document.getElementById('start-screen'),
                    endScreen: document.getElementById('game-over-screen'),
                    bestScore: document.getElementById('best-score-display'),
                    damage: document.getElementById('damage-overlay')
                };

                this.loadHighScore();
            }

            loadHighScore() {
                const best = localStorage.getItem('pak_rt_highscore') || 0;
                this.els.bestScore.innerText = best;
            }

            saveHighScore() {
                const currentBest = parseInt(localStorage.getItem('pak_rt_highscore') || 0);
                if (this.score > currentBest) {
                    localStorage.setItem('pak_rt_highscore', this.score);
                    document.getElementById('new-record-msg').classList.remove('hidden');
                } else {
                    document.getElementById('new-record-msg').classList.add('hidden');
                }
            }

            start() {
                this.score = 0;
                this.time = GameConfig.duration;
                this.combo = 0;
                this.active = true;
                this.isProcessing = false;

                this.els.startScreen.classList.add('hidden');
                this.els.endScreen.classList.add('hidden');
                this.updateUI();

                this.els.stack.innerHTML = '';
                this.nextCard();

                if (this.timerInterval) clearInterval(this.timerInterval);
                this.timerInterval = setInterval(() => this.tick(), 1000);
            }

            tick() {
                this.time--;
                this.updateUI();
                if (this.time <= 0) this.end();
            }

            end() {
                this.active = false;
                clearInterval(this.timerInterval);
                this.saveHighScore();
                document.getElementById('final-score').innerText = this.score;
                this.els.endScreen.classList.remove('hidden');
            }

            updateUI() {
                this.els.score.innerText = this.score;
                this.els.timer.innerText = this.time;

                if (this.combo > 1) {
                    this.els.combo.classList.remove('hidden');
                    this.els.comboVal.innerText = 'x' + Math.min(this.combo, 5);
                    this.els.combo.style.transform = 'scale(1.2)';
                    setTimeout(() => this.els.combo.style.transform = 'scale(1)', 100);
                } else {
                    this.els.combo.classList.add('hidden');
                }
            }

            nextCard() {
                if (!this.active) return;

                this.isProcessing = false; // Unlock inputs

                const person = DataAssets.names[Math.floor(Math.random() * DataAssets.names.length)];
                const type = DataAssets.types[Math.floor(Math.random() * DataAssets.types.length)];

                const isExpired = Math.random() < 0.2;
                const nameMismatch = Math.random() < 0.2;
                const missingStamp = type.stamp && Math.random() < 0.2;

                const logic = {
                    valid: !isExpired && !nameMismatch && !missingStamp,
                    expired: isExpired,
                    nameMismatch: nameMismatch,
                    missingStamp: missingStamp
                };

                const card = document.createElement('div');
                card.className = 'document-card';

                const date = new Date();
                if (isExpired) date.setMonth(date.getMonth() - 3);
                else date.setDate(date.getDate() + 10);
                const dateStr = date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

                const signName = nameMismatch ? DataAssets.names.find(n => n !== person) : person;

                card.innerHTML = `
                    <div class="doc-header">
                        <div>
                            <h2>${type.title}</h2>
                            <span>Official Document</span>
                        </div>
                        <div style="font-size:2rem;">📄</div>
                    </div>
                    <div class="doc-body">
                        <div class="field">
                            <label>Nama Pemohon</label>
                            <div class="value">${person}</div>
                        </div>
                        <div class="field">
                            <label>Masa Berlaku</label>
                            <div class="value ${isExpired ? 'alert' : ''}">${dateStr}</div>
                        </div>
                        <div class="field">
                            <label>Status</label>
                            <div class="value">Menunggu Verifikasi</div>
                        </div>

                        ${(!missingStamp && type.stamp) ? `<div class="stamp-mark blue">SAH</div>` : ''}

                        <div class="signature-area">
                            <div class="signature ${nameMismatch ? 'fake' : ''}">${signName}</div>
                            <div style="font-size:0.6rem; color:#9ca3af;">Tanda Tangan Pemohon</div>
                        </div>
                    </div>
                `;

                card.dataset.valid = logic.valid;
                this.currentCard = card;
                this.els.stack.appendChild(card);

                card.animate([
                    { transform: 'translateY(100px) scale(0.9)', opacity: 0 },
                    { transform: 'translateY(0) scale(1)', opacity: 1 }
                ], { duration: 300, easing: 'cubic-bezier(0.2, 0.8, 0.2, 1)' });

                this.audio.swipe();
            }

            handleSwipe(dir) {
                if (!this.active || !this.currentCard || this.isProcessing) return;

                this.isProcessing = true; // Lock inputs immediately
                const card = this.currentCard;
                const isValid = card.dataset.valid === 'true';

                let correct = (dir === 'right' && isValid) || (dir === 'left' && !isValid);

                if (dir === 'right') card.classList.add('swiped-right');
                else card.classList.add('swiped-left');

                if (correct) {
                    this.combo++;
                    const multiplier = Math.min(this.combo, 5);
                    this.score += (GameConfig.baseScore * multiplier);
                    this.showFeedback('BENAR!', 'good');
                    this.audio.correct();

                    // TIME BONUS
                    if (this.combo > 0 && this.combo % 5 === 0) {
                        this.time += 3;
                        this.showFeedback('+3s BONUS', 'bonus');
                        this.audio.bonus();
                    }

                } else {
                    this.combo = 0;
                    this.score = Math.max(0, this.score - GameConfig.penalty);
                    // Do NOT set active = false here! That was the bug.

                    // Flash red
                    this.els.damage.style.opacity = '1';
                    setTimeout(() => this.els.damage.style.opacity = '0', 200);

                    this.showFeedback('SALAH!', 'bad');
                    this.audio.wrong();
                }

                this.updateUI();

                setTimeout(() => {
                    card.remove();
                    this.nextCard(); // This will unlock isProcessing
                }, 200);
            }

            showFeedback(msg, type) {
                const el = this.els.feedback;
                el.innerText = msg;
                el.className = `feedback ${type}`;

                el.animate([
                    { opacity: 0, transform: 'translate(-50%, -50%) scale(0.5)' },
                    { opacity: 1, transform: 'translate(-50%, -100%) scale(1.2)', offset: 0.5 },
                    { opacity: 0, transform: 'translate(-50%, -150%) scale(1)' }
                ], { duration: 600, easing: 'ease-out' });
            }
        }

        const game = new Game();

        window.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft' || e.key.toLowerCase() === 'a') game.handleSwipe('left');
            if (e.key === 'ArrowRight' || e.key.toLowerCase() === 'd') game.handleSwipe('right');
        });

    </script>
</body>

</html>