<?php

namespace App\Support;

class IndonesiaCities
{
    /**
     * Daftar 514 kota/kabupaten dari snapshot API Wilayah Indonesia (lihat resources/data/indonesia-cities.json).
     * Simpan lokal agar form tetap dapat digunakan tanpa koneksi internet.
     */
    public static function options(): array
    {
        $data = json_decode(file_get_contents(resource_path('data/indonesia-cities.json')), true, 512, JSON_THROW_ON_ERROR);

        return array_map(fn (array $region) => [
            'id' => $region['id'],
            'value' => $region['name'],
            'label' => $region['name'],
        ], $data['regions']);
    }

    public static function names(): array
    {
        return array_values(array_unique(array_column(self::options(), 'value')));
    }
}
