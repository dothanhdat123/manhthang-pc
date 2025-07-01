<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = hwaCore()->getSettings();
        foreach ($settings as $key => $value) {
            try {
                Setting::updateOrCreate([
                    'key' => $key
                ], [
                    'key' => $key,
                    'value' => $value
                ]);
            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
            }
        }
    }
}
