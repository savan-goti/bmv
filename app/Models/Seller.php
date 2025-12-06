<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Seller extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\SellerFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'business_name',
        'business_logo',
        'business_type',
        'category_id',
        'sub_category_id',
        'owner_name',
        'username',
        'bar_code',
        'store_link',
        'email',
        'date_of_birth',
        'gender',
        'country_code',
        'phone',
        'phone_otp',
        'otp_validate',
        'aadhar_number',
        'aadhaar_front',
        'aadhaar_back',
        'aadhaar_verified',
        'pancard_number',
        'pancard_image',
        'pancard_verified',
        'gst_number',
        'gst_certificate_image',
        'gst_verified',
        'account_holder_name',
        'ifsc_code',
        'bank_account_number',
        'cancel_check_image',
        'kyc_status',
        'kyc_document',
        'kyc_verified_at',
        'address',
        'city',
        'state',
        'country',
        'pincode',
        'social_links',
        'password',
        'status',
        'is_approved',
        'approved_at',
        'approved_by_type',
        'approved_by_id',
        'last_login_at',
        'last_login_ip',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'email_verified_at',
        'gst_vat', // Keeping existing column
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_approved' => 'boolean',
            'approved_at' => 'datetime',
            'otp_validate' => 'boolean',
            'aadhaar_verified' => 'boolean',
            'pancard_verified' => 'boolean',
            'gst_verified' => 'boolean',
            'kyc_verified_at' => 'datetime',
            'social_links' => 'array',
            'last_login_at' => 'datetime',
            'two_factor_enabled' => 'boolean',
            'two_factor_confirmed_at' => 'datetime',
            'email_verified_at' => 'datetime',
            'date_of_birth' => 'date',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    /**
     * Get the user who approved this seller (polymorphic).
     */
    public function approvedBy()
    {
        return $this->morphTo(__FUNCTION__, 'approved_by_type', 'approved_by_id');
    }

    /**
     * Get all management records for this seller.
     */
    public function managementRecords()
    {
        return $this->hasMany(SellerManagement::class);
    }

    
    public function getBusinessLogoAttribute($image)
    {
        $outputImage = asset('assets/img/no_img.jpg');

        if ($image && $image != null && $image != '') {
            if (file_exists(public_path(SELLER_DOCUMENT_PATH) . $image)) {
                $outputImage = asset(SELLER_DOCUMENT_PATH . $image);
            }
        }

        return $outputImage;
    }
    
    public function getAadhaarFrontAttribute($image)
    {
        $outputImage = asset('assets/img/no_img.jpg');

        if ($image && $image != null && $image != '') {
            if (file_exists(public_path(SELLER_DOCUMENT_PATH) . $image)) {
                $outputImage = asset(SELLER_DOCUMENT_PATH . $image);
            }
        }

        return $outputImage;
    }
    
    public function getAadhaarBackAttribute($image)
    {
        $outputImage = asset('assets/img/no_img.jpg');

        if ($image && $image != null && $image != '') {
            if (file_exists(public_path(SELLER_DOCUMENT_PATH) . $image)) {
                $outputImage = asset(SELLER_DOCUMENT_PATH . $image);
            }
        }

        return $outputImage;
    }
    
    public function getPancardImageAttribute($image)
    {
        $outputImage = asset('assets/img/no_img.jpg');

        if ($image && $image != null && $image != '') {
            if (file_exists(public_path(SELLER_DOCUMENT_PATH) . $image)) {
                $outputImage = asset(SELLER_DOCUMENT_PATH . $image);
            }
        }

        return $outputImage;
    }
    
    public function getGstCertificateImageAttribute($image)
    {
        $outputImage = asset('assets/img/no_img.jpg');

        if ($image && $image != null && $image != '') {
            if (file_exists(public_path(SELLER_DOCUMENT_PATH) . $image)) {
                $outputImage = asset(SELLER_DOCUMENT_PATH . $image);
            }
        }

        return $outputImage;
    }
    
    public function getKycDocumentAttribute($image)
    {
        $outputImage = asset('assets/img/no_img.jpg');

        if ($image && $image != null && $image != '') {
            if (file_exists(public_path(SELLER_DOCUMENT_PATH) . $image)) {
                $outputImage = asset(SELLER_DOCUMENT_PATH . $image);
            }
        }

        return $outputImage;
    }

    public function getCancelCheckImageAttribute($image)
    {
        $outputImage = asset('assets/img/no_img.jpg');

        if ($image && $image != null && $image != '') {
            if (file_exists(public_path(SELLER_DOCUMENT_PATH) . $image)) {
                $outputImage = asset(SELLER_DOCUMENT_PATH . $image);
            }
        }

        return $outputImage;
    }

}
