<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\This;

class WishlistController extends Controller
{
    protected $viewPath = "client.wishlist";

    /**
     * @var Wishlist
     */
    protected $wishlist;

    /**
     * @var Cart
     */
    protected $cart;

    /**
     * @var Product
     */
    protected $product;

    /**
     * WishlistController constructor.
     * @param Wishlist $wishlist
     * @param Product $product
     * @param Cart $cart
     */
    public function __construct(Wishlist $wishlist, Product $product, Cart $cart)
    {
        $this->wishlist = $wishlist;
        $this->product = $product;
        $this->cart = $cart;
    }

    /**
     * Get list wishlists
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function index()
    {
        $results = $this->wishlist->with([
            'product'
        ])->whereCustomerId(auth()->id())->get();
        return view("{$this->viewPath}.index")->with([
            'results' => $results,
            'path' => $this->viewPath
        ]);
    }

    /**
     * Add product to wishlist
     *
     * @param string $productId
     * @return RedirectResponse
     */
    public function store($productId = "")
    {
        if (!$productId || !$product = $this->product->find($productId)) {
            abort(404);
        } else {
            if ($this->wishlist->whereCustomerId(auth()->id())->whereProductId($productId)->first()) {
                hwa_notify_error("Sản phẩm đã được thêm vào yêu thích trước đó.");
            } else {
                $this->wishlist->create([
                    'customer_id' => auth()->id(),
                    'product_id' => $productId,
                ]);

                hwa_notify_success("Thêm thành công sản phẩm vào yêu thích.");
            }

            return redirect()->back();
        }
    }

    /**
     * Remove wishlist
     *
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id = "")
    {
        if (!$record = $this->wishlist->find($id)) {
            abort(404);
        } else {
            $record->delete();
            hwa_notify_success("Xóa thành công sản phẩm khỏi yêu thích.");
            return redirect()->route("{$this->viewPath}.index");
        }
    }

    /**
     * Wishlist to cart
     *
     * @param $id
     * @return RedirectResponse
     */
    public function wishlist_to_cart($id)
    {
        if (!$wishlist = $this->wishlist->find($id)) {
            abort(404);
        } else {
            $customerId = auth()->id();
            $productId = $wishlist['product_id'];
            $success = false;
            if ($cart = $this->cart->whereCustomerId($customerId)->whereProductId($productId)->first()) {
                $cart->quantity += 1;
                $cart->save();
                $success = true;
            } else {
                if ($this->cart->create([
                    'customer_id' => $customerId,
                    'product_id' => $productId,
                    'quantity' => 1,
                ])) {
                    $success = true;
                }
            }

            if ($success) {
                $wishlist->delete();
            }

            hwa_notify_success("Thêm giỏ hàng thành công.");
            return redirect()->back();
        }
    }
}
