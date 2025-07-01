<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\HwaRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $user = User::updateOrCreate([
                'email' => 'admin@' . hwa_app_domain(),
            ], [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'full_name' => 'Super Admin',
                'username' => 'admin',
                'email' => 'admin@' . hwa_app_domain(),
                'email_verified_at' => now(),
                'password' => bcrypt('admin123'),
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info($user);

            if ($role = HwaRole::whereName('super_admin')->first()) {
                Log::info($role);
                $user->syncRoles([$role['id']]);
            }

            $customer = Customer::updateOrCreate([
                'email' => 'customer@' . hwa_app_domain(),
            ],[
                'name' => 'Customer',
                'username' => 'customer',
                'email' => 'customer@' . hwa_app_domain(),
                'password' => bcrypt('admin123')
            ]);

            if ($customer) {
                CustomerAddress::updateOrCreate([
                    'id' => 1
                ],[
                    'customer_id' => $customer->id,
                    'name' => 'Customer',
                    'phone' => '0989324221',
                    'address' => 'My Address',
                    'is_default' => 1,
                ]);
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}
