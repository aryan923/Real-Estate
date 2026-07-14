<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AgentMeta;
use Illuminate\Database\Seeder;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        $agentsData = [
            [
                'user' => [
                    'name' => 'Rajesh Kumar',
                    'email' => 'rajesh@propdesk.in',
                    'phone' => '+91 98765 43210',
                    'password' => bcrypt('password123'),
                    'profile_pic' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=300&q=80',
                    'is_admin' => false,
                ],
                'meta' => [
                    'title' => 'Senior Luxury Consultant',
                    'company' => 'PropDesk India',
                    'member_of' => 'National Association of Realtors India (NAR-India)',
                    'address' => 'A-102, Maker Chambers V, Nariman Point, Mumbai, MH 400021',
                    'facebook' => 'https://facebook.com',
                    'twitter' => 'https://twitter.com',
                    'linkedin' => 'https://linkedin.com',
                    'instagram' => 'https://instagram.com',
                    'youtube' => 'https://youtube.com',
                    'vimeo' => 'https://vimeo.com',
                    'whatsapp' => '919876543210',
                    'about_me' => 'Rajesh is a seasoned real estate advisor with over 12 years of experience in Mumbai\'s luxury residential market, specializing in Bandra West, Juhu, and South Mumbai properties.',
                    'specialties' => 'Mumbai, Luxury Residences, High-rise Condos',
                    'experience_details' => '12 years as Premier Agent at Mumbai Homes',
                    'reach_time' => 'Daily, 9 AM to 7 PM',
                    'awards' => 'Best Luxury Agent of Mumbai 2024',
                    'mls_id' => 'MLS-IN-MUM-987',
                    'languages' => 'Hindi, Marathi, English',
                    'experience_years' => '12 Years as Consultant',
                    'is_featured' => true,
                ]
            ],
            [
                'user' => [
                    'name' => 'Priya Patel',
                    'email' => 'priya@propdesk.in',
                    'phone' => '+91 98123 45678',
                    'password' => bcrypt('password123'),
                    'profile_pic' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=300&q=80',
                    'is_admin' => false,
                ],
                'meta' => [
                    'title' => 'Commercial Leasing Specialist',
                    'company' => 'PropDesk India',
                    'member_of' => 'RERA Karnataka Association',
                    'address' => '5th Block, Indiranagar, Bangalore, KA 560038',
                    'facebook' => 'https://facebook.com',
                    'twitter' => 'https://twitter.com',
                    'linkedin' => 'https://linkedin.com',
                    'instagram' => 'https://instagram.com',
                    'youtube' => 'https://youtube.com',
                    'vimeo' => 'https://vimeo.com',
                    'whatsapp' => '919812345678',
                    'about_me' => 'Priya specializes in commercial leasing and co-working space acquisitions in Bangalore\'s tech hubs including Whitefield, HSR Layout, and Indiranagar.',
                    'specialties' => 'Bangalore, Commercial Office, Retail Leases',
                    'experience_details' => '8 years managing corporate workspaces',
                    'reach_time' => 'Mon - Fri, 9 AM to 6 PM',
                    'awards' => 'Top Commercial Broker 2023',
                    'mls_id' => 'MLS-IN-BLR-123',
                    'languages' => 'Kannada, Hindi, English',
                    'experience_years' => '8 Years as Specialist',
                    'is_featured' => true,
                ]
            ],
            [
                'user' => [
                    'name' => 'Amit Sharma',
                    'email' => 'amit@propdesk.in',
                    'phone' => '+91 91234 56789',
                    'password' => bcrypt('password123'),
                    'profile_pic' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=300&q=80',
                    'is_admin' => false,
                ],
                'meta' => [
                    'title' => 'Residential Property Advisor',
                    'company' => 'PropDesk India',
                    'member_of' => 'Delhi NCR Realtors Guild',
                    'address' => 'DLF Phase 3, Sector 24, Gurgaon, HR 122002',
                    'facebook' => 'https://facebook.com',
                    'twitter' => 'https://twitter.com',
                    'linkedin' => 'https://linkedin.com',
                    'instagram' => 'https://instagram.com',
                    'youtube' => 'https://youtube.com',
                    'vimeo' => 'https://vimeo.com',
                    'whatsapp' => '919123456789',
                    'about_me' => 'Amit helps families purchase premium apartments and villas in Pune and Gurgaon, bringing deep local insights and hassle-free document assistance.',
                    'specialties' => 'Pune, Gurgaon, Gated Communities',
                    'experience_details' => '10 years consulting in residential developments',
                    'reach_time' => 'Daily, 10 AM to 8 PM',
                    'awards' => 'Customer Choice Award 2024',
                    'mls_id' => 'MLS-IN-NCR-556',
                    'languages' => 'Hindi, Punjabi, English',
                    'experience_years' => '10 Years as Advisor',
                    'is_featured' => true,
                ]
            ],
            [
                'user' => [
                    'name' => 'Anjali Rao',
                    'email' => 'anjali@propdesk.in',
                    'phone' => '+91 95555 12345',
                    'password' => bcrypt('password123'),
                    'profile_pic' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300&q=80',
                    'is_admin' => false,
                ],
                'meta' => [
                    'title' => 'Land Acquisition Expert',
                    'company' => 'PropDesk India',
                    'member_of' => 'Association of Property Professionals',
                    'address' => 'Connaught Place, New Delhi, DL 110001',
                    'facebook' => 'https://facebook.com',
                    'twitter' => 'https://twitter.com',
                    'linkedin' => 'https://linkedin.com',
                    'instagram' => 'https://instagram.com',
                    'youtube' => 'https://youtube.com',
                    'vimeo' => 'https://vimeo.com',
                    'whatsapp' => '919555512345',
                    'about_me' => 'Anjali advises property owners, developers, and private equity funds on real estate investments and development opportunities in Gurgaon and Delhi.',
                    'specialties' => 'Delhi NCR, Land Deals, Plot Development',
                    'experience_details' => '7 years land consulting and acquisitions',
                    'reach_time' => 'Mon - Sat, 9 AM to 6 PM',
                    'awards' => 'Zoning Advisor of the Year 2023',
                    'mls_id' => 'MLS-IN-NCR-889',
                    'languages' => 'Hindi, English',
                    'experience_years' => '7 Years as Expert',
                    'is_featured' => false,
                ]
            ],
            [
                'user' => [
                    'name' => 'Vikram Malhotra',
                    'email' => 'vikram@propdesk.in',
                    'phone' => '+91 96666 98765',
                    'password' => bcrypt('password123'),
                    'profile_pic' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80',
                    'is_admin' => false,
                ],
                'meta' => [
                    'title' => 'Associate Partner',
                    'company' => 'PropDesk India',
                    'member_of' => 'Maharashtra Realtors League',
                    'address' => 'Koregaon Park, Pune, MH 411001',
                    'facebook' => 'https://facebook.com',
                    'twitter' => 'https://twitter.com',
                    'linkedin' => 'https://linkedin.com',
                    'instagram' => 'https://instagram.com',
                    'youtube' => 'https://youtube.com',
                    'vimeo' => 'https://vimeo.com',
                    'whatsapp' => '919666698765',
                    'about_me' => 'Vikram helps clients navigate high-end residential sales in Pune\'s primary locations including Koregaon Park, Baner, and Kothrud.',
                    'specialties' => 'Pune, Premium Flats, Independent Houses',
                    'experience_details' => '9 years residential sales partner',
                    'reach_time' => 'Daily, 9 AM to 8 PM',
                    'awards' => 'Top Producer 2024',
                    'mls_id' => 'MLS-IN-PUN-345',
                    'languages' => 'Hindi, Marathi, English',
                    'experience_years' => '9 Years as Associate',
                    'is_featured' => false,
                ]
            ]
        ];

        foreach ($agentsData as $agent) {
            $user = User::updateOrCreate(
                ['email' => $agent['user']['email']],
                $agent['user']
            );
            $agent['meta']['user_id'] = $user->id;
            AgentMeta::updateOrCreate(
                ['user_id' => $user->id],
                $agent['meta']
            );
        }
    }
}
