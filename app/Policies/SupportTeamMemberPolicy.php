<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Owner;
use App\Models\SupportTeamMember;

class SupportTeamMemberPolicy
{
    /**
     * Determine if the given user can view any support team members.
     */
    public function viewAny(Admin|Owner $user): bool
    {
        // Both Admin and Owner can view support team members
        return true;
    }

    /**
     * Determine if the given user can view the support team member.
     */
    public function view(Admin|Owner $user, SupportTeamMember $supportTeamMember): bool
    {
        // Both Admin and Owner can view support team members
        return true;
    }

    /**
     * Determine if the given user can create support team members.
     */
    public function create(Admin|Owner $user): bool
    {
        // Both Admin and Owner can create support team members
        return true;
    }

    /**
     * Determine if the given user can update the support team member.
     */
    public function update(Admin|Owner $user, SupportTeamMember $supportTeamMember): bool
    {
        // Both Admin and Owner can update support team members
        return true;
    }

    /**
     * Determine if the given user can delete the support team member.
     */
    public function delete(Admin|Owner $user, SupportTeamMember $supportTeamMember): bool
    {
        // Both Admin and Owner can delete support team members
        return true;
    }

    /**
     * Determine if the given user can restore the support team member.
     */
    public function restore(Admin|Owner $user, SupportTeamMember $supportTeamMember): bool
    {
        // Both Admin and Owner can restore support team members
        return true;
    }

    /**
     * Determine if the given user can permanently delete the support team member.
     */
    public function forceDelete(Admin|Owner $user, SupportTeamMember $supportTeamMember): bool
    {
        // Only Owner can force delete
        return $user instanceof Owner;
    }
}
