<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Page::updateOrCreate(
            ['slug' => 'about-us'],
            [
                'title' => 'About Us',
                'content' => 'Get to know our talented team',
                'metadata' => [
                    'sections' => [
                        [
                            'title' => 'More about us',
                            'fields' => [
                                [
                                    'label' => 'Hero Title',
                                    'name' => 'hero-title',
                                    'type' => 'text',
                                    'value' => 'More about us',
                                ],
                                [
                                    'label' => 'Hero Subtitle',
                                    'name' => 'hero-subtitle',
                                    'type' => 'text',
                                    'value' => 'Get to know our talented team',
                                ],
                                [
                                    'label' => 'Hero Background Image',
                                    'name' => 'hero-background-image',
                                    'type' => 'image',
                                    'value' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=2200&q=80',
                                ],
                            ],
                        ],
                        [
                            'title' => 'More About Us',
                            'fields' => [
                                [
                                    'label' => 'Subtitle description',
                                    'name' => 'subtitle-description',
                                    'type' => 'textarea',
                                    'value' => 'Utilizing standard real estate models or the custom profit-sharing model to plan a standard business model and standard business plan.',
                                ],
                                [
                                    'label' => 'Our Mission Description',
                                    'name' => 'our-mission-description',
                                    'type' => 'textarea',
                                    'value' => 'With over USD $4B in transactions, The Whitefield Group has the expertise, resource and system to get your property sold. Your representation in purchasing properties is our priority.',
                                ],
                                [
                                    'label' => 'Our Vision Description',
                                    'name' => 'our-vision-description',
                                    'type' => 'textarea',
                                    'value' => 'Lead the real estate industry by values, transactions, and client success. The Whitefield Group is built on strong foundation and is one of the most trusted names in the business.',
                                ],
                                [
                                    'label' => 'Our Values Description',
                                    'name' => 'our-values-description',
                                    'type' => 'textarea',
                                    'value' => 'While we support listings or buying modular property portfolio, we understand that every transaction is personal. We deliver results with client-reference and connection.',
                                ],
                                [
                                    'label' => 'Our Resources Description',
                                    'name' => 'our-resources-description',
                                    'type' => 'textarea',
                                    'value' => 'While we support listings or buying modular property portfolio, we understand that every transaction is personal. We deliver results with client-reference and connection.',
                                ],
                            ],
                        ],
                        [
                            'title' => 'Meet Our Team',
                            'fields' => [
                                [
                                    'label' => 'Subtitle description',
                                    'name' => 'subtitle-description',
                                    'type' => 'text',
                                    'value' => 'If you want to buy or rent a property in Gurgaon, then what else is better than a local agent? Here we have some local agents.',
                                ],
                            ],
                        ],
                        [
                            'title' => 'Testimonials',
                            'fields' => [
                                [
                                    'label' => 'Subtitle description',
                                    'name' => 'subtitle-description',
                                    'type' => 'text',
                                    'value' => 'Agent performance is very important for selling a property. Here we have some testimonials.',
                                ],
                            ],
                        ],
                        [
                            'title' => 'Frequently Asked Questions',
                            'fields' => [
                                [
                                    'label' => 'Subtitle description',
                                    'name' => 'subtitle-description',
                                    'type' => 'text',
                                    'value' => 'You can search and select matching features, and check details. Here is some important information relating to your property.',
                                ],
                                [
                                    'label' => 'Question 1',
                                    'name' => 'question-1',
                                    'type' => 'text',
                                    'value' => 'Why is it considered necessary to register Agreement for Sale?',
                                ],
                                [
                                    'label' => 'Answer 1',
                                    'name' => 'answer-1',
                                    'type' => 'textarea',
                                    'value' => 'Registration gives legal validity and protects both buyer and seller rights in property transactions.',
                                ],
                                [
                                    'label' => 'Question 2',
                                    'name' => 'question-2',
                                    'type' => 'text',
                                    'value' => 'Do I need to pay stamp duty if the property is transferred or is a gift?',
                                ],
                                [
                                    'label' => 'Answer 2',
                                    'name' => 'answer-2',
                                    'type' => 'textarea',
                                    'value' => 'Yes, stamp duty requirements vary by region and type of transfer, including gifts in many jurisdictions.',
                                ],
                                [
                                    'label' => 'Question 3',
                                    'name' => 'question-3',
                                    'type' => 'text',
                                    'value' => 'What is Carpet Area?',
                                ],
                                [
                                    'label' => 'Answer 3',
                                    'name' => 'answer-3',
                                    'type' => 'textarea',
                                    'value' => 'Carpet area is the net usable floor area inside the apartment walls, excluding common spaces and walls.',
                                ],
                                [
                                    'label' => 'Question 4',
                                    'name' => 'question-4',
                                    'type' => 'text',
                                    'value' => 'What are the documents a buyer would need from me?',
                                ],
                                [
                                    'label' => 'Answer 4',
                                    'name' => 'answer-4',
                                    'type' => 'textarea',
                                    'value' => 'Typically title deed, tax receipts, occupancy certificate, approved plans, and identity documents are required.',
                                ],
                                [
                                    'label' => 'Question 5',
                                    'name' => 'question-5',
                                    'type' => 'text',
                                    'value' => 'How can I qualify for exemptions on Tax?',
                                ],
                                [
                                    'label' => 'Answer 5',
                                    'name' => 'answer-5',
                                    'type' => 'textarea',
                                    'value' => 'Tax exemptions depend on applicable laws, property use, and documentation proving eligibility.',
                                ],
                                [
                                    'label' => 'Question 6',
                                    'name' => 'question-6',
                                    'type' => 'text',
                                    'value' => 'How soon would I receive a call from you after writing?',
                                ],
                                [
                                    'label' => 'Answer 6',
                                    'name' => 'answer-6',
                                    'type' => 'textarea',
                                    'value' => 'Our team generally responds to inquiries within one business day, often much sooner.',
                                ],
                            ],
                        ],
                        [
                            'title' => 'Our Team Stats',
                            'fields' => [
                                [
                                    'label' => 'Subtitle description',
                                    'name' => 'subtitle-description',
                                    'type' => 'text',
                                    'value' => 'Real estate performance is mostly represented in terms of transactions, values, and client success.',
                                ],
                                [
                                    'label' => 'Stat 1 Value',
                                    'name' => 'stat-1-value',
                                    'type' => 'text',
                                    'value' => '$100M',
                                ],
                                [
                                    'label' => 'Stat 1 Label',
                                    'name' => 'stat-1-label',
                                    'type' => 'text',
                                    'value' => 'Current Listing Volume',
                                ],
                                [
                                    'label' => 'Stat 2 Value',
                                    'name' => 'stat-2-value',
                                    'type' => 'text',
                                    'value' => '$400M',
                                ],
                                [
                                    'label' => 'Stat 2 Label',
                                    'name' => 'stat-2-label',
                                    'type' => 'text',
                                    'value' => 'Total Sold 2022 - 2026',
                                ],
                                [
                                    'label' => 'Stat 3 Value',
                                    'name' => 'stat-3-value',
                                    'type' => 'text',
                                    'value' => '$2B',
                                ],
                                [
                                    'label' => 'Stat 3 Label',
                                    'name' => 'stat-3-label',
                                    'type' => 'text',
                                    'value' => 'Lifetime Sales Volume',
                                ],
                            ],
                        ],
                        [
                            'title' => 'Get in touch to plan your next transaction',
                            'fields' => [
                                [
                                    'label' => 'Subtitle description',
                                    'name' => 'subtitle-description',
                                    'type' => 'text',
                                    'value' => 'Our experts and developers would love to contribute their expertise and insights and help you today.',
                                ],
                                [
                                    'label' => 'Side Image',
                                    'name' => 'side-image',
                                    'type' => 'image',
                                    'value' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1000&q=80',
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );
    }
}
