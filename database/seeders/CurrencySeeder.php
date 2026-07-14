<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = database_path('data/currencies.json');

        if (!File::exists($filePath)) {
            $this->command->error("Currencies JSON data file not found at: {$filePath}");
            return;
        }

        $json = File::get($filePath);
        $currencies = json_decode($json, true);

        if (!is_array($currencies)) {
            $this->command->error("Invalid JSON format in currencies data file.");
            return;
        }

        $this->command->info("Seeding " . count($currencies) . " currencies...");

        foreach ($currencies as $item) {
            $code = strtoupper($item['cc'] ?? '');
            $name = $item['name'] ?? '';
            $symbol = $item['symbol'] ?? '';

            if (empty($code) || empty($name)) {
                continue;
            }

            // Clean up symbol if it has trailing semi-colons or unicode artifacts
            $symbol = rtrim($symbol, ';');

            // Default USD as base currency, otherwise first record if USD not found
            $isDefault = ($code === 'USD');

            Currency::updateOrCreate(
                ['code' => $code],
                [
                    'name' => $name,
                    'symbol' => $symbol ?: $code,
                    'exchange_rate' => $isDefault ? 1.000000 : 1.000000,
                    'is_default' => $isDefault,
                    'status' => true,
                ]
            );
        }

        // If no USD was seeded to set as default, set the first active currency as default
        if (Currency::where('is_default', true)->count() === 0) {
            $first = Currency::first();
            if ($first) {
                $first->update(['is_default' => true, 'exchange_rate' => 1.000000]);
            }
        }

        $this->command->info("Currencies seeded successfully!");
    }
}
