<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Faq;
use App\Models\Information;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = ['admin', 'user'];
        foreach ($roles as $role) {
            Role::create([
                'name' => $role,
                'guard_name' => 'web',
            ]);
        }
        $admin = User::create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password')
        ]);

        $admin->assignRole('admin');
        $user = User::create([
            'email' => 'user@gmail.com',
            'password' => Hash::make('password')
        ]);

        $user->assignRole('user');

        Information::create([
            'user_id' => $user->id,
            'venue_name' => 'Rhythm Arena',
            'address' => '123 Harmony Avenue, Melody District, Symphony City, NY 10012',
            'phone' => '+1 (555) 789-1234',
            'email' => 'info@rhythmarena.com',
            'additional_information' => 'Rhythm Arena specializes in organizing world-class music events, ranging from intimate acoustic nights to large-scale music festivals. Our venue boasts state-of-the-art sound and lighting systems, a spacious dance floor, and VIP seating arrangements. We also offer event planning services, artist bookings, and catering for private or corporate music events. For inquiries or bookings, feel free to contact us during business hours: Monday - Friday: 10:00 AM - 7:00 PM, Saturday: 12:00 PM - 6:00 PM, Sunday: Closed',
        ]);

        $faqs = [
            [
                'user_id' => $user->id,
                "question" => "What type of events do you organize?",
                "answer" => "We organize a variety of music events, including concerts, festivals, corporate gigs, private parties, and themed music nights."
            ],
            [
                'user_id' => $user->id,
                "question" => "Where is your venue located?",
                "answer" => "Our venue is located at 123 Harmony Avenue, Melody District, Symphony City, NY 10012."
            ],
            [
                'user_id' => $user->id,
                "question" => "How can I book a ticket for an event?",
                "answer" => "Tickets can be booked directly on our website under the 'Events' section or through our official ticketing partners."
            ],
            [
                'user_id' => $user->id,
                "question" => "Can I host a private event at Rhythm Arena?",
                "answer" => "Yes, we offer venue rentals for private events. Please contact us for availability and pricing."
            ],
            [
                'user_id' => $user->id,
                "question" => "What is your refund policy for ticket purchases?",
                "answer" => "Refunds are available up to 7 days before the event. No refunds will be issued after this period unless the event is canceled."
            ],
            [
                'user_id' => $user->id,
                "question" => "Is there parking available at the venue?",
                "answer" => "Yes, we have a dedicated parking area for event attendees, with both free and premium parking options."
            ],
            [
                'user_id' => $user->id,
                "question" => "Do you have wheelchair accessibility?",
                "answer" => "Yes, our venue is fully wheelchair accessible, including restrooms and seating areas."
            ],
            [
                'user_id' => $user->id,
                "question" => "What are your operating hours?",
                "answer" => "We are open during events. For inquiries, our office operates Monday to Friday from 10:00 AM to 7:00 PM."
            ],
            [
                'user_id' => $user->id,
                "question" => "Can I bring my own food and drinks to the venue?",
                "answer" => "Outside food and beverages are not allowed. We have a variety of options available at our in-house cafe and bar."
            ],
            [
                'user_id' => $user->id,
                "question" => "Do you have age restrictions for events?",
                "answer" => "Age restrictions vary by event. Please check the event details on our website for specific age requirements."
            ],
            [
                'user_id' => $user->id,
                "question" => "How can I stay updated on upcoming events?",
                "answer" => "Subscribe to our newsletter or follow us on social media for updates on upcoming events and promotions."
            ],
            [
                'user_id' => $user->id,
                "question" => "Can I buy tickets at the door?",
                "answer" => "Yes, if the event is not sold out, tickets will be available at the door."
            ],
            [
                'user_id' => $user->id,
                "question" => "What forms of payment do you accept?",
                "answer" => "We accept all major credit cards, debit cards, and mobile payment options."
            ],
            [
                'user_id' => $user->id,
                "question" => "Do you offer group discounts for tickets?",
                "answer" => "Yes, group discounts are available for certain events. Please check the event details or contact us for more information."
            ],
            [
                'user_id' => $user->id,
                "question" => "Is smoking allowed inside the venue?",
                "answer" => "No, smoking is strictly prohibited inside the venue. Designated smoking areas are available outside."
            ],
            [
                'user_id' => $user->id,
                "question" => "How can I contact customer support?",
                "answer" => "You can reach us at +1 (555) 789-1234 or email us at support@rhythmarena.com."
            ],
            [
                'user_id' => $user->id,
                "question" => "What should I do if I lose my ticket?",
                "answer" => "Contact our customer support team with your booking details, and we will assist you in retrieving your ticket."
            ],
            [
                'user_id' => $user->id,
                "question" => "Can I volunteer or work at events?",
                "answer" => "Yes, we often look for enthusiastic volunteers and staff. Check our 'Careers' page for current openings."
            ],
            [
                'user_id' => $user->id,
                "question" => "Do you offer VIP packages?",
                "answer" => "Yes, many events include VIP packages with exclusive benefits like premium seating and backstage access."
            ],
            [
                'user_id' => $user->id,
                "question" => "What happens if an event is canceled?",
                "answer" => "In case of cancellation, ticket holders will be notified, and refunds will be processed automatically within 7-10 business days."
            ],
        ];
        Faq::insert($faqs);
        // foreach ($faqs as $faq) {
        //     Faq::create($faq);
        // }
    }
}
