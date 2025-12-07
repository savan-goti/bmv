<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SupportDepartment;
use App\Models\SupportQueue;
use App\Models\SupportTeamMember;
use App\Enums\SupportRole;
use Illuminate\Support\Facades\Hash;

class SupportTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create departments
        $technical = SupportDepartment::create([
            'name' => 'Technical Support',
            'description' => 'Handles technical issues and troubleshooting',
            'status' => 'active',
        ]);

        $billing = SupportDepartment::create([
            'name' => 'Billing & Payments',
            'description' => 'Handles billing inquiries and payment issues',
            'status' => 'active',
        ]);

        $general = SupportDepartment::create([
            'name' => 'General Inquiries',
            'description' => 'Handles general questions and information requests',
            'status' => 'active',
        ]);

        // Create queues
        $techQueue1 = SupportQueue::create([
            'name' => 'Level 1 Technical',
            'description' => 'First level technical support',
            'department_id' => $technical->id,
            'status' => 'active',
        ]);

        $techQueue2 = SupportQueue::create([
            'name' => 'Level 2 Technical',
            'description' => 'Advanced technical support',
            'department_id' => $technical->id,
            'status' => 'active',
        ]);

        $billingQueue = SupportQueue::create([
            'name' => 'Billing Queue',
            'description' => 'Billing and payment inquiries',
            'department_id' => $billing->id,
            'status' => 'active',
        ]);

        $generalQueue = SupportQueue::create([
            'name' => 'General Queue',
            'description' => 'General inquiries and questions',
            'department_id' => $general->id,
            'status' => 'active',
        ]);

        // Create sample support team members
        SupportTeamMember::create([
            'name' => 'John Doe',
            'email' => 'john.support@example.com',
            'phone' => '+1234567890',
            'password' => Hash::make('password'),
            'role' => SupportRole::ADMIN,
            'departments' => [$technical->id, $billing->id],
            'default_queues' => [$techQueue1->id, $billingQueue->id],
            'status' => 'active',
            'notification_method' => 'both',
            'tickets_assigned' => 45,
            'open_tickets' => 12,
            'avg_response_time' => 15.5,
            'email_verified_at' => now(),
        ]);

        SupportTeamMember::create([
            'name' => 'Jane Smith',
            'email' => 'jane.support@example.com',
            'phone' => '+1234567891',
            'password' => Hash::make('password'),
            'role' => SupportRole::STAFF,
            'departments' => [$technical->id],
            'default_queues' => [$techQueue1->id, $techQueue2->id],
            'status' => 'active',
            'notification_method' => 'email',
            'tickets_assigned' => 32,
            'open_tickets' => 8,
            'avg_response_time' => 12.3,
            'email_verified_at' => now(),
        ]);

        SupportTeamMember::create([
            'name' => 'Bob Johnson',
            'email' => 'bob.support@example.com',
            'phone' => '+1234567892',
            'password' => Hash::make('password'),
            'role' => SupportRole::STAFF,
            'departments' => [$billing->id, $general->id],
            'default_queues' => [$billingQueue->id, $generalQueue->id],
            'status' => 'active',
            'notification_method' => 'in_app',
            'tickets_assigned' => 28,
            'open_tickets' => 5,
            'avg_response_time' => 18.7,
            'email_verified_at' => now(),
        ]);

        $this->command->info('Support team seeded successfully!');
    }
}
