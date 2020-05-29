<?php

return [
    'app_key_is_set' => [
        'message' => 'Kunci aplikasi belum disetel. Panggil "php artisan key:generate" untuk membuat dan mengatur kunci',
        'name' => 'Kunci aplikasi sudah diatur',
    ],
    'composer_with_dev_dependencies_is_up_to_date' => [
        'message' => 'Dependency composer Anda tidak terbaru. Panggil "composer install" untuk memperbaruinya.
        :more',
        'name' => 'Dependency composer (termasuk dev) sudah terbaru',
    ],
    'composer_without_dev_dependencies_is_up_to_date' => [
        'message' => 'Dependency composer Anda tidak terbaru. Panggil "composer install" untuk memperbaruinya.
        :more',
        'name' => 'Dependency composer (tidak termasuk dev) sudah terbaru',
    ],
    'configuration_is_cached' => [
        'message' => 'Konfigurasi Anda harus di-cache dalam mode production untuk kinerja yang lebih baik. Panggil "php artisan config:cache" untuk membuat cache konfigurasi.',
        'name' => 'Konfigurasi sudah di-cache',
    ],
    'configuration_is_not_cached' => [
        'message' => 'Konfigurasi Anda tidak harus di-cache dalam mode development. Panggil "php artisan config:clear" untuk menghapus cache konfigurasi.',
        'name' => 'Konfigurasi belum di-cache',
    ],
    'correct_php_version_is_installed' => [
        'message' => 'Anda tidak memiliki versi PHP yang diperlukan untuk diinstal.' . PHP_EOL . 'Yang dibutuhkan:
        :required' . PHP_EOL . 'Yang dipakai: :used',
        'name' => 'Versi PHP benar yang diinstall',
    ],
    'database_can_be_accessed' => [
        'message' => 'Database tidak dapat diakses: :error',
        'name' => 'Database dapat diakses',
    ],
    'debug_mode_is_not_enabled' => [
        'message' => 'Anda seharusnya tidak menggunakan mode debug dalam mode production. Setel "APP_DEBUG" di file .env ke "false".',
        'name' => 'Mode debug tidak diaktifkan',
    ],
    'directories_have_correct_permissions' => [
        'message' => 'Direktori berikut tidak dapat ditulisi:' . PHP_EOL .':directories',
        'name' => 'Semua direktori memiliki izin yang benar',
    ],
    'env_file_exists' => [
        'message' => 'File .env tidak ada. Harap salin file .env.example sebagai .env dan sesuaikan sesuai kebutuhan.',
        'name' => 'File environment ada',
    ],
    'example_environment_variables_are_set' => [
        'message' => 'Variabel environment ini tidak ada dalam file .env Anda, tetapi didefinisikan dalam .env.example:'. PHP_EOL. ': variables',
        'name' => 'Contoh environment sudah ditetapkan',
    ],
    'example_environment_variables_are_up_to_date' => [
        'message' => 'Variabel environment ini didefinisikan dalam file .env, tetapi tidak ada dalam .env.example Anda: '. PHP_EOL. ': variables',
        'name' => 'Contoh environment sudah terbaru',
    ],
    'horizon_is_running' => [
        'message' => [
            'not_running' => 'Horizon tidak berjalan.',
            'unable_to_check' => 'Tidak dapat memeriksa horizon: :reason',
        ],
        'name' => 'Horizon sudah berjalan',
    ],
    'locales_are_installed' => [
        'message' => [
            'cannot_run_on_windows' => 'Pemeriksaan ini tidak dapat dijalankan di Windows.',
            'locale_command_not_available' => 'Perintah "locale -a" tidak tersedia di OS ini.',
            'missing_locales' => 'Terjemahan berikut tidak ada:' . PHP_EOL . ':locales',
            'shell_exec_not_available' => 'Fungsi "shell_exec" tidak didefinisikan atau dinonaktifkan, jadi kami tidak dapat memeriksa terjemahan.',
        ],
        'name' => 'Terjemahan yang diperlukan sudah diinstal',
    ],
    'maintenance_mode_not_enabled' => [
        'message' => 'Mode maintenance masih diaktifkan. Nonaktifkan dengan "php artisan up".',
        'name' => 'Mode maintenance tidak diaktifkan',
    ],
    'migrations_are_up_to_date' => [
        'message' => [
            'need_to_migrate' => 'Anda perlu memperbarui database Anda. Panggil "php artisan migrate" untuk
            menjalankan migrasi',
            'unable_to_check' => 'Tidak dapat memeriksa migrasi: :reason',
        ],
        'name' => 'Migrasi sudah terbaru',
    ],
    'php_extensions_are_disabled' => [
        'message' => 'Ekstensi berikut sudah aktif:' . PHP_EOL . ':extensions',
        'name' => 'Ekstensi PHP yang tidak diinginkan dinonaktifkan',
    ],
    'php_extensions_are_installed' => [
        'message' => 'Ekstensi berikut tidak ada:' . PHP_EOL . ':extensions',
        'name' => 'Ekstensi PHP yang diperlukan telah diinstal',
    ],
    'redis_can_be_accessed' => [
        'message' => [
            'not_accessible' => 'Cache Redis tidak dapat diakses: :error',
            'default_cache' => 'Cache default tidak dapat dijangkau.',
            'named_cache' => 'Cache bernama :name tidak dapat dijangkau.',
        ],
        'name' => 'Cache Redis dapat diakses',
    ],
    'routes_are_cached' => [
        'message' => 'Route Anda harus di-cache dalam mode production untuk kinerja yang lebih baik. Panggil "php artisan route:cache" untuk membuat cache route.',
        'name' => 'Route sudah di-cache',
    ],
    'routes_are_not_cached' => [
        'message' => 'Route Anda tidak harus di-cache dalam mode development. Panggil "php artisan route:clear" untuk menghapus cache route.',
        'name' => 'Routes belum di-cache',
    ],
    'servers_are_pingable' => [
        'message' => "Server ':host' (port: :port) tidak dapat dijangkau (batas waktu habis :timeout detik).",
        'name' => 'Dibutuhkan server yang bisa ping',
    ],
    'storage_directory_is_linked' => [
        'message' => 'Direktori penyimpanan tidak ditautkan. Gunakan "php artisan storage:link" untuk membuat tautan simbolis.',
        'name' => 'Direktori penyimpanan terhubung',
    ],
    'supervisor_programs_are_running' => [
        'message' => [
            'cannot_run_on_windows' => 'Pemeriksaan ini tidak dapat dijalankan di Windows.',
            'not_running_programs' => 'Program-program berikut ini tidak berjalan atau membutuhkan restart:' . PHP_EOL . ':programs',
            'shell_exec_not_available' => 'Fungsi "shell_exec" tidak didefinisikan atau dinonaktifkan, jadi kami tidak dapat memeriksa program yang sedang berjalan.',
            'supervisor_command_not_available' => 'Perintah "supervisorctl" tidak tersedia di OS ini.',
        ],
        'name' => 'Semua supervisor progam sudah berjalan',
    ],
];
