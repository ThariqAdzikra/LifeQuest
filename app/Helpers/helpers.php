<?php

/**
 * File helper global.
 * File ini akan di-autoload oleh Composer.
 */

if (!function_exists('getStatIcon')) {
    /**
     * Mendapatkan kelas ikon Bootstrap berdasarkan nama stat.
     *
     * @param string $statName Nama stat (e.g., 'intelligence')
     * @return string Kelas ikon (e.g., 'bi bi-brain')
     */
    function getStatIcon($statName)
    {
        $icons = [
            'intelligence' => 'bi bi-brain',
            'strength'     => 'bi bi-person-arms-up',
            'stamina'      => 'bi bi-lightning-charge-fill',
            'agility'      => 'bi bi-wind',
        ];

        // Jika tidak ada di map, berikan ikon default
        return $icons[$statName] ?? 'bi bi-gem';
    }
}

// Anda bisa menambahkan fungsi helper lain di sini di masa depan...