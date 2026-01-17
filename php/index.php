<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Image Generator</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><defs><linearGradient id='g' x1='0%25' y1='0%25' x2='100%25' y2='100%25'><stop offset='0%25' style='stop-color:%237c3aed'/><stop offset='100%25' style='stop-color:%23ec4899'/></linearGradient></defs><rect width='100' height='100' rx='20' fill='url(%23g)'/><text x='50' y='68' font-size='50' text-anchor='middle' fill='white'>✨</text></svg>">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #0a0a0f;
            min-height: 100vh;
            color: #fff;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        .bg-gradient {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 20%, rgba(120, 0, 255, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 80% 80%, rgba(255, 0, 128, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 50% 50%, rgba(0, 200, 255, 0.1) 0%, transparent 50%);
            z-index: -1;
            animation: pulse 8s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .grid-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: -1;
        }

        .main-content {
            flex: 1;
            padding: 40px 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 50px;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .logo-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #7c3aed, #ec4899);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            box-shadow: 0 10px 40px rgba(124, 58, 237, 0.4);
        }

        h1 {
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(135deg, #fff 0%, #a78bfa 50%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -1px;
        }

        .subtitle {
            color: rgba(255, 255, 255, 0.5);
            font-size: 1.1rem;
            font-weight: 400;
        }

        .input-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 40px;
            margin-bottom: 40px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
            position: relative;
            overflow: hidden;
        }

        .input-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        }

        .input-group {
            margin-bottom: 25px;
        }

        .input-label {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
        }

        .input-label i {
            color: #a78bfa;
        }

        textarea {
            width: 100%;
            height: 140px;
            padding: 20px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            background: rgba(0, 0, 0, 0.4);
            color: #fff;
            font-size: 16px;
            font-family: 'Inter', sans-serif;
            resize: none;
            transition: all 0.3s ease;
        }

        textarea:focus {
            outline: none;
            border-color: #7c3aed;
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.2);
        }

        textarea::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .provider-toggle {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .provider-btn {
            flex: 1;
            padding: 14px 20px;
            border-radius: 12px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.3);
            color: rgba(255, 255, 255, 0.6);
            font-size: 15px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .provider-btn:hover {
            border-color: rgba(255, 255, 255, 0.3);
            color: rgba(255, 255, 255, 0.8);
        }

        .provider-btn.active {
            border-color: #7c3aed;
            background: rgba(124, 58, 237, 0.2);
            color: #fff;
        }

        .provider-btn.active.openai {
            border-color: #10a37f;
            background: rgba(16, 163, 127, 0.2);
        }

        .provider-btn.active.gemini {
            border-color: #4285f4;
            background: rgba(66, 133, 244, 0.2);
        }

        .provider-btn i {
            font-size: 18px;
        }

        .provider-btn.openai i { color: #10a37f; }
        .provider-btn.gemini i { color: #4285f4; }

        .controls {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .size-wrapper {
            position: relative;
        }

        .size-select {
            appearance: none;
            padding: 16px 50px 16px 20px;
            border-radius: 12px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.4);
            color: #fff;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .size-select:focus {
            outline: none;
            border-color: #7c3aed;
        }

        .size-select:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .size-wrapper::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.5);
            pointer-events: none;
        }

        .generate-btn {
            flex: 1;
            min-width: 200px;
            padding: 18px 40px;
            background: linear-gradient(135deg, #7c3aed 0%, #ec4899 100%);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 17px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            position: relative;
            overflow: hidden;
        }

        .generate-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .generate-btn:hover::before {
            left: 100%;
        }

        .generate-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(124, 58, 237, 0.4);
        }

        .generate-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .generate-btn:disabled::before {
            display: none;
        }

        .result-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 40px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
            display: none;
        }

        .result-card.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .loading {
            text-align: center;
            padding: 60px 20px;
        }

        .loader {
            width: 80px;
            height: 80px;
            margin: 0 auto 30px;
            position: relative;
        }

        .loader::before,
        .loader::after {
            content: '';
            position: absolute;
            border-radius: 50%;
        }

        .loader::before {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #7c3aed, #ec4899);
            animation: pulse-loader 1.5s ease-in-out infinite;
        }

        .loader::after {
            width: 60%;
            height: 60%;
            background: #0a0a0f;
            top: 20%;
            left: 20%;
        }

        @keyframes pulse-loader {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.7; }
        }

        .loading-text {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.1rem;
        }

        .loading-text span {
            display: inline-block;
            animation: bounce 1.4s infinite ease-in-out;
        }

        .loading-text span:nth-child(1) { animation-delay: 0s; }
        .loading-text span:nth-child(2) { animation-delay: 0.1s; }
        .loading-text span:nth-child(3) { animation-delay: 0.2s; }

        @keyframes bounce {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-5px); }
        }

        .image-container {
            text-align: center;
        }

        .image-wrapper {
            position: relative;
            display: inline-block;
            margin-bottom: 30px;
        }

        .generated-image {
            max-width: 100%;
            max-height: 600px;
            border-radius: 16px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6);
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            pointer-events: none;
        }

        .provider-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }

        .provider-badge.openai {
            background: rgba(16, 163, 127, 0.9);
            color: #fff;
        }

        .provider-badge.gemini {
            background: rgba(66, 133, 244, 0.9);
            color: #fff;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .action-btn {
            padding: 14px 30px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .download-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            color: #fff;
        }

        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(16, 185, 129, 0.4);
        }

        .new-btn {
            background: transparent;
            border: 2px solid rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .new-btn:hover {
            border-color: rgba(255, 255, 255, 0.4);
            background: rgba(255, 255, 255, 0.05);
        }

        .error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 25px;
            border-radius: 16px;
            text-align: center;
            color: #fca5a5;
        }

        .error i {
            font-size: 40px;
            margin-bottom: 15px;
            color: #ef4444;
        }

        footer {
            text-align: center;
            padding: 30px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            background: rgba(0, 0, 0, 0.3);
        }

        footer p {
            color: rgba(255, 255, 255, 0.4);
            font-size: 14px;
        }

        footer a {
            color: #a78bfa;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        footer a:hover {
            color: #ec4899;
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 2rem;
            }

            .input-card, .result-card {
                padding: 25px;
            }

            .controls {
                flex-direction: column;
            }

            .size-wrapper, .generate-btn {
                width: 100%;
            }

            .provider-toggle {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="bg-gradient"></div>
    <div class="grid-overlay"></div>

    <main class="main-content">
        <div class="container">
            <header class="header">
                <div class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-wand-magic-sparkles"></i>
                    </div>
                </div>
                <h1>AI Image Generator</h1>
                <p class="subtitle">Imagine, write, create</p>
            </header>

            <div class="input-card">
                <div class="input-group">
                    <label class="input-label">
                        <i class="fas fa-robot"></i>
                        AI Provider
                    </label>
                    <div class="provider-toggle">
                        <button type="button" class="provider-btn openai" data-provider="openai">
                            <i class="fas fa-bolt"></i>
                            OpenAI
                        </button>
                        <button type="button" class="provider-btn gemini active" data-provider="gemini">
                            <i class="fas fa-gem"></i>
                            Gemini
                        </button>
                    </div>
                </div>

                <div class="input-group">
                    <label class="input-label">
                        <i class="fas fa-pen-fancy"></i>
                        Prompt
                    </label>
                    <textarea
                        id="prompt"
                        placeholder="Describe the image you want to create in detail...

Example: A cyberpunk style Tokyo street on a rainy night illuminated with neon lights, with reflections and steam effects"
                    ></textarea>
                </div>

                <div class="controls">
                    <div class="size-wrapper">
                        <select id="size" class="size-select" disabled title="Gemini automatically determines size">
                            <option value="1024x1024">1024 × 1024 (Square)</option>
                            <option value="1280x720">1280 × 720 (Wide)</option>
                            <option value="720x1280">720 × 1280 (Portrait)</option>
                            <option value="1216x896">1216 × 896 (4:3)</option>
                        </select>
                    </div>
                    <button id="generateBtn" class="generate-btn">
                        <i class="fas fa-sparkles"></i>
                        Generate Image
                    </button>
                </div>
            </div>

            <div id="resultCard" class="result-card">
                <div id="loading" class="loading" style="display: none;">
                    <div class="loader"></div>
                    <p class="loading-text">
                        Generating image<span>.</span><span>.</span><span>.</span>
                    </p>
                </div>
                <div id="imageContainer" class="image-container" style="display: none;">
                    <div class="image-wrapper">
                        <img id="generatedImage" class="generated-image" alt="Generated image">
                        <div class="image-overlay"></div>
                        <span id="providerBadge" class="provider-badge"></span>
                    </div>
                    <div class="action-buttons">
                        <a id="downloadBtn" class="action-btn download-btn" download="ai-image.png">
                            <i class="fas fa-download"></i>
                            Download
                        </a>
                        <button id="newBtn" class="action-btn new-btn">
                            <i class="fas fa-plus"></i>
                            New Image
                        </button>
                    </div>
                </div>
                <div id="errorContainer" class="error" style="display: none;">
                    <i class="fas fa-circle-exclamation"></i>
                    <p id="errorText"></p>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>Made by <a href="https://emircan.tr" target="_blank">Emir Can</a></p>
    </footer>

    <script>
        const promptInput = document.getElementById('prompt');
        const sizeSelect = document.getElementById('size');
        const generateBtn = document.getElementById('generateBtn');
        const resultCard = document.getElementById('resultCard');
        const loading = document.getElementById('loading');
        const imageContainer = document.getElementById('imageContainer');
        const generatedImage = document.getElementById('generatedImage');
        const downloadBtn = document.getElementById('downloadBtn');
        const newBtn = document.getElementById('newBtn');
        const errorContainer = document.getElementById('errorContainer');
        const errorText = document.getElementById('errorText');
        const providerBtns = document.querySelectorAll('.provider-btn');
        const providerBadge = document.getElementById('providerBadge');

        let selectedProvider = 'gemini';

        // Disable size selection when Gemini is selected on page load
        sizeSelect.disabled = true;
        sizeSelect.title = 'Gemini automatically determines size';

        // Provider selection
        providerBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                providerBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                selectedProvider = btn.dataset.provider;

                // Disable size selection when Gemini is selected
                if (selectedProvider === 'gemini') {
                    sizeSelect.disabled = true;
                    sizeSelect.title = 'Gemini automatically determines size';
                } else {
                    sizeSelect.disabled = false;
                    sizeSelect.title = '';
                }
            });
        });

        generateBtn.addEventListener('click', async () => {
            const prompt = promptInput.value.trim();

            if (!prompt) {
                promptInput.focus();
                promptInput.style.borderColor = '#ef4444';
                setTimeout(() => {
                    promptInput.style.borderColor = 'rgba(255, 255, 255, 0.1)';
                }, 2000);
                return;
            }

            resultCard.classList.add('active');
            loading.style.display = 'block';
            imageContainer.style.display = 'none';
            errorContainer.style.display = 'none';
            generateBtn.disabled = true;
            generateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';

            try {
                const response = await fetch('api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        prompt: prompt,
                        size: sizeSelect.value,
                        provider: selectedProvider
                    })
                });

                const data = await response.json();

                if (data.error) {
                    throw new Error(data.error);
                }

                loading.style.display = 'none';
                imageContainer.style.display = 'block';

                let imageUrl;

                if (data.type === 'base64') {
                    imageUrl = data.image;
                } else if (data.type === 'url') {
                    imageUrl = data.image;
                } else {
                    if (data.image.startsWith('data:image')) {
                        imageUrl = data.image;
                    } else {
                        imageUrl = 'data:image/png;base64,' + data.image;
                    }
                }

                generatedImage.src = imageUrl;
                downloadBtn.href = imageUrl;

                // Show provider badge
                providerBadge.textContent = data.provider === 'gemini' ? 'Gemini' : 'OpenAI';
                providerBadge.className = 'provider-badge ' + data.provider;

            } catch (error) {
                loading.style.display = 'none';
                errorContainer.style.display = 'block';
                errorText.textContent = error.message;
            } finally {
                generateBtn.disabled = false;
                generateBtn.innerHTML = '<i class="fas fa-sparkles"></i> Generate Image';
            }
        });

        newBtn.addEventListener('click', () => {
            promptInput.value = '';
            promptInput.focus();
            resultCard.classList.remove('active');
        });

        promptInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                generateBtn.click();
            }
        });
    </script>
</body>
</html>
