<?php

namespace App\Http\Composers\Client;

use App\Models\Customer;
use Illuminate\View\View;

class CartComposer
{
    /**
     * @var Customer
     */
    protected $customer;

    /**
     * CartComposer constructor.
     * @param Customer $customer
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $data = [];
        $count = 0;
        $subtotal = 0;

        if (auth()->check()) {
            $customer = $this->customer->with(['carts'])->find(auth()->id());
            if ($customer) {
                $count = count($customer['carts']) ?? 0;
                foreach ($customer['carts'] as $cart) {
                    $data[] = [
                        'id' => $cart['id'],
                        'product_name' => $cart['product']['name'],
                        'product_slug' => $cart['product']['slug'],
                        'product_thumb' => hwa_image_url("products/thumbs", $cart['product']['thumb']),
                        'product_price' => $cart['product']['price'],
                        'quantity' => $cart['quantity']
                    ];
                    $subtotal += $cart['quantity'] * $cart['product']['price'];
                }
            }
        }

        $view->with('cart', [
            'count' => $count,
            'subtotal' => $subtotal,
            'data' => $data
        ]);
    }
}
