<?php
header('Content-Type: application/json');

// API Ayarları
$config = [
    'openai' => [
        'base_url' => 'http://127.0.0.1:8045/v1/chat/completions',
        'api_key' => 'YOUR_OPENAI_API_KEY',
        'model' => 'gemini-3-pro-image'
    ],
    'gemini' => [
        'base_url' => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-exp-image-generation:generateContent',
        'api_key' => 'YOUR_GEMINI_API_KEY'
    ]
];

function generateWithOpenAI($prompt, $size, $config) {
    // Boyut bazlı model mapping
    $model = $config['model'];
    if ($size === '1280x720') {
        $model .= '-16-9';
    } elseif ($size === '720x1280') {
        $model .= '-9-16';
    } elseif ($size === '1216x896') {
        $model .= '-4-3';
    }
    // 1024x1024 varsayılan modeldir

    $data = [
        'model' => $model,
        'messages' => [
            ['role' => 'user', 'content' => $prompt]
        ],
        'size' => $size
    ];

    $ch = curl_init($config['base_url']);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $config['api_key']
        ],
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_TIMEOUT => 120
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        throw new Exception('cURL Error: ' . $error);
    }

    if ($httpCode !== 200) {
        throw new Exception('API Error: HTTP ' . $httpCode);
    }

    $result = json_decode($response, true);

    if (!isset($result['choices'][0]['message']['content'])) {
        throw new Exception('Geçersiz API yanıtı');
    }

    return $result['choices'][0]['message']['content'];
}

function generateWithGemini($prompt, $config) {
    $url = $config['base_url'] . '?key=' . $config['api_key'];

    $data = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ],
        'generationConfig' => [
            'responseModalities' => ['TEXT', 'IMAGE']
        ]
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json'
        ],
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_TIMEOUT => 120
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        throw new Exception('cURL Error: ' . $error);
    }

    if ($httpCode !== 200) {
        $errorData = json_decode($response, true);
        $errorMsg = $errorData['error']['message'] ?? 'HTTP ' . $httpCode;
        throw new Exception('Gemini API Error: ' . $errorMsg);
    }

    $result = json_decode($response, true);

    // Gemini yanıtından görsel verisini çıkar
    if (isset($result['candidates'][0]['content']['parts'])) {
        foreach ($result['candidates'][0]['content']['parts'] as $part) {
            if (isset($part['inlineData'])) {
                $mimeType = $part['inlineData']['mimeType'];
                $base64Data = $part['inlineData']['data'];
                return 'data:' . $mimeType . ';base64,' . $base64Data;
            }
        }
    }

    throw new Exception('Görsel oluşturulamadı');
}

function extractImage($content) {
    // Base64 data URI formatı
    if (preg_match('/data:image\/[^;]+;base64,[A-Za-z0-9+\/=]+/', $content, $matches)) {
        return ['image' => $matches[0], 'type' => 'base64'];
    }

    // Markdown formatında base64
    if (preg_match('/!\[.*?\]\((data:image\/[^;]+;base64,[A-Za-z0-9+\/=]+)\)/', $content, $matches)) {
        return ['image' => $matches[1], 'type' => 'base64'];
    }

    // URL formatı
    if (preg_match('/https?:\/\/[^\s\)]+\.(png|jpg|jpeg|gif|webp)/i', $content, $matches)) {
        return ['image' => $matches[0], 'type' => 'url'];
    }

    // Ham base64 (data: prefix olmadan)
    if (preg_match('/^[A-Za-z0-9+\/=]{100,}$/', trim($content))) {
        return ['image' => 'data:image/png;base64,' . trim($content), 'type' => 'base64'];
    }

    // Olduğu gibi döndür
    return ['image' => $content, 'type' => 'raw'];
}

// POST isteği kontrolü
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $input = json_decode(file_get_contents('php://input'), true);

        $prompt = trim($input['prompt'] ?? '');
        $size = $input['size'] ?? '1024x1024';
        $provider = $input['provider'] ?? 'gemini';

        if (empty($prompt)) {
            throw new Exception('Prompt boş olamaz');
        }

        if ($provider === 'gemini') {
            $content = generateWithGemini($prompt, $config['gemini']);
            $imageData = extractImage($content);
        } else {
            $content = generateWithOpenAI($prompt, $size, $config['openai']);
            $imageData = extractImage($content);
        }

        echo json_encode([
            'success' => true,
            'image' => $imageData['image'],
            'type' => $imageData['type'],
            'provider' => $provider
        ]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}
?>
