<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Owner;
use App\Models\SupportTeamMember;
use App\Models\SupportDepartment;
use App\Models\SupportQueue;
use App\Models\SupportAuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SupportTeamTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;
    protected $owner;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test admin and owner
        $this->admin = Admin::factory()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
        ]);

        $this->owner = Owner::factory()->create([
            'email' => 'owner@test.com',
            'password' => bcrypt('password123'),
        ]);
    }

    /** @test */
    public function admin_can_view_support_team_index()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.support-team.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.support-team.index');
    }

    /** @test */
    public function owner_can_view_support_team_index()
    {
        $response = $this->actingAs($this->owner, 'owner')
            ->get(route('owner.support-team.index'));

        $response->assertStatus(200);
        $response->assertViewIs('owner.support-team.index');
    }

    /** @test */
    public function admin_can_create_support_team_member()
    {
        $department = SupportDepartment::factory()->create();
        $queue = SupportQueue::factory()->create(['department_id' => $department->id]);

        $data = [
            'name' => 'Test Support Member',
            'email' => 'support@test.com',
            'phone' => '1234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'staff',
            'departments' => [$department->id],
            'default_queues' => [$queue->id],
            'status' => 'active',
            'notification_method' => 'both',
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.support-team.store'), $data);

        $response->assertStatus(200);
        $response->assertJson(['status' => true]);

        $this->assertDatabaseHas('support_team_members', [
            'name' => 'Test Support Member',
            'email' => 'support@test.com',
            'role' => 'staff',
        ]);

        // Check audit log was created
        $this->assertDatabaseHas('support_audit_logs', [
            'action' => 'created',
        ]);
    }

    /** @test */
    public function admin_can_update_support_team_member()
    {
        $member = SupportTeamMember::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@test.com',
        ]);

        $data = [
            'name' => 'Updated Name',
            'email' => 'updated@test.com',
            'role' => 'admin',
            'status' => 'active',
            'notification_method' => 'email',
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.support-team.update', $member->id), $data);

        $response->assertStatus(200);
        $response->assertJson(['status' => true]);

        $this->assertDatabaseHas('support_team_members', [
            'id' => $member->id,
            'name' => 'Updated Name',
            'email' => 'updated@test.com',
        ]);

        // Check audit log was created
        $this->assertDatabaseHas('support_audit_logs', [
            'support_team_member_id' => $member->id,
            'action' => 'updated',
        ]);
    }

    /** @test */
    public function admin_can_delete_support_team_member()
    {
        $member = SupportTeamMember::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.support-team.destroy', $member->id));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertSoftDeleted('support_team_members', [
            'id' => $member->id,
        ]);

        // Check audit log was created
        $this->assertDatabaseHas('support_audit_logs', [
            'support_team_member_id' => $member->id,
            'action' => 'deleted',
        ]);
    }

    /** @test */
    public function validation_fails_with_invalid_data()
    {
        $data = [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123', // Too short
            'role' => 'invalid-role',
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.support-team.store'), $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['error']);
    }

    /** @test */
    public function email_must_be_unique()
    {
        SupportTeamMember::factory()->create(['email' => 'existing@test.com']);

        $data = [
            'name' => 'Test User',
            'email' => 'existing@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'staff',
            'status' => 'active',
            'notification_method' => 'email',
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.support-team.store'), $data);

        $response->assertStatus(422);
    }

    /** @test */
    public function password_must_be_confirmed()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password123',
            'password_confirmation' => 'different-password',
            'role' => 'staff',
            'status' => 'active',
            'notification_method' => 'email',
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.support-team.store'), $data);

        $response->assertStatus(422);
    }

    /** @test */
    public function admin_can_change_member_status()
    {
        $member = SupportTeamMember::factory()->create(['status' => 'active']);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.support-team.status', $member->id), [
                'status' => 'disabled'
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('support_team_members', [
            'id' => $member->id,
            'status' => 'disabled',
        ]);

        // Check audit log was created
        $this->assertDatabaseHas('support_audit_logs', [
            'support_team_member_id' => $member->id,
            'action' => 'status_changed',
        ]);
    }

    /** @test */
    public function role_must_be_valid_enum_value()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'invalid_role',
            'status' => 'active',
            'notification_method' => 'email',
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.support-team.store'), $data);

        $response->assertStatus(422);
    }

    /** @test */
    public function departments_must_exist()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'staff',
            'departments' => [999], // Non-existent department
            'status' => 'active',
            'notification_method' => 'email',
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.support-team.store'), $data);

        $response->assertStatus(422);
    }

    /** @test */
    public function queues_must_exist()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'staff',
            'default_queues' => [999], // Non-existent queue
            'status' => 'active',
            'notification_method' => 'email',
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.support-team.store'), $data);

        $response->assertStatus(422);
    }
}
