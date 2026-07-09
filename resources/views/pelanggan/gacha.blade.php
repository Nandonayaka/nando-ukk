@extends('books.layout')

@section('content')
<div class="min-h-[80vh] flex flex-col items-center justify-center p-4 lg:-mt-10">
    <div class="w-full max-w-6xl bg-white rounded-[2.5rem] p-8 md:p-12 relative border border-gray-100 shadow-[0_20px_60px_rgba(0,0,0,0.05)] transform transition-all">
        
        <div class="flex flex-col lg:flex-row gap-12 lg:gap-16 items-center lg:items-center justify-between">
            
            <!-- LEFT SIDE: Gacha Wheel & Actions -->
            <div class="w-full lg:w-1/2 flex flex-col items-center text-center">
                
                <!-- Header -->
                <div class="w-full flex flex-col sm:flex-row items-center sm:items-start justify-between gap-4 mb-8 lg:mb-10">
                    <div class="text-center sm:text-left">
                        <h1 class="text-3xl lg:text-4xl font-black text-gray-900 tracking-tight mb-2">Gacha Rulet</h1>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-relaxed">Tukar 50 EXP untuk Putar Rolet Hadiah</p>
                    </div>
                    <div>
                        <a href="{{ route('gacha.koleksi') }}" class="inline-flex items-center gap-2 bg-gray-50 text-gray-700 hover:text-black px-4 py-2 rounded-full font-bold text-xs border border-gray-100 hover:border-black transition-all hover:shadow-sm">
                            <i class="fas fa-box-open text-black"></i> Koleksi Gacha
                        </a>
                    </div>
                </div>

                <!-- Animation Container -->
                <div class="relative w-[300px] h-[300px] md:w-[440px] md:h-[440px] mx-auto mb-10 flex items-center justify-center">
                    
                    <!-- Wheel Container -->
                    <div id="wheel-container" class="absolute inset-0 rounded-full shadow-2xl overflow-hidden transition-transform ease-[cubic-bezier(0.1,0.7,0.1,1)]" style="transition-duration: 4s;">
                        <canvas id="wheelCanvas" width="800" height="800" class="w-full h-full scale-100"></canvas>
                    </div>
                    
                    <!-- Center Spin Button & Pointer (Fixed) -->
                    <button id="spin-btn" class="relative z-20 w-20 h-20 md:w-24 md:h-24 bg-black rounded-full border-4 border-white shadow-2xl flex items-center justify-center group transition-transform active:scale-95 disabled:bg-gray-800 disabled:cursor-not-allowed" {{ (auth()->user()->exp ?? 0) < 50 ? 'disabled' : '' }}>
                        <!-- The UP pointing triangle fixed to the center button -->
                        <div class="absolute -top-4 md:-top-5 left-1/2 -translate-x-1/2 w-0 h-0 border-l-[12px] md:border-l-[15px] border-l-transparent border-r-[12px] md:border-r-[15px] border-r-transparent border-b-[20px] md:border-b-[25px] border-b-black group-disabled:border-b-gray-800 transition-colors"></div>
                        
                        <span class="text-white font-black text-lg tracking-widest uppercase relative z-10" id="btn-text">
                            @if((auth()->user()->exp ?? 0) < 50)
                                <i class="fas fa-lock text-xl"></i>
                            @else
                                <span class="flex flex-col leading-none items-center"><span class="text-xs">SPIN</span><span class="text-[8px] text-gray-400 mt-1">-50 EXP</span></span>
                            @endif
                        </span>
                    </button>
                    
                    <!-- Burst Wave & Particles -->
                    <div id="burst-wave" class="absolute inset-0 border-4 border-black rounded-full scale-0 opacity-0 pointer-events-none z-10"></div>
                    <div id="particles" class="absolute inset-0 pointer-events-none opacity-0 z-10">
                        <i class="fas fa-star absolute top-4 left-10 text-yellow-400 text-2xl particle"></i>
                        <i class="fas fa-circle absolute bottom-8 right-10 text-blue-400 text-xl particle"></i>
                        <i class="fas fa-certificate absolute top-10 right-10 text-pink-400 text-lg particle"></i>
                        <i class="fas fa-star absolute bottom-10 left-10 text-green-400 text-2xl particle"></i>
                    </div>
                </div>
                
                <!-- EXP Indicator -->
                <!-- EXP Indicator -->
                <button type="button" onclick="showExpInfo()" class="inline-flex items-center justify-center gap-3 bg-gray-50 hover:bg-gray-100 rounded-2xl px-6 py-3 mb-4 border border-gray-100 hover:border-gray-300 transition-all focus:outline-none cursor-pointer">
                    <i class="fas fa-question-circle text-gray-400 absolute right-3 top-3 text-[10px]"></i>
                    <i class="fas fa-star text-yellow-500 text-xl animate-pulse"></i>
                    <div class="text-left">
                        <div class="text-[9px] uppercase font-black text-gray-400 tracking-[0.2em] leading-none mb-1">Sisa EXP Kamu</div>
                        <div class="text-3xl font-black text-gray-900 leading-none tracking-tighter" id="exp-amount">{{ auth()->user()->exp ?? 0 }}</div>
                    </div>
                </button>
                <!-- Small Help Text -->
                <p class="text-[10px] text-gray-400 font-semibold tracking-wide">Pencet lingkaran <b class="text-black">SPIN</b> di tengah untuk memutar</p>

            </div>

            <!-- RIGHT SIDE: Prize Rates Display -->
            <div class="w-full lg:w-1/2 flex flex-col text-left">
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight">Rate Hadiah</h3>
                        <p class="text-[11px] text-gray-500 mt-1 font-semibold uppercase tracking-widest">Distribusi Peluang</p>
                    </div>
                    <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center border border-gray-100">
                        <i class="fas fa-chart-pie text-gray-400 text-sm"></i>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
                    @foreach($prizes as $prize)
                    @php
                        $isZonk = $prize['name'] == 'Coba Lagi (Zonk)';
                        $isDoorprize = $prize['name'] == 'Doorprize Sepeda';
                        $isEpic = $prize['display'] == '2';
                    @endphp
                    <div class="flex items-center justify-between p-4 rounded-xl border {{ $isZonk ? 'bg-red-50 border-red-100' : ($isDoorprize ? 'bg-gradient-to-r from-yellow-50 to-orange-50 border-yellow-300 shadow-sm relative overflow-hidden' : ($isEpic ? 'bg-gradient-to-r from-indigo-50 to-purple-50 border-indigo-200 shadow-sm' : 'bg-white border-gray-100 hover:border-gray-900 hover:shadow-lg transition-all cursor-default')) }}">
                        @if($isDoorprize)
                            <!-- Shimmer effect for doorprize -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/50 to-transparent -translate-x-full animate-[shimmer_2s_infinite]"></div>
                        @endif
                        <div class="flex items-center gap-3 relative z-10">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $isZonk ? 'bg-red-100 text-red-500' : ($isDoorprize ? 'bg-gradient-to-tr from-yellow-400 to-orange-500 text-white shadow-inner animate-pulse' : ($isEpic ? 'bg-gradient-to-tr from-indigo-500 to-purple-600 text-white shadow-inner' : 'bg-black text-white')) }}">
                                <i class="fas {{ $isZonk ? 'fa-ghost' : ($isDoorprize ? 'fa-bicycle' : ($prize['name'] == 'Voucher Belanja 100k' ? 'fa-ticket-alt' : ($isEpic ? 'fa-tshirt' : 'fa-gift'))) }} text-[12px]"></i>
                            </div>
                            <span class="text-xs font-bold leading-tight {{ $isZonk ? 'text-red-700' : ($isDoorprize ? 'text-orange-800' : ($isEpic ? 'text-indigo-800' : 'text-gray-900')) }}">
                                {{ $prize['name'] }}
                                @if($isDoorprize) <i class="fas fa-crown text-yellow-500 ml-1 text-[10px]"></i>
                                @elseif($isEpic) <i class="fas fa-gem text-indigo-400 ml-1 text-[10px]"></i> @endif
                            </span>
                        </div>
                        <span class="relative z-10 text-sm font-black {{ $isZonk ? 'text-red-600' : ($isDoorprize ? 'text-orange-600' : ($isEpic ? 'text-indigo-600' : 'text-gray-400')) }}">
                            {{ $prize['display'] }}%
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Audio Effects -->
<audio id="audio-spin" preload="auto">
    <source src="{{ asset('audio/sfx/wheel-spin .mp3') }}" type="audio/mpeg">
