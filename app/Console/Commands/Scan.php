<?php

namespace App\Console\Commands;
use App\Models\Meme;
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
                $s=microtime(true);
                $md5 = md5_file($fullPath);
                if(Meme::where('md5', $md5)->exists()) {
                    continue;
                }
                $text = [];
                exec("tesseract {$fullPath} stdout quiet", $text);
                $text = array_filter($text);
                $text = join(" ", $text);
                $text = str_replace('|', 'I', $text);
                $e=microtime(true);
                Meme::unguard();
                $meme = Meme::create([
                    'md5' => $md5,
                    'path' => $fullPath,
                    'text' => $text,
                    'scan_duration' => $e - $s
                ]);
            }
        }
        return Command::SUCCESS;
    }
}
