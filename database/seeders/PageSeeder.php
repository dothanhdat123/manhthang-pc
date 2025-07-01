<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getData() as $page) {
            try {
                Page::updateOrCreate([
                    'id' => $page['id']
                ], array_merge($page, [
                    'seo_title' => str_replace('|', '-', hwa_page_title($page['name'])),
                    'seo_description' => $page['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
            }
        }
    }

    /**
     * @return \string[][]
     */
    protected function getData()
    {
        return [
            [
                'id' => 1,
                'name' => 'Giới thiệu',
                'slug' => 'gioi-thieu',
                'content' => 'Trang giới thiệu.'
            ],
            [
                'id' => 2,
                'name' => 'Chính sách và điều khoản',
                'slug' => 'chinh-sach-va-dieu-khoản',
                'content' => 'Trang Chính sách và điều khoản.'
            ],
            [
                'id' => 3,
                'name' => 'Chính sách giao hàng',
                'slug' => 'chinh-sach-giao-hang',
                'content' => 'Trang Chính sách giao hàng.'
            ],
            [
                'id' => 4,
                'name' => 'Chính sách đổi trả',
                'slug' => 'chinh-sach-doi-tra',
                'content' => 'Trang Chính sách đổi trả.'
            ],
        ];
    }
}
