<?php
define('CACHE_FILE', __DIR__ . '/genelpara-cache.json');
define('CACHE_DURATION', 15 * 60); // 15 dakika (saniye cinsinden)
define('API_URL', 'https://api.genelpara.com/json/');

// Çekilecek kategoriler ve semboller
$CATEGORIES = [
    ['list' => 'doviz', 'sembol' => 'USD,EUR,GBP,JPY,CHF'],
    ['list' => 'kripto', 'sembol' => 'BTC,ETH,XRP,DOGE,LTC'],
    ['list' => 'altin', 'sembol' => 'GA,C,Y,T']
];

// ═══════════════════════════════════════════════════════════════════════════
// FONKSİYONLAR
// ═══════════════════════════════════════════════════════════════════════════

/**
 * API'den veri çek ve cache'e yaz
 */
function updateCache() {
    global $CATEGORIES;
    
    $allData = [];
    
    foreach ($CATEGORIES as $category) {
        $params = http_build_query($category);
        $url = API_URL . '?' . $params;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            error_log('GenelPara API cURL Error: ' . curl_error($ch));
            curl_close($ch);
            continue;
        }
        
        curl_close($ch);
        
        if ($httpCode == 200) {
            $data = json_decode($response, true);
            if ($data && $data['success']) {
                $allData[$category['list']] = $data;
            }
        }
        
        // API'ye çok hızlı istek atmamak için bekle
        sleep(1);
    }
    
    // Cache dosyasına yaz
    $cacheData = [
        'updated_at' => time(),
        'data' => $allData
    ];
    
    file_put_contents(CACHE_FILE, json_encode($cacheData, JSON_PRETTY_PRINT));
    
    return true;
}
updateCache();
?>