</audio>
<audio id="audio-win" preload="auto">
    <source src="{{ asset('audio/sfx/windows-ding.mp3') }}" type="audio/mpeg">
</audio>
<audio id="audio-zonk" preload="auto">
    <source src="{{ asset('audio/sfx/windows-xp-error-sound.mp3') }}" type="audio/mpeg">
</audio>

<style>
/* Burst Wave */
@keyframes wave {
    0% { transform: scale(0.9); opacity: 1; border-width: 30px; }
    100% { transform: scale(1.5); opacity: 0; border-width: 0px; }
}
.anim-wave { animation: wave 0.6s cubic-bezier(0.1, 0.8, 0.3, 1) forwards; }

/* Particle Explosion */
@keyframes explode {
    0% { transform: translate(0, 0) scale(0.5); opacity: 1; }
    100% { transform: translate(var(--tx), var(--ty)) scale(1.5) rotate(var(--rot)); opacity: 0; }
}
.particle { --tx: 0px; --ty: 0px; --rot: 0deg; }
.particle:nth-child(1) { --tx: -80px; --ty: -90px; --rot: 45deg; }
.particle:nth-child(2) { --tx: 90px; --ty: 50px; --rot: 90deg; }
.particle:nth-child(3) { --tx: 80px; --ty: -80px; --rot: 180deg; }
.particle:nth-child(4) { --tx: -70px; --ty: 80px; --rot: 270deg; }

