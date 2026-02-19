<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'CrownTime Distributors',
                'contact' => '+44 20 7123 8890',
            ],
            [
                'name' => 'Precision Watch Supply Co.',
                'contact' => '+44 161 555 2178',
            ],
            [
                'name' => 'Elite Timepiece Imports',
                'contact' => '+44 28 9045 3321',
            ],
            [
                'name' => 'Horology Partners UK',
                'contact' => '+44 121 798 4411',
            ],
            [
                'name' => 'Global Watch Components Ltd.',
                'contact' => '+44 113 224 9988',
            ],
        ];

        foreach ($suppliers as $data) {
            Supplier::firstOrCreate([
                'name' => $data['name'],
                'contact' => $data['contact'],
            ]);
        }
    }
}
