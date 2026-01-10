# AI Image Generator - PHP Version

PHP implementation of the AI Image Generator.

## Requirements

- PHP 7.4 or higher
- cURL extension enabled

## Setup

1. Configure API keys in `api.php`:
```php
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
```

2. Start PHP development server:
```bash
php -S localhost:8000
```

3. Open browser and go to `http://localhost:8000`

## Files

- `index.php` - Frontend (HTML/CSS/JS)
- `api.php` - Backend API handler
