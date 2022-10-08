<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Scan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'memedb:scan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $path = config('memes.path');
        $files = scandir($path);
        foreach($files as $file) {
            $fullPath = "{$path}/{$file}";
            if(is_dir($fullPath)) {
                //TODO: recurse
            } else {
                $md5 = md5_file($fullPath);
                $s=microtime(true);
                dd($md5);
                $e=microtime(false);
            }
        }
        return Command::SUCCESS;
    }
}
