from flask import Flask, render_template, request, jsonify
from openai import OpenAI
import requests
import base64
import re

app = Flask(__name__)

# API Ayarları
CONFIG = {
    'openai': {
        'base_url': 'http://127.0.0.1:8045/v1',
        'api_key': 'YOUR_OPENAI_API_KEY',
        'model': 'gemini-3-pro-image'
    },
    'gemini': {
        'base_url': 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-exp-image-generation:generateContent',
        'api_key': 'YOUR_GEMINI_API_KEY'
    }
}

# OpenAI client
openai_client = OpenAI(
    base_url=CONFIG['openai']['base_url'],
    api_key=CONFIG['openai']['api_key']
)

def generate_with_openai(prompt, size):
    """OpenAI API ile görsel oluştur"""
    # Boyut bazlı model mapping
    model = CONFIG['openai']['model']
    if size == '1280x720':
        model += '-16-9'
    elif size == '720x1280':
        model += '-9-16'
    elif size == '1216x896':
        model += '-4-3'
    
    response = openai_client.chat.completions.create(
        model=model,
        extra_body={"size": size},
        messages=[{
            "role": "user",
            "content": prompt
        }]
    )
    return response.choices[0].message.content

def generate_with_gemini(prompt):
    """Gemini API ile görsel oluştur"""
    url = f"{CONFIG['gemini']['base_url']}?key={CONFIG['gemini']['api_key']}"

    payload = {
        "contents": [{
            "parts": [{"text": prompt}]
        }],
        "generationConfig": {
            "responseModalities": ["TEXT", "IMAGE"]
        }
    }

    response = requests.post(url, json=payload, timeout=120)

    if response.status_code != 200:
        error_msg = response.json().get('error', {}).get('message', f'HTTP {response.status_code}')
        raise Exception(f'Gemini API Error: {error_msg}')

    result = response.json()

    # Gemini yanıtından görsel verisini çıkar
    if 'candidates' in result and result['candidates']:
        parts = result['candidates'][0].get('content', {}).get('parts', [])
        for part in parts:
            if 'inlineData' in part:
                mime_type = part['inlineData']['mimeType']
                base64_data = part['inlineData']['data']
                return f'data:{mime_type};base64,{base64_data}'

    raise Exception('Görsel oluşturulamadı')

def extract_image(content):
    """İçerikten görsel verisini çıkar"""
    # Base64 data URI formatı
    base64_match = re.search(r'data:image/[^;]+;base64,[A-Za-z0-9+/=]+', content)
    if base64_match:
        return {'image': base64_match.group(0), 'type': 'base64'}

    # Markdown formatında base64
    md_match = re.search(r'!\[.*?\]\((data:image/[^;]+;base64,[A-Za-z0-9+/=]+)\)', content)
    if md_match:
        return {'image': md_match.group(1), 'type': 'base64'}

    # URL formatı
    url_match = re.search(r'https?://[^\s\)]+\.(png|jpg|jpeg|gif|webp)', content, re.IGNORECASE)
    if url_match:
        return {'image': url_match.group(0), 'type': 'url'}

    # Ham base64 (data: prefix olmadan)
    if re.match(r'^[A-Za-z0-9+/=]{100,}$', content.strip()):
        return {'image': f'data:image/png;base64,{content.strip()}', 'type': 'base64'}

    # Olduğu gibi döndür
    return {'image': content, 'type': 'raw'}

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/generate', methods=['POST'])
def generate():
    try:
        data = request.json
        prompt = data.get('prompt', '').strip()
        size = data.get('size', '1024x1024')
        provider = data.get('provider', 'gemini')

        if not prompt:
            return jsonify({'error': 'Prompt boş olamaz'}), 400

        if provider == 'gemini':
            content = generate_with_gemini(prompt)
            image_data = extract_image(content)
        else:
            content = generate_with_openai(prompt, size)
            image_data = extract_image(content)

        return jsonify({
            'success': True,
            'image': image_data['image'],
            'type': image_data['type'],
            'provider': provider
        })

    except Exception as e:
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True, port=5000)