.anim-particles .particle {
    animation: explode 0.8s cubic-bezier(0.1, 0.8, 0.3, 1) forwards;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Wheel Setup
    const canvas = document.getElementById('wheelCanvas');
    const ctx = canvas.getContext('2d');
    const wheelContainer = document.getElementById('wheel-container');
    
    // Inject Server Prizes Dynamically
    let prizesData = @json($prizes);
    
    // Acak (Shuffle) posisi kepingan di Roda agar warnanya tidak berkumpul di satu sisi
    prizesData = prizesData.sort(() => Math.random() - 0.5);

    const prizes = prizesData.map(p => p.name);
    const labels = prizesData.map(p => p.label);

    // Pastel colors for 10 slices
    const colors = [
        '#fecaca', // light red (ZONK)
        '#bbf7d0', // green (Kembalian EXP)
        '#fde047', // vivid yellow (Sepeda)
        '#fbcfe8', // light pink
        '#bfdbfe', // light blue
        '#fed7aa', // light orange
        '#e9d5ff', // light purple
        '#a7f3d0', // mint
        '#fef08a', // light yellow
        '#bae6fd'  // sky blue
    ];

    const numSlices = labels.length;
    const sliceAngle = 2 * Math.PI / numSlices;
    const sliceDeg = 360 / numSlices;

    function drawWheel() {
        const center = canvas.width / 2;
        const radius = center;
        
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        for (let i = 0; i < numSlices; i++) {
            const startAngle = i * sliceAngle;
            const endAngle = startAngle + sliceAngle;
            
            // Draw slice
            ctx.beginPath();
            ctx.moveTo(center, center);
            ctx.arc(center, center, radius, startAngle, endAngle);
            ctx.closePath();
            
            // Special gradient background strictly for SEPEDA and Epic tier
            if (labels[i] === 'SEPEDA') {
                ctx.fillStyle = '#f59e0b'; // Premium Gold Amber color
            } else if (labels[i] === 'Rp 100k' || labels[i] === 'Kaos') {
                ctx.fillStyle = '#8b5cf6'; // Violet / Indigo color
            } else if (labels[i] === 'ZONK') {
                ctx.fillStyle = '#fecaca'; // Light red (ZONK)
            } else if (labels[i] === '+40 EXP') {
                ctx.fillStyle = '#bbf7d0'; // Green (Kembalian EXP)
            } else {
                ctx.fillStyle = colors[i % colors.length];
            }
            
            ctx.fill();
            
            // Subtle slice border
            ctx.lineWidth = 1;
            ctx.strokeStyle = "rgba(255,255,255,0.3)";
            ctx.stroke();

            // Draw text
            ctx.save();
            ctx.translate(center, center);
            // Rotate to center of slice
            ctx.rotate(startAngle + sliceAngle / 2);
            ctx.textAlign = "right";
            ctx.textBaseline = "middle";
            ctx.fillStyle = "#374151"; // dark gray text
            ctx.font = "900 28px 'Inter', 'Poppins', sans-serif";
            
            // Custom styling overrides
            if(labels[i] === 'ZONK') {
                ctx.fillStyle = "#dc2626";
            } else if (labels[i] === 'SEPEDA') {
                ctx.fillStyle = "#ffffff"; // Bold white on gold
                ctx.font = "900 32px 'Inter', 'Poppins', sans-serif"; // Slightly larger
            } else if (labels[i] === 'Rp 100k' || labels[i] === 'Kaos') {
                ctx.fillStyle = "#ffffff";
                ctx.font = "900 28px 'Inter', 'Poppins', sans-serif";
            }
            
            // Position text pushing towards the edge
            ctx.fillText(labels[i].toUpperCase(), radius - 40, 0);
            ctx.restore();
        }
    }
    
    // Render initially
    drawWheel();

    // Spin Logic
    const btn = document.getElementById('spin-btn');
    const btnText = document.getElementById('btn-text');
    const wave = document.getElementById('burst-wave');
    const particles = document.getElementById('particles');
    const expDisplay = document.getElementById('exp-amount');
    const globalExpDisplays = document.querySelectorAll('.global-exp-display'); // For topbar 
    
    let isSpinning = false;
    let currentRotation = 0;

    // Sounds
    const spinSound = document.getElementById('audio-spin');
    const winSound = document.getElementById('audio-win');
    const zonkSound = document.getElementById('audio-zonk');

    function playSpinSound() {
        spinSound.currentTime = 0;
        spinSound.volume = 0.5;
        spinSound.play().catch(e => console.log("Audio play blocked by browser"));
    }

    //ubah exp
    btn.addEventListener('click', async function() {
        if (isSpinning || btn.disabled) return;
        
        let currentExp = parseInt(expDisplay.textContent);
        if (currentExp < 50) return;

        // Init Animation State
        isSpinning = true;
        btn.disabled = true;
        btnText.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        // Remove previous effects
        wave.classList.remove('anim-wave', 'border-gray-200', 'border-yellow-400');
        particles.classList.remove('anim-particles', 'opacity-100');
        
        // Start continuous fake spin
        wheelContainer.style.transitionDuration = '2s';
        wheelContainer.style.transitionTimingFunction = 'linear';
        currentRotation += 1080; // 3 full spins while waiting for network
        wheelContainer.style.transform = `rotate(${currentRotation}deg)`;
        
        // Play spin sound
        spinSound.currentTime = 0;
        spinSound.volume = 0.5;
        spinSound.play().catch(e => console.log("Audio play blocked by browser"));
        
        // ubah exp tampilan
        animateValue(expDisplay, currentExp, currentExp - 50, 500);
        globalExpDisplays.forEach(el => animateValue(el, currentExp, currentExp - 50, 500));

        try {
            // At least 2.5 seconds of intense animation buildup
            const animPromise = new Promise(res => setTimeout(res, 2500));
            
            // Backend Request
            const fd = new FormData();
            fd.append('_token', '{{ csrf_token() }}');
            
            const fetchReq = fetch("{{ route('gacha.spin') }}", {
                method: 'POST',
                body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(r => r.json());

            // Wait for both fetch and min animation
            const [_, res] = await Promise.all([animPromise, fetchReq]);

            if (res.status === 'success') {
                // Determine target slice index
                const prizeIndex = prizes.findIndex(p => p === res.prize);
                
                // MATH:
                // Canvas 0 degrees starts at Right (3 o'clock).
                // Pointer is pointing UP (12 o'clock), which is -90 degrees in CSS rotate vs Canvas offset.
                const sliceCenter = (prizeIndex * sliceDeg) + (sliceDeg / 2);
                
                // To align the slice center with the UP pointer (-90 deg), we need:
                // sliceCenter + finalRotation = 270 (which is -90 equivalent in mod 360) 
                const targetMod = (270 - sliceCenter + 360) % 360;
                
                const extraSpins = 360 * 4; // 4 more spins during deceleration
                const currentMod = currentRotation % 360;
                let neededRotation = targetMod - currentMod;
                if(neededRotation < 0) neededRotation += 360;
                
                // Add slight random offset so it doesn't always land rigidly in dead center 
                // Using 70% of slice half-width to prevent crossing lines
                const randomOffset = (Math.random() - 0.5) * (sliceDeg * 0.7);
                
                currentRotation += neededRotation + extraSpins + randomOffset;
                
                // Apply final deceleration CSS
                wheelContainer.style.transitionDuration = '4.5s';
                wheelContainer.style.transitionTimingFunction = 'cubic-bezier(0.1, 0.8, 0.1, 1)'; // Decelerate smoothly
                wheelContainer.style.transform = `rotate(${currentRotation}deg)`;

                // Wait for deceleration to finish exactly
                setTimeout(() => {
                    // Set appropriate burst effect
                    if (res.is_zonk) {
                        wave.classList.add('border-gray-200');
                    } else {
                        wave.classList.add('border-yellow-400');
                        particles.classList.add('anim-particles', 'opacity-100');
                    }

                    // Trigger burst wave exactly upon stopping
                    wave.classList.add('anim-wave');
                    btnText.innerHTML = 'SPIN';

                    // Stop spin sound and play Result Sound
                    spinSound.pause();
                    if (res.is_zonk) zonkSound.play().catch(() => {});
                    else winSound.play().catch(() => {});

                    // Pop SweetAlert
                    setTimeout(() => {
                        Swal.fire({
                            title: res.is_zonk ? 'ZONK!' : 'BERHASIL!',
                            text: res.message,
                            html: res.is_zonk ? 
                                  `<p class="text-xs text-gray-500 font-medium mt-2">Coba keberuntunganmu lagi ya!</p>` : 
                                  `<div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl p-5 mt-4 border border-yellow-100 shadow-inner">
                                    <p class="text-[10px] uppercase font-bold text-gray-400 mb-2">Hadiah Kamu</p>
                                    <p class="text-xl font-black text-black leading-tight mb-2">${res.prize}</p>
                                   </div>` + (res.show_collection ? `<p class="text-xs font-semibold text-blue-600 mt-4 bg-blue-50 py-3 rounded-xl border border-blue-100">Silakan cek <a href="/gacha/koleksi" class="underline font-bold text-blue-700 mx-1">Koleksi Saya</a> dan hubungi Admin Perpustakaan untuk mengambil item fisik ini!</p>` : ''),
                            icon: res.is_zonk ? 'error' : 'success',
                            background: '#fff',
                            color: '#000',
                            confirmButtonColor: '#000',
                            confirmButtonText: 'Lanjut',
                            customClass: {
                                popup: 'rounded-[2.5rem] border border-gray-100 shadow-2xl',
                                confirmButton: 'rounded-xl px-10 font-bold tracking-widest text-[11px] py-4 uppercase',
                                title: 'font-black tracking-tighter text-4xl mt-2'
                            }
                        }).then(() => {
                            resetGachaUI(res.new_exp);
                        });
                    }, 500); 
                }, 4500); // Sync with transition-duration
            }

        } catch (e) {
            Swal.fire('Oops', 'Koneksi terputus!', 'error');
            resetGachaUI(currentExp);
        }
    });

    //ubah exp recheck
    function resetGachaUI(finalExp) {
        isSpinning = false;
        expDisplay.textContent = finalExp;
        globalExpDisplays.forEach(el => el.textContent = finalExp);
        
        // Re-check button state
        if (finalExp < 50) {
            btn.disabled = true;
            btnText.innerHTML = '<i class="fas fa-lock text-xl"></i>';
        } else {
            btn.disabled = false;
            btnText.innerHTML = 'SPIN';
        }
    }

    // Number roller animation function
    function animateValue(obj, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            obj.innerHTML = Math.floor(progress * (end - start) + start);
            if (progress < 1) {
                window.requestAnimationFrame(step);
            } else {
                obj.innerHTML = end;
            }
        };
        window.requestAnimationFrame(step);
    }
});
</script>
@endsection
