<?php
require_once 'models/BaseModel.php';

class DataImporter extends BaseModel {
    private function getUtf8Content($filePath) {
        $content = file_get_contents($filePath);
        if (substr($content, 0, 2) === "\xff\xfe" || substr($content, 0, 2) === "\xfe\xff") {
            // Convert UTF-16 to UTF-8
            return mb_convert_encoding($content, 'UTF-8', 'UTF-16');
        }
        return $content;
    }

    public function importProvinces($filePath) {
        if (!file_exists($filePath)) return "File not found: $filePath";
        
        $content = $this->getUtf8Content($filePath);
        // Regex for Provinces: VALUES (id, N'code', N'name', N'short', N'code', N'type', country, ...
        preg_match_all("/VALUES\s*\(\d+,\s*N'([^']*)',\s*N'([^']*)',\s*N'([^']*)',\s*N'([^']*)',\s*N'([^']*)',\s*(\d+)/u", $content, $matches, PREG_SET_ORDER);
        
        $count = 0;
        foreach ($matches as $m) {
            $stmt = $this->connect->prepare("INSERT IGNORE INTO provinces (province_code, name, short_name, code, place_type, country_id) VALUES (?, ?, ?, ?, ?, ?)");
            $p_code = trim($m[1]);
            $name = preg_replace('/\s+/', ' ', trim($m[2]));
            $short = preg_replace('/\s+/', ' ', trim($m[3]));
            $code = trim($m[4]);
            $type = trim($m[5]);
            $country = (int)$m[6];
            
            $stmt->bind_param("sssssi", $p_code, $name, $short, $code, $type, $country);
            if ($stmt->execute()) $count++;
        }
        return "Imported $count provinces.";
    }

    public function importWards($filePath) {
        if (!file_exists($filePath)) return "File not found: $filePath";
        
        $content = $this->getUtf8Content($filePath);
        // VALUES (id, N'ward_code', N'name', N'prov_code', ...
        preg_match_all("/VALUES\s*\(\d+,\s*N'([^']*)',\s*N'([^']*)',\s*N'([^']*)'/u", $content, $matches, PREG_SET_ORDER);
        
        $count = 0;
        foreach ($matches as $m) {
            $stmt = $this->connect->prepare("INSERT IGNORE INTO wards (ward_code, name, province_code) VALUES (?, ?, ?)");
            $w_code = trim($m[1]);
            $name = preg_replace('/\s+/', ' ', trim($m[2]));
            $p_code = trim($m[3]);
            
            $stmt->bind_param("sss", $w_code, $name, $p_code);
            if ($stmt->execute()) $count++;
        }
        return "Imported $count wards.";
    }
}

$importer = new DataImporter();
echo $importer->importProvinces('script.sql') . "\n";
echo $importer->importWards('sct.sql') . "\n";
