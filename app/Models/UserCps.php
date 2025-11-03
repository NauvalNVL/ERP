<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserCps extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usercps';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'NO_',
        'userID',
        'userName',
        'OFFICIAL_NAME',
        'OFFICIAL_TITLE',
        'MOBILE',
        'TEL_',
        'STS',
        'EXPIRY_DATE',
        'EXPIRED',
        'PRINTER',
        'ROUTE',
        'TYPE',
        'U_PRICE',
        'AC',
        'MC',
        'MC_PRICE',
        'SM',
        'PASS',
        'PRICE',
        'COST',
        'remember_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'PASS',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'EXPIRY_DATE' => 'datetime',
        'EXPIRED' => 'boolean',
        'U_PRICE' => 'decimal:2',
        'MC_PRICE' => 'decimal:2',
        'PRICE' => 'decimal:2',
        'COST' => 'decimal:2',
        'PASS' => 'hashed',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'user_id',
        'username', 
        'official_name',
        'official_title',
        'mobile_number',
        'official_tel',
        'status',
        'password_expiry_date',
        'amend_expired_password'
    ];

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'userID';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getAttribute($this->getAuthIdentifierName());
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->PASS;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string|null
     */
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * Create new UserCps record
     *
     * @param array $data
     * @return \App\Models\UserCps
     */
    public static function createUser(array $data)
    {
        return self::create([
            'NO_' => self::count() + 1,
            'userID' => $data['user_id'],
            'userName' => $data['username'],
            'OFFICIAL_NAME' => $data['official_name'],
            'OFFICIAL_TITLE' => $data['official_title'] ?? '',
            'MOBILE' => $data['mobile_number'] ?? '',
            'TEL_' => $data['official_tel'] ?? '',
            'STS' => $data['status'] === 'A' ? 'Active' : 'Inactive',
            'EXPIRY_DATE' => now()->addDays($data['password_expiry_date'] ?? 90)->format('Y-m-d'),
            'EXPIRED' => ($data['password_expiry_date'] ?? 90) <= 0 ? 'Yes' : 'No',
            'PRINTER' => 'Default',
            'ROUTE' => 'Web',
            'TYPE' => 'Standard',
            'U_PRICE' => '0.00',
            'AC' => 'Default',
            'MC' => 'Web',
            'MC_PRICE' => '0.00',
            'SM' => '0.00',
            'PASS' => bcrypt($data['password'] ?? 'temporary_password'),
            'PRICE' => '0.00',
            'COST' => '0.00'
        ]);
    }

    /**
     * Update login information
     */
    public function updateLoginInfo()
    {
        $this->update([
            'updated_at' => now()
        ]);
    }

    /**
     * Update password and expiry
     */
    public function updatePassword($newPassword, $expiryDays = 90)
    {
        $this->update([
            'PASS' => bcrypt($newPassword),
            'EXPIRY_DATE' => now()->addDays($expiryDays)->format('Y-m-d'),
            'EXPIRED' => 'No'
        ]);
    }

    /**
     * Relationship with UserPermission
     */
    public function permissions()
    {
        return $this->hasMany(UserPermission::class, 'user_id', 'userID');
    }

    /**
     * Check if user has permission for a specific menu
     */
    public function hasPermission($menuKey)
    {
        return $this->permissions()
            ->where('menu_key', $menuKey)
            ->where('can_access', true)
            ->exists();
    }

    /**
     * Get user permissions as array
     */
    public function getPermissionsArray()
    {
        return $this->permissions()
            ->where('can_access', true)
            ->pluck('menu_key')
            ->toArray();
    }

    /**
     * Accessor methods for Vue component compatibility
     */
    public function getUserIdAttribute()
    {
        return $this->attributes['userID'] ?? null;
    }

    public function getUsernameAttribute()
    {
        return $this->attributes['userName'] ?? null;
    }

    public function getOfficialNameAttribute()
    {
        return $this->attributes['OFFICIAL_NAME'] ?? null;
    }

    public function getOfficialTitleAttribute()
    {
        return $this->attributes['OFFICIAL_TITLE'] ?? null;
    }

    public function getMobileNumberAttribute()
    {
        return $this->attributes['MOBILE'] ?? null;
    }

    public function getOfficialTelAttribute()
    {
        return $this->attributes['TEL_'] ?? null;
    }

    public function getStatusAttribute()
    {
        $sts = $this->attributes['STS'] ?? null;
        return $sts === 'Active' ? 'A' : 'O';
    }

    public function getPasswordExpiryDateAttribute()
    {
        return $this->attributes['EXPIRY_DATE'] ?? null;
    }

    public function getAmendExpiredPasswordAttribute()
    {
        $expired = $this->attributes['EXPIRED'] ?? null;
        return $expired === 'No' ? 'Yes' : 'No';
    }
}
