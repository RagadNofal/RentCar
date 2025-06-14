<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $cars = [
            [
                'brand' => 'Toyota',
                'model' => 'Camry',
                'engine' => '2.5L',
                'price_per_day' => 50,
                'image' => '/images/cars/Toyota_Camry.jpg',
                'quantity' => 1,
                'status' => 'Available',
               
                'stars' => 5,
                'category' => 'Sedan',
            ],
            [
                'brand' => 'Honda',
                'model' => 'Civic',
                'engine' => '1.8L',
                'price_per_day' => 45,
                'image' => '/images/cars/Honda_Civic.jpg',
                'quantity' => 1,
                'status' => 'Available',
                
                'stars' => 5,
                'category' => 'Sedan',
            ],
            [
                'brand' => 'Ford',
                'model' => 'Mustang',
                'engine' => '5.0L V8',
                'price_per_day' => 70,
                'image' => '/images/cars/Ford_Mustang.jpg',
                'quantity' => 1,
                'status' => 'Available',
               
                'stars' => 5,
                'category' => 'Sports',
            ],
            [
                'brand' => 'BMW',
                'model' => 'X5',
                'engine' => '3.0L',
                'price_per_day' => 80,
                'image' => '/images/cars/BMW_X5.jpg',
                'quantity' => 1,
                'status' => 'Available',
                
                'stars' => 5,
                'category' => 'SUV',
            ],
            [
                'brand' => 'Mercedes-Benz',
                'model' => 'E-Class',
                'engine' => '2.0L',
                'price_per_day' => 65,
                'image' => '/images/cars/Mercedes-Benz_E-Class.jpg',
                'quantity' => 1,
                'status' => 'Available',
                
                'stars' => 5,
                'category' => 'Luxury',
            ],
            [
                'brand' => 'Chevrolet',
                'model' => 'Malibu',
                'engine' => '1.5L',
                'price_per_day' => 55,
                'image' => '/images/cars/Chevrolet_Malibu.jpg',
                'quantity' => 1,
                'status' => 'Available',
                
                'stars' => 5,
                'category' => 'Sedan',
            ],
            [
                'brand' => 'Audi',
                'model' => 'A4',
                'engine' => '2.0L',
                'price_per_day' => 70,
                'image' => '/images/cars/Audi_A4.jpg',
                'quantity' => 1,
                'status' => 'Available',
               
                'stars' => 5,
                'category' => 'Luxury',
            ],
            [
                'brand' => 'Nissan',
                'model' => 'Altima',
                'engine' => '2.5L',
                'price_per_day' => 50,
                'image' => '/images/cars/Nissan_Altima.jpg',
                'quantity' => 1,
                'status' => 'Available',
                
                'stars' => 5,
                'category' => 'Sedan',
            ],
            [
                'brand' => 'Hyundai',
                'model' => 'Sonata',
                'engine' => '2.5L',
                'price_per_day' => 45,
                'image' => '/images/cars/Hyundai_Sonata.jpg',
                'quantity' => 1,
                'status' => 'Available',
                
                'stars' => 5,
                'category' => 'Sedan',
            ],
            [
                'brand' => 'Kia',
                'model' => 'Optima',
                'engine' => '2.0L',
                'price_per_day' => 45,
                'image' => '/images/cars/Kia_Optima.jpg',
                'quantity' => 1,
                'status' => 'Available',
               
                'stars' => 5,
                'category' => 'Sedan',
            ],
            [
                'brand' => 'Volkswagen',
                'model' => 'Golf',
                'engine' => '1.4L',
                'price_per_day' => 60,
                'image' => '/images/cars/Volkswagen_Golf.jpg',
                'quantity' => 1,
                'status' => 'Available',
             
                'stars' => 5,
                'category' => 'Hatchback',
            ],
            [
                'brand' => 'Subaru',
                'model' => 'Impreza',
                'engine' => '2.0L',
                'price_per_day' => 60,
                'image' => '/images/cars/Subaru_Impreza.jpg',
                'quantity' => 1,
                'status' => 'Available',
                
                'stars' => 5,
                'category' => 'Sedan',
            ],
            [
                'brand' => 'Ford',
                'model' => 'Focus',
                'engine' => '1.6L',
                'price_per_day' => 50,
                'image' => '/images/cars/Ford_Focus.jpg',
                'quantity' => 1,
                'status' => 'Available',
                
                'stars' => 5,
                'category' => 'Hatchback',
            ],
            [
                'brand' => 'Tesla',
                'model' => 'Model 3',
                'engine' => 'Electric',
                'price_per_day' => 90,
                'image' => '/images/cars/Tesla_Model_3.jpg',
                'quantity' => 1,
                'status' => 'Available',
                
                'stars' => 5,
                'category' => 'Electric',
            ],
            [
                'brand' => 'Chevrolet',
                'model' => 'Camaro',
                'engine' => '6.2L V8',
                'price_per_day' => 100,
                'image' => '/images/cars/Chevrolet_Camaro.jpg',
                'quantity' => 1,
                'status' => 'Available',
                
                'stars' => 5,
                'category' => 'Sports',
            ],
            [
                'brand' => 'Jaguar',
                'model' => 'F-Type',
                'engine' => '3.0L V6',
                'price_per_day' => 120,
                'image' => '/images/cars/Jaguar_F-Type.jpg',
                'quantity' => 1,
                'status' => 'Available',
              
                'stars' => 5,
                'category' => 'Luxury',
            ],
            [
                'brand' => 'Lexus',
                'model' => 'RX 350',
                'engine' => '3.5L V6',
                'price_per_day' => 80,
                'image' => '/images/cars/Lexus_RX_350.jpg',
                'quantity' => 1,
                'status' => 'Available',
             
                'stars' => 5,
                'category' => 'SUV',
            ],
            [
                'brand' => 'Volvo',
                'model' => 'XC60',
                'engine' => '2.0L',
                'price_per_day' => 70,
                'image' => '/images/cars/Volvo_XC60.jpg',
                'quantity' => 1,
                'status' => 'Available',
              
                'stars' => 5,
                'category' => 'SUV',
            ],
            [
                'brand' => 'Porsche',
                'model' => '911 Carrera',
                'engine' => '3.0L Flat-6',
                'price_per_day' => 150.00,
                'image' => '/images/cars/Porsche_911_Carrera.jpg',
                'quantity' => 1,
                'status' => 'Available',
              
                'stars' => 5,
                'category' => 'Sports',
            ],
            [
                'brand' => 'Mitsubishi',
                'model' => 'Outlander',
                'engine' => '2.4L',
                'price_per_day' => 60.00,
                'image' => '/images/cars/Mitsubishi_Outlander.jpg',
                'quantity' => 1,
                'status' => 'Available',
                
                'stars' => 5,
                'category' => 'SUV',
            ],
            [
                'brand' => 'Land Rover',
                'model' => 'Range Rover Sport',
                'engine' => '3.0L V6',
                'price_per_day' => 120.00,
                'image' => '/images/cars/Land_Rover_Range_Rover_Sport.jpg',
                'quantity' => 1,
                'status' => 'Available',
              
                'stars' => 5,
                'category' => 'Luxury',
            ],
            [
                'brand' => 'GMC',
                'model' => 'Sierra_1500',
                'engine' => '5.3L V8',
                'price_per_day' => 120.00,
                'image' => '/images/cars/GMC_Sierra_1500.jpg',
                'quantity' => 1,
                'status' => 'Available',
                
                'stars' => 4,
                'category' => 'Pickup',
            ],
            [
                'brand' => 'Fiat',
                'model' => '500',
                'engine' => '1.4L',
                'price_per_day' => 40.00,
                'image' => '/images/cars/Fiat_500.jpg',
                'quantity' => 1,
                'status' => 'Available',
              
                'stars' => 5,
                'category' => 'Hatchback',
            ],
            [
                'brand' => 'Mini',
                'model' => 'Cooper',
                'engine' => '1.5L',
                'price_per_day' => 55.00,
                'image' => '/images/cars/Mini_Cooper.jpg',
                'quantity' => 1,
                'status' => 'Available',
                
                'stars' => 5,
                'category' => 'Hatchback',
            ],
            [
                'brand' => 'Audi',
                'model' => 'Q5',
                'engine' => '2.0L',
                'price_per_day' => 80.00,
                'image' => '/images/cars/Audi_Q5.jpg',
                'quantity' => 1,
                'status' => 'Available',
               
                'stars' => 5,
                'category' => 'SUV',
            ],
        ];

        DB::table('cars')->insert($cars);
    }
}
