<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\PressController; // Ganti sesuai nama Controller Anda

class UpdateUniqueDatesCommand extends Command
{
    /**
     * The name and signature of the console command.
     * Misalnya: php artisan update:unique-dates
     */
    protected $signature = 'update:unique-dates';

    /**
     * The console command description.
     */
    protected $description = 'Menjalankan method updateUniqueDates() dari controller tertentu';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Panggil controller
        $controller = new PressController(); // ganti "YourController" sesuai controller Anda

        // Jalankan method
        $result = $controller->updateUniqueDates();

        // Opsional: tampilkan info di console
        $this->info('updateUniqueDates() berhasil dijalankan.');
    }
}
