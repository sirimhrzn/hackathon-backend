<?php

namespace Database\Seeders;

use App\Models\Categories;
use App\Models\Location;
use App\Models\PaymentMethods;
use App\Models\ProductDetails;
use App\Models\Products;
use App\Models\Stores;
use App\Models\User;
use App\Models\VendorConfigs;
use App\Models\Vendors;
use App\Models\VendorUsers;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        Vendors::create([
            'name' => 'Test Vendor',
            'super_user' => 1
        ]);
        VendorUsers::create([
            'user_id' => 1,
            'vendor_id' => 1
        ]);
        Stores::create([
            'vendor_id' => 1,
            'name' => 'Test Store'
        ]);
        Categories::create([
            'vendor_id' => 1,
            'name' => 'Electronics',
            'active' => 'y',
            'parent' => null
        ]);
        Categories::create([
            'vendor_id' => 1,
            'name' => 'Mobile',
            'active' => 'y',
            'parent' => 1
        ]);
        Products::create([
            'name' => 'Waves Heavyweight Hoodie',
            'enabled' => 'y',
            'price' => 2400,
            'category_id' => 1,
            'vendor_id' => 1,
            'added_by' => 1
        ]);

        Products::create([
            'name' => 'SDMN x Hot Wheels Washed Hoodie',
            'enabled' => 'y',
            'price' => 2400,
            'category_id' => 1,
            'vendor_id' => 1,
            'added_by' => 1
        ]);

        Products::create([
            'name' => 'SDMN x Hot Wheels Racing Jacket',
            'enabled' => 'y',
            'price' => 2400,
            'category_id' => 1,
            'vendor_id' => 1,
            'added_by' => 1
        ]);
        ProductDetails::create([
            'vendor_id' => 1,
            'product_id' => 1,
            'details' => json_encode([
                'tax_amount' => 100,
                'product_service_charge' => 20,
           ]),
            'metadata' => json_encode(['description' => 'Waves Heavyweight Hoodie',
                'types' => [
                    [
                        'size' => 'L',
                        'price' =>  1000,
                        'stock' => 4
                    ],
                    [
                        'size' => 'M',
                        'price' =>  1000,
                        'stock' => 40
                    ]
                ],
                'images' => [
                    "1/product_image/pw.jpg"
                ]

            ])
        ]);
        ProductDetails::create([
            'vendor_id' => 1,
            'product_id' => 2,
            'details' => json_encode([
                'tax_amount' => 100,
                'product_service_charge' => 20,
           ]),
            'metadata' => json_encode(['description' => 'SDMN x Hot Wheels Washed Hoodie',
                'types' => [
                    [
                        'size' => 'L',
                        'price' =>  1000,
                        'stock' => 4
                    ],
                    [
                        'size' => 'M',
                        'price' =>  1000,
                        'stock' => 40
                    ]
                ],
                'images' => [
                    "1/product_image/11.jpg"
                ]

            ])
        ]);

        ProductDetails::create([
            'vendor_id' => 1,
            'product_id' => 3,
            'details' => json_encode([
                'tax_amount' => 100,
                'product_service_charge' => 20,
           ]),
            'metadata' => json_encode(['description' => 'SDMN x Hot Wheels Racing Jacket',
                'types' => [
                    [
                        'size' => 'L',
                        'price' =>  1000,
                        'stock' => 4
                    ],
                    [
                        'size' => 'M',
                        'price' =>  1000,
                        'stock' => 40
                    ]
                ],
                'images' => [
                    "1/product_image/12.jpg"
                ]

            ])
        ]);

        $payment_methods = [
            // ['name' => 'Esewa' , 'enabled' => 'y'],
            ['name' => 'Khalti', 'enabled' => 'y']
        ];
        PaymentMethods::insert($payment_methods);
        VendorConfigs::insert([
            'vendor_id' => 1,
            'config' => json_encode([
                'payment_options' => [
                    [
                        'id' => 1,
                        'enabled' => 'y'
                    ]
                ]
            ])
        ]);
        Location::insert([
                ["name" => "Kathmandu"],
                ["name" => "Pokhara"],
                ["name" => "Lalitpur"],
                ["name" => "Biratnagar"],
                ["name" => "Birgunj"],
                ["name" => "Bharatpur"],
                ["name" => "Hetauda"],
                ["name" => "Janakpur"],
                ["name" => "Dharan"],
                ["name" => "Butwal"]
        ]);
    }
}
