@extends('admin-panel::layouts.guest')
@section('title', 'Verify OTP')


@section('content')
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-2xl shadow-lg relative">
        <div>
            <h1 class="text-3xl font-bold text-center text-gray-800">Enter OTP</h1>
            <p class="mt-2 text-sm text-center text-gray-600">
                We've sent a 6-digit code to: <strong>{{ session('otp_email') }}</strong>
            </p>
        </div>

        {{-- Success Message for Resend --}}
        <div id="resend-success" class="hidden p-4 text-sm text-green-700 bg-green-100 rounded-lg text-center">
            New OTP sent successfully!
        </div>

        @if ($errors->any())
            <div class="p-4 text-sm text-red-700 bg-red-100 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form class="space-y-6" method="POST" action="{{ route('otp.verify') }}">
            @csrf
            <div>
                <label for="otp" class="block text-sm font-semibold text-gray-700">One-Time Password (OTP)</label>
                <input id="otp" name="otp" type="text" inputmode="numeric" maxlength="6" required autofocus
                    class="w-full px-4 py-3 mt-1 text-gray-700 bg-gray-100 rounded-xl focus:ring-primary text-center text-2xl tracking-[1rem]">
            </div>
            <div>
                <button type="submit" class="w-full px-6 py-3 text-lg font-semibold text-white rounded-xl"
                    style="background-color: var(--primary);">
                    Verify & Login
                </button>
            </div>
        </form>

        <div class="text-center">
            <p class="text-sm text-gray-600">
                Didn't receive code?
                <span id="timer-container" class="font-bold text-gray-500">
                    Resend in <span id="timer">02:00</span>
                </span>
                <a href="#" id="resend-link" class="hidden font-bold text-primary hover:underline">
                    Resend OTP
                </a>
                <span id="sending-msg" class="hidden text-gray-500 italic">Sending...</span>
            </p>
        </div>
    </div>

    <script>
        let timeLeft = 120; // 2 minutes in seconds
        const timerEl = document.getElementById('timer');
        const timerContainer = document.getElementById('timer-container');
        const resendLink = document.getElementById('resend-link');
        const sendingMsg = document.getElementById('sending-msg');
        const successMsg = document.getElementById('resend-success');
        let timerId;

        function formatTime(seconds) {
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        }

        function startTimer() {
            // Reset UI
            timeLeft = 120;
            timerEl.textContent = formatTime(timeLeft);
            timerContainer.classList.remove('hidden');
            resendLink.classList.add('hidden');

            clearInterval(timerId);
            timerId = setInterval(() => {
                timeLeft--;
                timerEl.textContent = formatTime(timeLeft);

                if (timeLeft <= 0) {
                    clearInterval(timerId);
                    timerContainer.classList.add('hidden');
                    resendLink.classList.remove('hidden');
                }
            }, 1000);
        }

        // Start timer on page load
        startTimer();

        resendLink.addEventListener('click', async (e) => {
            e.preventDefault();

            // UI Updates
            resendLink.classList.add('hidden');
            sendingMsg.classList.remove('hidden');
            successMsg.classList.add('hidden');

            try {
                const response = await fetch("{{ route('otp.resend') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                });

                if (response.ok) {
                    successMsg.classList.remove('hidden');
                    sendingMsg.classList.add('hidden');
                    // Restart timer
                    startTimer();
                } else {
                    alert('Failed to resend OTP. Please try again.');
                    resendLink.classList.remove('hidden');
                    sendingMsg.classList.add('hidden');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Something went wrong. Please check your connection.');
                resendLink.classList.remove('hidden');
                sendingMsg.classList.add('hidden');
            }
        });
    </script>
@endsection