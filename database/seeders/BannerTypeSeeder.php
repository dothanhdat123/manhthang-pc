<?php

namespace Database\Seeders;

use App\Models\BannerType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class BannerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bannerTypes = [
            [
                'id' => 1,
                'code' => 'banner',
                'name' => 'Banner chính',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'code' => 'sub-banner',
                'name' => 'Banner phụ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'code' => 'banner-store',
                'name' => 'Banner cửa hàng',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'code' => 'banner-blog',
                'name' => 'Banner tin tức',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'code' => 'banner-background',
                'name' => 'Banner nền',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($bannerTypes as $bannerType) {
            try {
                BannerType::updateOrCreate([
                    'id' => $bannerType['id']
                ], $bannerType);
            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
            }
        }
    }
}
