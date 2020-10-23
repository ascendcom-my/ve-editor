<?php

namespace Bigmom\VeEditor\Commands;

use Bigmom\VeEditor\Facades\Asset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class PullVeEditor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 've:pull';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pull from VE Editor.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $response = Http::withBasicAuth(config('ve.api_username'), config('ve.api_password'))->get(config('ve.pull_url'));

        $result = collect($response->json());

        $success = Asset::pull($result);
        
        $success ? 
            $this->info('Success.')
            : $this->info('Error.');
    }
}
