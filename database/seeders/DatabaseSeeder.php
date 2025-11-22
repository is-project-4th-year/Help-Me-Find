<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Users
        DB::table('users')->insert([
            [
                'id' => 1,
                'firstName' => 'Bethelhem',
                'lastName' => 'Haileselassie',
                'qr_code_token' => '5a9f657f-6019-4005-af88-532a548abdd7',
                'email' => 'bethelhemtesfaye95@gmail.com',
                'password' => '$2y$12$7l67jd0e3HIr2zM7dwj7hePKUq6s8Xwc8JsXO/NtsA4bcOt60lHXy',
                'created_at' => '2025-11-10 07:34:56',
                'updated_at' => '2025-11-10 07:34:56',
            ],
            [
                'id' => 2,
                'firstName' => 'Nelly',
                'lastName' => 'Joyd',
                'qr_code_token' => '219bdafb-760b-40a7-9b52-a33096f4c2ea',
                'email' => 'nellyjoyd77@gmail.com',
                'password' => '$2y$12$uJ4MX08nYDUrUAt5byNcZ.CyOnii1RXKMSRME6jWez8KBZOF8SY2W',
                'created_at' => '2025-11-16 07:46:01',
                'updated_at' => '2025-11-16 07:46:01',
            ],
        ]);

        // Seed Items
        DB::table('items')->insert([
            [
                'id' => 1,
                'image_name' => '1.jpg',
                'description' => 'The image shows a gray Asus laptop, angled slightly towards the viewer. The screen displays the Windows operating system. The keyboard and trackpad are visible.  Ports are located on the side.',
                'finder_first_name' => 'Bethelhem',
                'finder_last_Name' => 'Haileselassie',
                'finder_email' => 'bethelhemtesfaye95@gmail.com',
                'found_date' => '2025-11-10 07:35:21',
                'latitude' => -1.31025007,
                'longitude' => 36.81312192,
                'created_at' => '2025-11-10 07:35:28',
                'updated_at' => '2025-11-10 07:35:28',
            ],
            [
                'id' => 2,
                'image_name' => '2.jpg',
                'description' => 'The image shows a wired computer mouse. It is dark gray with the Dell logo printed on its top surface. It has a standard two-button design with a scroll wheel located between the buttons. The cable is black and bundled together.',
                'finder_first_name' => 'Bethelhem',
                'finder_last_Name' => 'Haileselassie',
                'finder_email' => 'bethelhemtesfaye95@gmail.com',
                'found_date' => '2025-11-12 07:35:45',
                'latitude' => -1.2950852,
                'longitude' => 36.7839056,
                'created_at' => '2025-11-12 07:35:52',
                'updated_at' => '2025-11-12 07:35:52',
            ],
            [
                'id' => 3,
                'image_name' => '3.jpg',
                'description' => 'The image shows a black and silver smartphone. The touchscreen display features colorful app icons over a background with water droplets. The phone also has three touch-sensitive buttons at the bottom of the screen.',
                'finder_first_name' => 'Bethelhem',
                'finder_last_Name' => 'Haileselassie',
                'finder_email' => 'bethelhemtesfaye95@gmail.com',
                'found_date' => '2025-11-13 07:36:30',
                'latitude' => -1.2609951,
                'longitude' => 36.8020759,
                'created_at' => '2025-11-13 07:36:30',
                'updated_at' => '2025-11-13 07:36:30',
            ],
            [
                'id' => 4,
                'image_name' => '4.jpg',
                'description' => 'The image shows a small, rectangular harmonica with a silver and blue color scheme. It has a black cord attached to it, ending in a silver-colored lobster clasp for attachment to keys or other items.',
                'finder_first_name' => 'Nelly',
                'finder_last_Name' => 'Joyd',
                'finder_email' => 'nellyjoyd77@gmail.com',
                'found_date' => '2025-11-18 07:46:23',
                'latitude' => -1.31025007,
                'longitude' => 36.81312192,
                'created_at' => '2025-11-18 07:46:34',
                'updated_at' => '2025-11-18 07:46:34',
            ],
        ]);
    }
}
