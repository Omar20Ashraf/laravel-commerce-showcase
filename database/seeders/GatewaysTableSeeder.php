<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Gateway;
use App\Models\Module;
use Illuminate\Database\Seeder;

class GatewaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gateways = [
            [
                'name' => 'Clickpay',
                'type' => 'clickpay',
                'is_active' => true,
            ],
            [
                'name' => 'Moyasar',
                'type' => 'moyasar',
                'is_active' => true,
            ],
            [
                'name' => 'PayTabs',
                'type' => 'pay_tabs',
                'is_active' => true,
            ],
        ];

        for ($i = 0; $i < \count($gateways); $i++) {
            $gateway = $gateways[$i];

            $gatewayExists = Gateway::where('type', $gateway['type'])->exists();

            if (!$gatewayExists) {
                $gateway = Gateway::create([
                    'name' => $gateway['name'],
                    'type' => $gateway['type'],
                    'is_active' => $gateway['is_active'],
                ]);

                $cityIds = City::available()->pluck('id')->toArray();
                $gateway->cities()->attach($cityIds);

                $moduleIds = Module::available()->pluck('id')->toArray();
                $gateway->modules()->attach($moduleIds);
            }
        }
    }
}
