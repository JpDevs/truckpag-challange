<?php

namespace App\Services;

class ImportService
{
    public function getProducts($endpoint)
    {
        $handle = gzopen($endpoint, 'r');

        $lines = [];
        $data = [];

        for ($i = 0; $i < 100; $i++) {
            if (!gzeof($handle)) {
                $lines[] = gzgets($handle);
            }
        }
        gzclose($handle);

        foreach ($lines as $line) {
            $data[] = json_decode($line, true);
        }

        return $data;
    }
}
