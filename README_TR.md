# AI Görsel Oluşturucu

Yapay zeka kullanarak görsel oluşturan modern bir web uygulaması. **OpenAI** ve **Google Gemini** API'lerini destekler.

![Python](https://img.shields.io/badge/Python-3776AB?style=for-the-badge&logo=python&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Flask](https://img.shields.io/badge/Flask-000000?style=for-the-badge&logo=flask&logoColor=white)
![License](https://img.shields.io/badge/Lisans-MIT-green.svg?style=for-the-badge)

Türkçe | [English](README.md)

## Mevcut Versiyonlar

Bu proje iki farklı dilde mevcuttur:

| Versiyon | Klasör | Gereksinimler |
|----------|--------|---------------|
| **Python** | [/python](./python) | Python 3.8+, Flask |
| **PHP** | [/php](./php) | PHP 7.4+, cURL eklentisi |

## Özellikler

- Glassmorphism tasarımlı modern, responsive arayüz
- **OpenAI** ve **Google Gemini** görsel oluşturma desteği
- Çoklu görsel boyutu seçenekleri (1024x1024, 1280x720, 720x1280, 1216x896)
- Tek tıkla görsel indirme
- Gerçek zamanlı yükleme animasyonları
- Mobil uyumlu tasarım

## Hızlı Başlangıç

### Python Versiyonu

```bash
cd python
pip install -r requirements.txt
# app.py dosyasında API anahtarlarını yapılandırın
python app.py
```

### PHP Versiyonu

```bash
cd php
# api.php dosyasında API anahtarlarını yapılandırın
php -S localhost:8000
```

## API Anahtarı Alma

### Google Gemini
1. [Google AI Studio](https://aistudio.google.com/apikey) adresine gidin
2. Yeni bir API anahtarı oluşturun
3. Kopyalayıp yapılandırmaya yapıştırın

### OpenAI
1. [OpenAI Platform](https://platform.openai.com/api-keys) adresine gidin
2. Yeni bir API anahtarı oluşturun
3. Kopyalayıp yapılandırmaya yapıştırın

## Lisans

Bu proje MIT Lisansı altında lisanslanmıştır - detaylar için [LICENSE](LICENSE) dosyasına bakın.

## Geliştirici

Made by [Emir Can](https://emircan.tr)

## Katkıda Bulunma

Katkılarınız memnuniyetle karşılanır! Lütfen Pull Request göndermekten çekinmeyin.
