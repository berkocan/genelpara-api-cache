# GenelPara API - Cache System

<div align="center">

![PHP](https://img.shields.io/badge/PHP-7.4%2B-blue)
![License](https://img.shields.io/badge/license-MIT-green)
![Status](https://img.shields.io/badge/status-production-brightgreen)

**API limitini aşmadan GenelPara API'yi kullanın**

[API Dokümantasyon](https://api.genelpara.com) · [Sorun Bildir](https://github.com/berkocan/genelpara-api-cache/issues)

</div>

---

## 📖 İçindekiler

- [Neden Cache?](#-neden-cache)
- [Özellikler](#-özellikler)
- [Kurulum](#-kurulum)
- [Kullanım](#-kullanım)
- [Yapılandırma](#️-yapılandırma)
- [Sorun Giderme](#-sorun-giderme)
- [Lisans](#-lisans)

---

## 🎯 Neden Cache?

GenelPara API'de **günlük 1.000 istek** limiti var. Web sitenizde her sayfa yüklemesinde API'ye istek atarsanız:

```
❌ Cache Olmadan:
1000 sayfa görüntüleme = 1000 API isteği → Limit aşıldı!

✅ Cache İle:
Cron job (15dk'da bir) = 96 API isteği/gün → Limit güvenli!
1000 sayfa görüntüleme = Cache'den okur (0 API isteği)
```

### Karşılaştırma

| Özellik | Cache YOK | Cache VAR |
|---------|-----------|-----------|
| **Günlük İstek** | 1000+ | 96 |
| **Sayfa Hızı** | 0.5-2 saniye | <0.01 saniye |
| **Sunucu Yükü** | Yüksek | Düşük |
| **Ban Riski** | Yüksek | Yok |

---

## ✨ Özellikler

- ✅ **15 dakikalık cache** - API 15dk'da bir güncellenir
- ✅ **Otomatik güncelleme** - Cron job ile
- ✅ **Web arayüzü** - Görsel takip
- ✅ **Manuel güncelleme** - Tek tıkla
- ✅ **Responsive tasarım** - Mobil uyumlu
- ✅ **Profesyonel görünüm** - Temiz ve sade
- ✅ **Hata yönetimi** - Güvenli işlem
- ✅ **Kolay kurulum** - 5 dakikada hazır

---

## 📦 Kurulum

### 1. Dosyaları İndirin

```bash
git clone https://github.com/berkocan/genelpara-api-cache.git
cd genelpara-api-cache
```

### 2. Sunucunuza Yükleyin

```bash
# FTP veya SSH ile yükleyin
/var/www/html/
├── cache-updater.php
└── index.php
```

### 3. İlk Cache'i Oluşturun

```bash
php cache-updater.php
```

Çıktı:
```
════════════════════════════════════════════════
  GenelPara API - Cache Updater
════════════════════════════════════════════════

Mevcut cache yok

API'den veri çekiliyor...

✓ doviz - OK
✓ kripto - OK
✓ altin - OK

✓ Cache başarıyla güncellendi!
Dosya: /var/www/html/api-cache.json
Boyut: 12.45 KB
Sonraki güncelleme: 15 dakika sonra
```

### 4. Cron Job Ekleyin

```bash
crontab -e
```

Aşağıdaki satırı ekleyin:

```bash
*/15 * * * * php /var/www/html/cache-updater.php >> /var/log/genelpara-cache.log 2>&1
```

### 5. Tarayıcıdan Açın

```
http://yoursite.com/index.php
```

---

## 🚀 Kullanım

### Web Arayüzü

Tarayıcıdan `index.php`'yi açın:

- **Cache durumu** - Güncel mi, ne zaman güncellenmiş
- **Veri tabloları** - Döviz, kripto, altın
- **Manuel güncelleme** - "Güncelle" butonu
- **Otomatik yenileme** - Sayfa yenile butonu

### Komut Satırı

```bash
# Cache güncelle
php cache-updater.php

# Log ile güncelle
php cache-updater.php >> /var/log/cache.log 2>&1
```

### PHP Kodu İçinde Kullanım

```php
<?php
// Cache'den veri oku
$cacheFile = __DIR__ . '/api-cache.json';
$cacheData = json_decode(file_get_contents($cacheFile), true);

// Döviz kurları
$doviz = $cacheData['data']['doviz']['data'];
echo "USD: " . $doviz['USD']['satis'];

// Kripto paralar
$kripto = $cacheData['data']['kripto']['data'];
echo "BTC: " . $kripto['BTC']['satis'];
?>
```

---

## ⚙️ Yapılandırma

### Cache Süresi

`cache-updater.php` dosyasında:

```php
define('CACHE_DURATION', 15 * 60); // 15 dakika (saniye)
```

**Önerilen değerler:**
- `15 * 60` - 15 dakika (önerilen)
- `30 * 60` - 30 dakika
- `60 * 60` - 1 saat

### Çekilecek Kategoriler

`cache-updater.php` dosyasında:

```php
$categories = [
    ['list' => 'doviz', 'sembol' => 'USD,EUR,GBP,JPY,CHF'],
    ['list' => 'kripto', 'sembol' => 'BTC,ETH,XRP,DOGE,LTC'],
    ['list' => 'altin', 'sembol' => 'GA,C,Y,T']
];
```

**Kullanılabilir kategoriler:**
- `doviz` - Döviz kurları
- `kripto` - Kripto paralar
- `altin` - Altın fiyatları
- `emtia` - Emtia fiyatları
- `hisse` - Hisse senetleri
- `endeks` - Endeksler

**Sembol örnekleri:**
- Döviz: `USD,EUR,GBP,JPY,CHF,CAD,AUD`
- Kripto: `BTC,ETH,XRP,DOGE,LTC,ADA`
- Altın: `GA,C,Y,T` (Gram, Çeyrek, Yarım, Tam)

### Cache Dosyası Konumu

```php
define('CACHE_FILE', __DIR__ . '/api-cache.json');
```

Farklı bir yere kaydetmek için:

```php
define('CACHE_FILE', '/var/cache/genelpara/api-cache.json');
```

---

## 🔧 Sorun Giderme

### Cache Dosyası Oluşmuyor

**Sorun:** `php cache-updater.php` çalıştırılıyor ama dosya oluşmuyor.

**Çözüm:**
```bash
# Dizin yazma izni kontrol et
ls -la /var/www/html/

# İzin ver
chmod 755 /var/www/html/
chmod 666 /var/www/html/api-cache.json
```

### Cron Job Çalışmıyor

**Sorun:** Cron job eklendi ama cache güncellenmiyor.

**Çözüm:**
```bash
# Cron servis durumu
sudo systemctl status cron

# Cron log kontrol
grep CRON /var/log/syslog

# Tam path kullan
*/15 * * * * /usr/bin/php /var/www/html/cache-updater.php
```

### API'den Veri Çekilemiyor

**Sorun:** Cache güncelleniyor ama veri yok.

**Çözüm:**
```bash
# curl test et
curl "https://api.genelpara.com/json/?list=doviz&sembol=USD"

# PHP'de curl etkin mi?
php -m | grep curl

# curl etkinleştir (Ubuntu)
sudo apt-get install php-curl
sudo service apache2 restart
```

### Cache Süresi Dolmuş Görünüyor

**Sorun:** Web arayüzünde "Süresi Dolmuş" uyarısı.

**Çözüm:**
```bash
# Cron çalışıyor mu?
crontab -l

# Log kontrol
tail -f /var/log/genelpara-cache.log

# Manuel güncelle
php cache-updater.php
```

---

## 📊 İzleme ve Log

### Log Dosyası Oluşturma

```bash
# Cron job'a log ekle
*/15 * * * * php /var/www/html/cache-updater.php >> /var/log/genelpara-cache.log 2>&1
```

### Log İnceleme

```bash
# Son 50 satır
tail -n 50 /var/log/genelpara-cache.log

# Canlı takip
tail -f /var/log/genelpara-cache.log

# Hataları bul
grep "Error" /var/log/genelpara-cache.log
```

### Basit Monitoring Script

```bash
#!/bin/bash
# check-cache.sh

CACHE_FILE="/var/www/html/api-cache.json"
MAX_AGE=1200  # 20 dakika

if [ ! -f "$CACHE_FILE" ]; then
    echo "HATA: Cache dosyası yok!"
    exit 1
fi

AGE=$(( $(date +%s) - $(stat -c %Y "$CACHE_FILE") ))

if [ $AGE -gt $MAX_AGE ]; then
    echo "UYARI: Cache çok eski! ($AGE saniye)"
    exit 1
else
    echo "OK: Cache güncel ($AGE saniye)"
    exit 0
fi
```

Cron job:
```bash
*/5 * * * * /var/www/html/check-cache.sh >> /var/log/cache-monitor.log 2>&1
```

---

## 📝 Sistem Gereksinimleri

- PHP 7.4 veya üzeri
- cURL extension
- JSON extension
- Cron job erişimi
- Yazma izinleri

---

## 🤝 Katkıda Bulunma

Katkılarınızı bekliyoruz! Lütfen şu adımları izleyin:

1. Bu repository'yi fork edin
2. Feature branch oluşturun (`git checkout -b feature/amazing-feature`)
3. Değişikliklerinizi commit edin (`git commit -m 'feat: Add amazing feature'`)
4. Branch'inizi push edin (`git push origin feature/amazing-feature`)
5. Pull Request açın

---

## 📄 Lisans

Bu proje MIT lisansı altında lisanslanmıştır. Detaylar için [LICENSE](LICENSE) dosyasına bakın.

---

## 📞 İletişim

**Berk Öcan**
- GitHub: [@berkocan](https://github.com/berkocan)
- Website: [genelpara.com](https://genelpara.com)
- API: [api.genelpara.com](https://api.genelpara.com)

---

## 🙏 Teşekkürler

- GenelPara API'yi sağladığı için GenelPara ekibine
- Katkıda bulunan tüm geliştiricilere

---

<div align="center">

**⭐ Bu projeyi faydalı bulduysanız yıldızlamayı unutmayın!**

Made with ❤️ by [Berk Öcan](https://github.com/berkocan)

</div>
