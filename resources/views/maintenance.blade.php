<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Under Maintenance</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-x: hidden;
        }

        .container {
            max-width: 600px;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            padding: 60px 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            position: relative;
        }

        .icon-container {
            margin-bottom: 30px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .icon {
            width: 120px;
            height: 120px;
            margin: 0 auto;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-center;
            position: relative;
        }

        .icon::before {
            content: '🔧';
            font-size: 60px;
            animation: rotate 4s linear infinite;
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        h1 {
            font-size: 42px;
            color: #333;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .message {
            font-size: 18px;
            color: #666;
            line-height: 1.8;
            margin-bottom: 30px;
        }

        .estimated-time {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            margin-top: 20px;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .dots {
            display: inline-block;
            margin-left: 5px;
        }

        .dots span {
            animation: blink 1.4s infinite;
            animation-fill-mode: both;
        }

        .dots span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .dots span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes blink {

            0%,
            80%,
            100% {
                opacity: 0;
            }

            40% {
                opacity: 1;
            }
        }

        .contact {
            margin-top: 40px;
            font-size: 14px;
            color: #888;
        }

        /* Animated background particles */
        .particle {
            position: fixed;
            width: 10px;
            height: 10px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            pointer-events: none;
            animation: rise 15s infinite ease-in;
        }

        @keyframes rise {
            0% {
                bottom: -10%;
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                bottom: 110%;
                opacity: 0;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 40px 30px;
            }

            h1 {
                font-size: 32px;
            }

            .message {
                font-size: 16px;
            }

            .icon {
                width: 100px;
                height: 100px;
            }

            .icon::before {
                font-size: 50px;
            }
        }
    </style>
</head>

<body>
    <!-- Animated background particles -->
    <div class="particle" style="left: 10%; animation-delay: 0s;"></div>
    <div class="particle" style="left: 20%; animation-delay: 2s;"></div>
    <div class="particle" style="left: 30%; animation-delay: 4s;"></div>
    <div class="particle" style="left: 40%; animation-delay: 6s;"></div>
    <div class="particle" style="left: 50%; animation-delay: 8s;"></div>
    <div class="particle" style="left: 60%; animation-delay: 10s;"></div>
    <div class="particle" style="left: 70%; animation-delay: 12s;"></div>
    <div class="particle" style="left: 80%; animation-delay: 14s;"></div>
    <div class="particle" style="left: 90%; animation-delay: 16s;"></div>

    <div class="container">
        <div class="icon-container">
            <div class="icon"></div>
        </div>

        <h1>We'll Be Back Soon!</h1>

        <p class="message">
            {{ $message ?? 'We are currently performing scheduled maintenance. We will be back shortly!' }}
        </p>

        @if(isset($estimatedTime) && $estimatedTime)
            <div class="estimated-time">
                Expected back: {{ \Carbon\Carbon::parse($estimatedTime)->format('F j, Y g:i A') }}
            </div>
        @else
            <p class="message">
                We are working hard to improve our services<span
                    class="dots"><span>.</span><span>.</span><span>.</span></span>
            </p>
        @endif

        <p class="contact">
            Thank you for your patience!
        </p>
    </div>

    <script>
        // Additional particles animation
        setInterval(() => {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
            document.body.appendChild(particle);

            setTimeout(() => {
                particle.remove();
            }, 20000);
        }, 2000);
    </script>
</body>

</html>