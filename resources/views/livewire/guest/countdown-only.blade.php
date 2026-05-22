<div wire:poll style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: #000; z-index: 9999; display: flex; align-items: center; justify-content: center; overflow: hidden;">
    @if ($isPaused)
        <div class="text-center" style="color: #ffc107;">
            <i class="fas fa-pause-circle" style="font-size: 8rem; margin-bottom: 2rem;"></i>
            <h1 style="font-size: 4vw; font-weight: 900; font-family: 'Inter', sans-serif; letter-spacing: 5px;">DIJEDA</h1>
        </div>
    @elseif ($phase === 'countdown')
        <div x-data="{
                started: {{ $countdownStartedAt ?? 'null' }},
                duration: {{ $countdownDuration }},
                timeLeft: 0,
                interval: null,
                get formatted() {
                    const m = Math.floor(this.timeLeft / 60);
                    const s = this.timeLeft % 60;
                    return String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
                },
                calc() {
                    if (!this.started) { this.timeLeft = this.duration; return; }
                    const now = Math.floor(Date.now() / 1000);
                    const elapsed = now - this.started;
                    this.timeLeft = Math.max(0, this.duration - elapsed);
                },
                init() { this.calc(); this.interval = setInterval(() => this.calc(), 1000); }
            }">
            <div style="font-size: 28vw; font-weight: 900; font-family: 'Inter', monospace; line-height: 1;"
                :style="{ color: timeLeft <= 30 ? '#e62b1e' : 'white' }">
                <span x-text="formatted"></span>
            </div>
        </div>
    @elseif ($phase === 'presenting')
        <div x-data="{
                started: {{ $timerStartedAt ?? 'null' }},
                duration: {{ $timerDuration }},
                timeLeft: 0,
                interval: null,
                get formatted() {
                    const m = Math.floor(this.timeLeft / 60);
                    const s = this.timeLeft % 60;
                    return String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
                },
                calc() {
                    if (!this.started) { this.timeLeft = this.duration; return; }
                    const now = Math.floor(Date.now() / 1000);
                    const elapsed = now - this.started;
                    this.timeLeft = Math.max(0, this.duration - elapsed);
                },
                init() { this.calc(); this.interval = setInterval(() => this.calc(), 1000); }
            }">
            <div style="font-size: 28vw; font-weight: 900; font-family: 'Inter', monospace; line-height: 1;"
                :style="{ color: timeLeft <= 30 ? '#e62b1e' : 'white' }">
                <span x-text="formatted"></span>
            </div>
        </div>
    @else
        <div class="text-center" style="color: #333;">
            <div style="font-size: 28vw; font-weight: 900; font-family: 'Inter', monospace; line-height: 1;">
                00:00
            </div>
        </div>
    @endif
</div>
