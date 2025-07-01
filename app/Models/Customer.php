<?php

namespace App\Models;

use App\Libraries\HwaAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'uid',
        'name',
        'username',
        'email',
        'email_verified_at',
        'password',
        'active',
        'store_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    protected $dates = [
        'created_at'
    ];

    /**
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        return (string)(new HwaAvatar())->create($this->name)->toBase64();
    }

    /**
     * Set email to lower
     *
     * @param $value
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower(trim($value));
    }

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->setAttribute('uid', uniqid());
        });
    }

    /**
     * Find user by email
     *
     * @param $email
     * @return mixed
     */
    public static function findByEmail($email)
    {
        if (empty($email)) return false;

        return self::whereEmail($email)->first();
    }

    /**
     * Find user by username
     *
     * @param $username
     * @return mixed
     */
    public static function findByUserName($username)
    {
        if (empty($username)) return false;

        return self::whereUsername($username)->first();
    }

    /**
     * @return HasMany
     */
    public function customer_metas()
    {
        return $this->hasMany(CustomerMeta::class, 'customer_id')->select([
            'id', 'meta_key', 'meta_value'
        ]);
    }

    /**
     * @return HasMany
     */
    public function customer_addresses()
    {
        return $this->hasMany(CustomerAddress::class)->orderBy('is_default', 'desc');
    }

    /**
     * @return HasMany
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Find user by id with meta data
     *
     * @param $id
     * @return array|false
     */
    public static function findCustomerMetaById($id)
    {
        if (empty($id) || !is_numeric($id)) {
            return false;
        } else {
            $customer = self::select([
                'id', 'uid', 'name', 'username', 'email', 'active', 'created_at'
            ])->with(['customer_addresses'])->find($id);
            if (!$customer) {
                return false;
            }
            unset($customer['customer_metas']);
            return array_merge($customer->toArray(), array_combine(array_column($customer->customer_metas->toArray(), 'meta_key'), array_column($customer->customer_metas->toArray(), 'meta_value')));
        }
    }

}
