<?php

$flags = [
    'en' => 'https://flagcdn.com/w80/gb.png',
    'fr' => 'https://flagcdn.com/w80/fr.png',
    'de' => 'https://flagcdn.com/w80/de.png',
    'es' => 'https://flagcdn.com/w80/es.png',
    'ja' => 'https://flagcdn.com/w80/jp.png',
];

$flagDir = __DIR__ . '/public/images/flags';

if (!file_exists($flagDir)) {
    mkdir($flagDir, 0777, true);
}

foreach ($flags as $code => $url) {
    $flagPath = $flagDir . '/' . $code . '.png';
    if (!file_exists($flagPath)) {
        $ch = curl_init($url);
        $fp = fopen($flagPath, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        echo "Downloaded flag for $code\n";
    }
}

echo "All flags downloaded successfully!\n"; 