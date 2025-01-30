<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Http\Controllers\PredController;

class PredGetFolOffCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'pred:getFolOff';

    /**
     * The console command description.
     */
    protected $description = 'Memanggil method getFolOff() di PredController';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Panggil controller
        $controller = new PredController();

        // Kita buat Request kosong atau disesuaikan
        $request = new Request();

        // Panggil method
        $controller->getFolOff($request);

        // Tampilkan pesan di console (opsional)
        $this->info('Method getFolOff() berhasil dieksekusi.');
    }
}
