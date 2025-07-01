<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    /**
     * @var string[]
     */
    protected $fillable = [
        'customer_id',
        'customer_address_id',
        'subtotal',
        'discount',
        'ship',
        'total',
        'comment',
        'payment_method',
        'payment_status',
        'shipping_status',
        'active',
        'transaction',
        'store_id',
    ];

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function customer_address(): BelongsTo
    {
        return $this->belongsTo(CustomerAddress::class, 'customer_address_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function order_details(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * Order payment method
     *
     * @param string $method
     * @return string
     */
    public static function orderPaymentMethod($method = 'cod')
    {
        switch ($method) {
            case 'vnpay':
                $label = "ATM Online";
                $class = "dark";
                break;
            default:
                $label = "Ship COD";
                $class = "primary";
        }

        return "<span class='badge badge-pill badge-soft-" . $class . " font-size-11' style='line-height: unset!important;'>" . $label . "</span>";
    }

    /**
     * Order payment status
     *
     * @param string $status
     * @return string
     */
    public static function orderPaymentStatus($status = 'cod')
    {
        switch ($status) {
            case 1:
                $label = "Đã thanh toán";
                $class = "success";
                break;
            default:
                $label = "Chưa thanh toán";
                $class = "warning";
        }

        return "<span class='badge badge-pill badge-soft-" . $class . " font-size-11' style='line-height: unset!important;'>" . $label . "</span>";
    }

    /**
     * Order status
     *
     * @param string $status
     * @return string
     */
    public static function orderStatus($status = 'pending')
    {
        switch ($status) {
            case 'processing':
                $label = "Đang xử lý";
                $class = "secondary";
                break;
            case 'cancel':
                $label = "Đã hủy";
                $class = "warning";
                break;
            case 'done':
                $label = "Hoàn thành";
                $class = "success";
                break;
            case 'fail':
                $label = "Thất bại";
                $class = "danger";
                break;
            default:
                $label = "Đơn hàng mới";
                $class = "info";
        }

        return "<span class='badge badge-pill badge-soft-" . $class . " font-size-11' style='line-height: unset!important;'>" . $label . "</span>";
    }
}
