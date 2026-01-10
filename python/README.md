# AI Image Generator - Python Version

Python/Flask implementation of the AI Image Generator.

## Requirements

- Python 3.8 or higher
- Flask
- OpenAI Python SDK
- Requests

## Setup

1. Create virtual environment (optional but recommended):
```bash
python -m venv venv
source venv/bin/activate  # On Windows: venv\Scripts\activate
```

2. Install dependencies:
```bash
pip install -r requirements.txt
```

3. Configure API keys in `app.py`:
```python
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
```

4. Run the application:
```bash
python app.py
```

5. Open browser and go to `http://localhost:5000`

## Files

- `app.py` - Flask backend
- `templates/index.html` - Frontend (HTML/CSS/JS)
- `requirements.txt` - Python dependencies
