<div>
    <div 
        x-data="gameTimer({{ $startTimestamp }}, {{ $finishTimestamp }})"
        x-init="init()"
        class="game-timer"
    >
        <span class="timer-label" x-text="label"></span>
        <span class="timer-countdown" x-text="countdown"></span>
    </div>


    @pushOnce('styles')
    <style>
        .game-timer {
            font-weight: 600;
        }

        .timer-label {
            color: #666;
        }

        .timer-countdown {
            color: #ffffffff;
        }
    </style>
    @endPushOnce('styles')

    @pushOnce('scripts')
    <script>
    function gameTimer(startTimestamp, finishTimestamp) {
        return {
            label: '',
            countdown: '',
            interval: null,
            
            init() {
                this.updateTimer();
                this.interval = setInterval(() => this.updateTimer(), 1000);
                
                // Clear interval once component destroyed
                this.$watch('interval', () => {
                    return () => clearInterval(this.interval);
                });
            },
            
            updateTimer() {
                const now = Date.now();
                const startTime = startTimestamp * 1000;
                const finishTime = finishTimestamp * 1000;
                
                if (now < startTime) {
                    this.label = 'Starts in: ';
                    this.countdown = this.formatCountdown(startTime - now);
                } else if (now >= startTime && now < finishTime) {
                    this.label = 'Ends in: ';
                    this.countdown = this.formatCountdown(finishTime - now);
                } else {
                    this.label = 'Finished';
                    this.countdown = '';
                    clearInterval(this.interval);
                }
            },
            
            formatCountdown(milliseconds) {
                const days = Math.floor(milliseconds / (1000 * 60 * 60 * 24));
                const hours = Math.floor((milliseconds % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((milliseconds % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((milliseconds % (1000 * 60)) / 1000);
                
                let timeString = '';
                if (days > 0) timeString += `${days}d `;
                if (hours > 0 || days > 0) timeString += `${hours}h `;
                if (minutes > 0 || hours > 0 || days > 0) timeString += `${minutes}m `;
                timeString += `${seconds}s`;
                
                return timeString;
            }
        }
    }
    </script>
    @endPushOnce('scripts')
</div>

