<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('countries')->count() > 0) {
            $this->command->info('Countries table already has data. Skipping...');
            return;
        }

        $this->importSqlFile('countries.sql');
    }

    protected function importSqlFile(string $filename): void
    {
        $path = database_path('data/' . $filename);
        if (!file_exists($path)) {
            throw new \RuntimeException("SQL file not found at: {$path}");
        }

        $this->command->info("Importing {$filename}...");

        ini_set('memory_limit', '-1');
        set_time_limit(0);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        $sql = file_get_contents($path);
        $statements = preg_split('/;\s*[\r\n]+/', $sql);

        foreach ($statements as $statement) {
            $statement = trim($statement);
            if ($statement === '') {
                continue;
            }
            if (stripos($statement, 'SET ') === 0) {
                continue;
            }
            DB::unprepared($statement);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info("Successfully imported {$filename}.");
    }
}
