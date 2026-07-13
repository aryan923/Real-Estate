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
                    'name' => 'Amanda Smithson',
                    'email' => 'amanda@luxestate.com',
                    'phone' => '1-800-555-1021',
                    'password' => bcrypt('password123'),
                    'profile_pic' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=300&q=80',
                    'is_admin' => false,
                ],
                'meta' => [
                    'title' => 'Senior Manager',
                    'company' => 'Lux Estate',
                    'member_of' => 'National Association of Luxury Real Estate Professionals',
                    'address' => '120 Greenwich St, Suite 220, Manhattan, New York, NY 10006',
                    'facebook' => 'https://facebook.com',
                    'twitter' => 'https://twitter.com',
                    'linkedin' => 'https://linkedin.com',
                    'instagram' => 'https://instagram.com',
                    'youtube' => 'https://youtube.com',
                    'vimeo' => 'https://vimeo.com',
                    'whatsapp' => '18005551021',
                    'about_me' => 'With a genuine passion for helping clients find their dream homes, Amanda Smithson brings a unique blend of market insight, dedication, and a client-centered approach to every real estate transaction. Known for her integrity, professionalism, and skilled negotiation, Amanda Smithson ensures that buyers and sellers feel confident and supported throughout the entire process.',
                    'specialties' => 'New York, Commercial, Consultancy, Residential',
                    'experience_details' => '18 years as Expert Realtor at Future Realty',
                    'reach_time' => 'Daily, from 9 AM to 5 PM at our local offices',
                    'awards' => 'Best Realtor from 2000-2024',
                    'mls_id' => '126775 MYD - Until 12/12/2025',
                    'languages' => 'French, Spanish, English',
                    'experience_years' => '30 Years as Realtor',
                ]
            ],
            [
                'user' => [
                    'name' => 'Marcus Vance',
                    'email' => 'marcus@luxestate.com',
                    'phone' => '1-800-555-1022',
                    'password' => bcrypt('password123'),
                    'profile_pic' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80',
                    'is_admin' => false,
                ],
                'meta' => [
                    'title' => 'Associate Broker',
                    'company' => 'Lux Estate',
                    'member_of' => 'Broker Association Group',
                    'address' => '150 Broadway St, Suite 10, Manhattan, NY 10007',
                    'facebook' => 'https://facebook.com',
                    'twitter' => 'https://twitter.com',
                    'linkedin' => 'https://linkedin.com',
                    'instagram' => 'https://instagram.com',
                    'youtube' => 'https://youtube.com',
                    'vimeo' => 'https://vimeo.com',
                    'whatsapp' => '18005551022',
                    'about_me' => 'Marcus is dedicated to helping buyers and sellers navigate the complex Manhattan real estate market. His attention to detail and extensive financial background guarantee a seamless experience for high-end clients.',
                    'specialties' => 'Luxury Condos, Penthouse, Investment Properties',
                    'experience_details' => '12 years broker at Premier Properties',
                    'reach_time' => 'Mon - Sat, 10 AM to 6 PM',
                    'awards' => 'Top Producer 2021-2023',
                    'mls_id' => '984577 MX - Until 05/05/2027',
                    'languages' => 'English, German',
                    'experience_years' => '15 Years as Broker',
                ]
            ],
            [
                'user' => [
                    'name' => 'Sofia Rodriguez',
                    'email' => 'sofia@luxestate.com',
                    'phone' => '1-800-555-1023',
                    'password' => bcrypt('password123'),
                    'profile_pic' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300&q=80',
                    'is_admin' => false,
                ],
                'meta' => [
                    'title' => 'Commercial Agent',
                    'company' => 'Lux Estate',
                    'member_of' => 'National Commercial Realtors Board',
                    'address' => '50 Rockefeller Plaza, New York, NY 10020',
                    'facebook' => 'https://facebook.com',
                    'twitter' => 'https://twitter.com',
                    'linkedin' => 'https://linkedin.com',
                    'instagram' => 'https://instagram.com',
                    'youtube' => 'https://youtube.com',
                    'vimeo' => 'https://vimeo.com',
                    'whatsapp' => '18005551023',
                    'about_me' => 'Sofia specializes in commercial acquisitions, leasing, and portfolio management. She advises corporate clients on location strategy and transaction structures.',
                    'specialties' => 'Commercial Leasing, Office Space, Warehouses',
                    'experience_details' => '8 years commercial specialist',
                    'reach_time' => 'Daily, 8 AM to 5 PM',
                    'awards' => 'Dealmaker of the Year 2022',
                    'mls_id' => '127788 MYD - Until 01/01/2026',
                    'languages' => 'Spanish, English, Portuguese',
                    'experience_years' => '10 Years as Commercial Agent',
                ]
            ],
            [
                'user' => [
                    'name' => 'David Kim',
                    'email' => 'david@luxestate.com',
                    'phone' => '1-800-555-1024',
                    'password' => bcrypt('password123'),
                    'profile_pic' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=300&q=80',
                    'is_admin' => false,
                ],
                'meta' => [
                    'title' => 'Senior Advisor',
                    'company' => 'Lux Estate',
                    'member_of' => 'International Real Estate Federation',
                    'address' => '350 Fifth Ave, Empire State Building, New York, NY 10118',
                    'facebook' => 'https://facebook.com',
                    'twitter' => 'https://twitter.com',
                    'linkedin' => 'https://linkedin.com',
                    'instagram' => 'https://instagram.com',
                    'youtube' => 'https://youtube.com',
                    'vimeo' => 'https://vimeo.com',
                    'whatsapp' => '18005551024',
                    'about_me' => 'David brings global perspective to local transactions, helping international buyers acquire prime residential and commercial property in Manhattan.',
                    'specialties' => 'Residential Sales, Global Investment, Wealth Advisory',
                    'experience_details' => '15 years representing international investors',
                    'reach_time' => 'Mon - Fri, 9 AM to 7 PM',
                    'awards' => 'President’s Club Circle 2023',
                    'mls_id' => '334455 AD - Until 10/12/2026',
                    'languages' => 'English, Korean, Japanese',
                    'experience_years' => '18 Years as Advisor',
                ]
            ],
            [
                'user' => [
                    'name' => 'Elena Petrova',
                    'email' => 'elena@luxestate.com',
                    'phone' => '1-800-555-1025',
                    'password' => bcrypt('password123'),
                    'profile_pic' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=300&q=80',
                    'is_admin' => false,
                ],
                'meta' => [
                    'title' => 'Residential Specialist',
                    'company' => 'Lux Estate',
                    'member_of' => 'NY Residential Association',
                    'address' => '720 Fifth Ave, New York, NY 10019',
                    'facebook' => 'https://facebook.com',
                    'twitter' => 'https://twitter.com',
                    'linkedin' => 'https://linkedin.com',
                    'instagram' => 'https://instagram.com',
                    'youtube' => 'https://youtube.com',
                    'vimeo' => 'https://vimeo.com',
                    'whatsapp' => '18005551025',
                    'about_me' => 'Elena is dedicated to matching families with their perfect homes. Her warm approach and extensive neighborhood knowledge make her a trusted guide.',
                    'specialties' => 'Family Homes, Townhouses, Rentals',
                    'experience_details' => '6 years residential sales expert',
                    'reach_time' => 'Daily, from 9 AM to 6 PM',
                    'awards' => 'Best Client Services Award 2024',
                    'mls_id' => '889900 TR - Until 08/08/2027',
                    'languages' => 'Russian, English',
                    'experience_years' => '8 Years as Specialist',
                ]
            ],
            [
                'user' => [
                    'name' => 'Jonathan Wu',
                    'email' => 'jonathan@luxestate.com',
                    'phone' => '1-800-555-1026',
                    'password' => bcrypt('password123'),
                    'profile_pic' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=300&q=80',
                    'is_admin' => false,
                ],
                'meta' => [
                    'title' => 'Investment Consultant',
                    'company' => 'Lux Estate',
                    'member_of' => 'Real Estate Board of New York (REBNY)',
                    'address' => '575 Madison Ave, New York, NY 10022',
                    'facebook' => 'https://facebook.com',
                    'twitter' => 'https://twitter.com',
                    'linkedin' => 'https://linkedin.com',
                    'instagram' => 'https://instagram.com',
                    'youtube' => 'https://youtube.com',
                    'vimeo' => 'https://vimeo.com',
                    'whatsapp' => '18005551026',
                    'about_me' => 'Jonathan advises property owners, developers, and private equity funds on real estate investments and development opportunities in New York.',
                    'specialties' => 'Acquisitions, Underwriting, Valuation',
                    'experience_details' => '9 years investment analyst & broker',
                    'reach_time' => 'Mon - Fri, 8:30 AM to 6 PM',
                    'awards' => 'Rising Star of the Year 2020',
                    'mls_id' => '556677 KL - Until 03/15/2026',
                    'languages' => 'Mandarin, Cantonese, English',
                    'experience_years' => '11 Years as Consultant',
                ]
            ],
            [
                'user' => [
                    'name' => 'Sarah Jenkins',
                    'email' => 'sarah@luxestate.com',
                    'phone' => '1-800-555-1027',
                    'password' => bcrypt('password123'),
                    'profile_pic' => 'https://images.unsplash.com/photo-1534751516642-a131fed10495?auto=format&fit=crop&w=300&q=80',
                    'is_admin' => false,
                ],
                'meta' => [
                    'title' => 'Luxury Property Specialist',
                    'company' => 'Lux Estate',
                    'member_of' => 'Guild for Luxury Home Marketing',
                    'address' => '654 Madison Ave, New York, NY 10021',
                    'facebook' => 'https://facebook.com',
                    'twitter' => 'https://twitter.com',
                    'linkedin' => 'https://linkedin.com',
                    'instagram' => 'https://instagram.com',
                    'youtube' => 'https://youtube.com',
                    'vimeo' => 'https://vimeo.com',
                    'whatsapp' => '18005551027',
                    'about_me' => 'Sarah offers bespoke advisory services for premium clients looking to buy and sell signature properties, historic townhouses, and waterfront estates.',
                    'specialties' => 'Waterfront Estates, Luxury Condos, Historic Homes',
                    'experience_details' => '20 years in high-end real estate sales',
                    'reach_time' => 'By appointment',
                    'awards' => 'Lifetime Achievement Award 2025',
                    'mls_id' => '773344 UI - Until 07/07/2028',
                    'languages' => 'English, Italian',
                    'experience_years' => '25 Years as Specialist',
                ]
            ],
            [
                'user' => [
                    'name' => 'Carlos Mendez',
                    'email' => 'carlos@luxestate.com',
                    'phone' => '1-800-555-1028',
                    'password' => bcrypt('password123'),
                    'profile_pic' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=300&q=80',
                    'is_admin' => false,
                ],
                'meta' => [
                    'title' => 'Buyer Representative',
                    'company' => 'Lux Estate',
                    'member_of' => 'REBNY Guild member',
                    'address' => '200 Park Ave, New York, NY 10166',
                    'facebook' => 'https://facebook.com',
                    'twitter' => 'https://twitter.com',
                    'linkedin' => 'https://linkedin.com',
                    'instagram' => 'https://instagram.com',
                    'youtube' => 'https://youtube.com',
                    'vimeo' => 'https://vimeo.com',
                    'whatsapp' => '18005551028',
                    'about_me' => 'Carlos is dedicated to helping first-time buyers find and secure properties that fit their needs and budgets, providing patient advice and support.',
                    'specialties' => 'First-time Buyers, Condos, Co-ops',
                    'experience_details' => '5 years helping buyers secure financing and homes',
                    'reach_time' => 'Daily, 9 AM to 8 PM',
                    'awards' => 'Best Buyer Agent 2023',
                    'mls_id' => '112233 TY - Until 11/11/2026',
                    'languages' => 'Spanish, English',
                    'experience_years' => '7 Years as Representative',
                ]
            ],
            [
                'user' => [
                    'name' => 'Chloe Dubois',
                    'email' => 'chloe@luxestate.com',
                    'phone' => '1-800-555-1029',
                    'password' => bcrypt('password123'),
                    'profile_pic' => 'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?auto=format&fit=crop&w=300&q=80',
                    'is_admin' => false,
                ],
                'meta' => [
                    'title' => 'Leasing Manager',
                    'company' => 'Lux Estate',
                    'member_of' => 'New York Leasing Board Alliance',
                    'address' => '405 Lexington Ave, Chrysler Building, New York, NY 10174',
                    'facebook' => 'https://facebook.com',
                    'twitter' => 'https://twitter.com',
                    'linkedin' => 'https://linkedin.com',
                    'instagram' => 'https://instagram.com',
                    'youtube' => 'https://youtube.com',
                    'vimeo' => 'https://vimeo.com',
                    'whatsapp' => '18005551029',
                    'about_me' => 'Chloe manages leasing agreements and corporate relocation services for luxury properties and residential towers throughout Manhattan.',
                    'specialties' => 'Luxury Rentals, Relocation Services, Tenant Rep',
                    'experience_details' => '7 years rental specialist at Prime Residences',
                    'reach_time' => 'Mon - Fri, 9 AM to 6 PM',
                    'awards' => 'Top Rental Producer 2022-2024',
                    'mls_id' => '998811 PL - Until 09/09/2027',
                    'languages' => 'French, English',
                    'experience_years' => '9 Years as Leasing Manager',
                ]
            ],
            [
                'user' => [
                    'name' => 'Rajesh Sharma',
                    'email' => 'rajesh@luxestate.com',
                    'phone' => '1-800-555-1030',
                    'password' => bcrypt('password123'),
                    'profile_pic' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=300&q=80',
                    'is_admin' => false,
                ],
                'meta' => [
                    'title' => 'Land Consultant',
                    'company' => 'Lux Estate',
                    'member_of' => 'National Association of Realtors',
                    'address' => '115 Broadway, New York, NY 10006',
                    'facebook' => 'https://facebook.com',
                    'twitter' => 'https://twitter.com',
                    'linkedin' => 'https://linkedin.com',
                    'instagram' => 'https://instagram.com',
                    'youtube' => 'https://youtube.com',
                    'vimeo' => 'https://vimeo.com',
                    'whatsapp' => '18005551030',
                    'about_me' => 'Rajesh specializes in land acquisitions, zoning regulations, and development advisory, assisting clients in maximizing land value.',
                    'specialties' => 'Land Sales, Zoning Advisory, Commercial Plots',
                    'experience_details' => '14 years in commercial and land sales advisory',
                    'reach_time' => 'Mon - Sat, 9 AM to 5 PM',
                    'awards' => 'Consultant of the Decade (Commercial)',
                    'mls_id' => '445566 GH - Until 04/04/2026',
                    'languages' => 'Hindi, Punjabi, English',
                    'experience_years' => '16 Years as Consultant',
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
