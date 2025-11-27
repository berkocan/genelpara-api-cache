<?php
/**
 * GenelPara API - Cache Viewer
 * 
 * Cache'lenmiş verileri görüntüler.
 * Web arayüzü ile erişim.
 */

// Cache dosyası
define('CACHE_FILE', __DIR__ . '/genelpara-cache.json');
define('CACHE_DURATION', 15 * 60);

/**
 * Cache'den veri oku
 */
function getCachedData() {
    if (!file_exists(CACHE_FILE)) {
        return null;
    }
    
    $cacheData = json_decode(file_get_contents(CACHE_FILE), true);
    if (!$cacheData) {
        return null;
    }
    
    return $cacheData;
}

/**
 * Cache bilgisini al
 */
function getCacheInfo() {
    if (!file_exists(CACHE_FILE)) {
        return ['exists' => false];
    }
    
    $cacheData = json_decode(file_get_contents(CACHE_FILE), true);
    if (!$cacheData) {
        return ['exists' => false];
    }
    
    $age = time() - $cacheData['updated_at'];
    $remaining = max(0, CACHE_DURATION - $age);
    
    return [
        'exists' => true,
        'updated_at' => $cacheData['updated_at'],
        'age' => $age,
        'age_minutes' => floor($age / 60),
        'remaining' => $remaining,
        'remaining_minutes' => floor($remaining / 60),
        'is_fresh' => $age < CACHE_DURATION
    ];
}

$cachedData = getCachedData();
$cacheInfo = getCacheInfo();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GenelPara API - Cache System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        header {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        h1 {
            font-size: 1.8rem;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        .subtitle {
            color: #7f8c8d;
            font-size: 0.95rem;
        }
        .cache-info {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            border-left: 4px solid #3498db;
        }
        .cache-info table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .cache-info td {
            padding: 8px 0;
            border-bottom: 1px solid #ecf0f1;
        }
        .cache-info td:first-child {
            color: #7f8c8d;
            width: 180px;
        }
        .cache-info td:last-child {
            font-weight: 500;
        }
        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .status.active { background: #d4edda; color: #155724; }
        .status.warning { background: #fff3cd; color: #856404; }
        .status.error { background: #f8d7da; color: #721c24; }
        .btn {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9rem;
            margin-right: 10px;
            transition: background 0.2s;
        }
        .btn:hover { background: #2980b9; }
        .btn-secondary {
            background: #95a5a6;
        }
        .btn-secondary:hover { background: #7f8c8d; }
        .section {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .section h2 {
            font-size: 1.3rem;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ecf0f1;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.data-table th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #2c3e50;
            border-bottom: 2px solid #dee2e6;
            font-size: 0.9rem;
        }
        table.data-table td {
            padding: 12px;
            border-bottom: 1px solid #ecf0f1;
        }
        table.data-table tr:hover {
            background: #f8f9fa;
        }
        .symbol {
            font-weight: 600;
            font-size: 1rem;
        }
        .price {
            font-family: 'Courier New', monospace;
            font-size: 0.95rem;
        }
        .change {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .change.up { background: #d4edda; color: #155724; }
        .change.down { background: #f8d7da; color: #721c24; }
        .change.neutral { background: #e2e3e5; color: #383d41; }
        .alert {
            padding: 20px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #f5c6cb;
        }
        .footer {
            margin-top: 40px;
            padding: 25px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            font-size: 0.9rem;
            color: #7f8c8d;
        }
        .footer h3 {
            font-size: 1rem;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .footer code {
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 0.85rem;
            color: #e74c3c;
        }
        @media (max-width: 768px) {
            .container { padding: 20px 10px; }
            header { padding: 20px; }
            h1 { font-size: 1.5rem; }
            table.data-table { font-size: 0.85rem; }
            table.data-table th, table.data-table td { padding: 8px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>GenelPara API - Cache System</h1>
            <p class="subtitle">Verimli API kullanımı için önbellekleme sistemi</p>
        </header>
        
        <div class="cache-info">
            <h3 style="margin-bottom: 10px; color: #2c3e50;">Cache Durumu</h3>
            <table>
                <?php if ($cacheInfo['exists']): ?>
                    <tr>
                        <td>Durum:</td>
                        <td>
                            <?php if ($cacheInfo['is_fresh']): ?>
                                <span class="status active">✓ Aktif</span>
                            <?php else: ?>
                                <span class="status warning">⚠ Süresi Dolmuş</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Son Güncelleme:</td>
                        <td><?php echo date('d.m.Y H:i:s', $cacheInfo['updated_at']); ?></td>
                    </tr>
                    <tr>
                        <td>Cache Yaşı:</td>
                        <td><?php echo $cacheInfo['age_minutes']; ?> dakika</td>
                    </tr>
                    <tr>
                        <td>Sonraki Güncelleme:</td>
                        <td><?php echo $cacheInfo['remaining_minutes']; ?> dakika sonra</td>
                    </tr>
                    <tr>
                        <td>Dosya Boyutu:</td>
                        <td><?php echo number_format(filesize(CACHE_FILE) / 1024, 2); ?> KB</td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td>Durum:</td>
                        <td><span class="status error">✗ Cache Yok</span></td>
                    </tr>
                <?php endif; ?>
            </table>
            <div style="margin-top: 20px;">
                <a href="cache-updater.php" target="_blank" class="btn">Güncelle</a>
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-secondary">Yenile</a>
            </div>
        </div>

        <?php if ($cachedData && !empty($cachedData['data'])): ?>
            <?php foreach ($cachedData['data'] as $category => $apiData): ?>
                <div class="section">
                    <h2><?php echo strtoupper($category); ?></h2>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Sembol</th>
                                <th>Alış</th>
                                <th>Satış</th>
                                <th>Değişim</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($apiData['data'] as $symbol => $info): ?>
                                <?php
                                    $direction = 'neutral';
                                    if ($info['yon'] == 'moneyUp') $direction = 'up';
                                    if ($info['yon'] == 'moneyDown') $direction = 'down';
                                ?>
                                <tr>
                                    <td><span class="symbol"><?php echo htmlspecialchars($symbol); ?></span></td>
                                    <td class="price"><?php echo number_format((float)$info['alis'], 4, '.', ','); ?></td>
                                    <td class="price"><?php echo number_format((float)$info['satis'], 4, '.', ','); ?></td>
                                    <td>
                                        <span class="change <?php echo $direction; ?>">
                                            <?php echo htmlspecialchars($info['degisim']); ?> 
                                            (<?php echo htmlspecialchars($info['oran']); ?>%)
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-error">
                <strong>Cache Boş</strong><br><br>
                Cache dosyası oluşturulmamış. Lütfen cache'i güncelleyin.<br><br>
                <strong>Komut satırı:</strong> <code>php cache-updater.php</code>
            </div>
        <?php endif; ?>

        <div class="footer">
            <h3>Kurulum</h3>
            <p><strong>Cron Job:</strong> <code>*/15 * * * * php <?php echo __DIR__; ?>/cache-updater.php</code></p>
            <p style="margin-top: 15px;"><strong>Avantajlar:</strong> Günlük 96 istek | Sınırsız sayfa yükleme | API limiti aşma riski yok</p>
        </div>
    </div>
</body>
</html>