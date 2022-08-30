<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\OffersParseService;
use Exception;

class ParseXML extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:offers {file?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command parses offers from XML file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(OffersParseService $offersParseService)
    {
        $this->info('Start parse');

        $file = $this->argument('file');

        try {
            $offersParseService->parse($file);
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        }

        $this->info('Success finish parse');
    }
}
