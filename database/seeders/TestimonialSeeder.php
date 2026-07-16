<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Dana Gilmore',
                'designation' => 'developer',
                'content' => 'I reviewed and purchased a number of different WordPress themes before settling on Wp Residence.',
                'rating' => 5,
                'image' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=120&q=80',
                'status' => true,
            ],
            [
                'name' => 'Susan Barkley',
                'designation' => 'happy seller',
                'content' => 'The WP Estate team did an outstanding job helping me buy and create my first real estate website.',
                'rating' => 5,
                'image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=120&q=80',
                'status' => true,
            ],
            [
                'name' => 'Lisa Simpson',
                'designation' => 'happy buyer',
                'content' => 'We hired the WP Estate team as our buyer agent because they are the perfect team for real estate projects.',
                'rating' => 5,
                'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=120&q=80',
                'status' => true,
            ],
            [
                'name' => 'Sara Loreley',
                'designation' => 'buyer',
                'content' => 'I reviewed and purchased a number of different WordPress themes before settling on Wp Residence.',
                'rating' => 5,
                'image' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=120&q=80',
                'status' => true,
            ],
            [
                'name' => 'Virginia Wolf',
                'designation' => 'happy seller',
                'content' => 'The WP Estate team did an outstanding job helping me buy and create my first real estate website.',
                'rating' => 5,
                'image' => 'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?auto=format&fit=crop&w=120&q=80',
                'status' => true,
            ],
            [
                'name' => 'Jessica Fowley',
                'designation' => 'happy buyer',
                'content' => 'We hired the WP Estate team as our buyer agent because they are the perfect team for real estate projects.',
                'rating' => 5,
                'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=120&q=80',
                'status' => true,
            ],
        ];

        foreach ($testimonials as $t) {
            Testimonial::updateOrCreate(
                ['name' => $t['name']],
                $t
            );
        }
    }
}
