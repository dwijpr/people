<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Composer;
use DB;

class RSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rseed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate seed from Database';

    protected $composer;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Composer $composer)
    {
        parent::__construct();
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $db = config('database.connections.mysql.database');
        $ignores = config('database.connections.mysql.ignores');
        $skips = config('database.connections.mysql.skips');
        $query = "SHOW TABLES where Tables_in_$db not like '%migrations'";
        foreach ($ignores as $i => $prefix) {
            $query .= " AND Tables_in_$db not like '{$prefix}%'";
        }
        $tables = DB::select($query);
        $this->info('tables list:');
        $items = [];
        foreach ($tables as $i => $table) {
            $name = $table->{'Tables_in_'.$db};
            $this->line("- ".$name);
            if (in_array($name, $skips)) {
                $this->line("!! skipping $name table");
            } else {
                $items[] = $name;
            }
        }
        $this->call('iseed', [
            "tables" => implode(",", $items),
            "--force" => true,
        ]);
        $this->composer->dumpAutoLoads();
    }
}
