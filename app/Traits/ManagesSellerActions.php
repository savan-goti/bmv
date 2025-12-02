<?php

namespace App\Traits;

use App\Models\SellerManagement;
use Illuminate\Support\Facades\Request;

trait ManagesSellerActions
{
    /**
     * Log a seller management action.
     *
     * @param int $sellerId
     * @param string $action (created, approved, rejected, suspended)
     * @param mixed $createdBy The user who performed the action (Admin, Owner, Staff, Seller)
     * @param string|null $notes Optional notes about the action
     * @return SellerManagement
     */
    protected function logSellerAction(
        int $sellerId,
        string $action,
        $createdBy,
        ?string $notes = null
    ): SellerManagement {
        return SellerManagement::create([
            'seller_id' => $sellerId,
            'created_by_type' => get_class($createdBy),
            'created_by_id' => $createdBy->id,
            'action' => $action,
            'notes' => $notes,
            'ip_address' => Request::ip(),
        ]);
    }

    /**
     * Log seller creation.
     *
     * @param int $sellerId
     * @param mixed $createdBy
     * @param string|null $notes
     * @return SellerManagement
     */
    protected function logSellerCreation(int $sellerId, $createdBy, ?string $notes = null): SellerManagement
    {
        return $this->logSellerAction($sellerId, 'created', $createdBy, $notes);
    }

    /**
     * Log seller approval and update the seller record.
     *
     * @param \App\Models\Seller $seller
     * @param mixed $approvedBy
     * @param string|null $notes
     * @return SellerManagement
     */
    protected function logSellerApproval($seller, $approvedBy, ?string $notes = null): SellerManagement
    {
        // Update seller approval status
        $seller->update([
            'is_approved' => true,
            'approved_at' => now(),
            'approved_by_type' => get_class($approvedBy),
            'approved_by_id' => $approvedBy->id,
            'status' => 'active',
        ]);

        // Log the approval action
        return $this->logSellerAction($seller->id, 'approved', $approvedBy, $notes);
    }

    /**
     * Log seller rejection and update the seller record.
     *
     * @param \App\Models\Seller $seller
     * @param mixed $rejectedBy
     * @param string|null $notes
     * @return SellerManagement
     */
    protected function logSellerRejection($seller, $rejectedBy, ?string $notes = null): SellerManagement
    {
        // Update seller status
        $seller->update([
            'is_approved' => false,
            'status' => 'inactive',
        ]);

        // Log the rejection action
        return $this->logSellerAction($seller->id, 'rejected', $rejectedBy, $notes);
    }

    /**
     * Log seller suspension and update the seller record.
     *
     * @param \App\Models\Seller $seller
     * @param mixed $suspendedBy
     * @param string|null $notes
     * @return SellerManagement
     */
    protected function logSellerSuspension($seller, $suspendedBy, ?string $notes = null): SellerManagement
    {
        // Update seller status
        $seller->update([
            'status' => 'suspended',
        ]);

        // Log the suspension action
        return $this->logSellerAction($seller->id, 'suspended', $suspendedBy, $notes);
    }
}
