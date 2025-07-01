<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    protected $viewPath = 'client.cart';

    /**
     * @var Product
     */
    protected $product;

    /**
     * @var Cart
     */
    protected $cart;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var OrderDetail
     */
    protected $orderDetail;

    /**
     * @var Customer
     */
    protected $customer;

    /**
     * CartController constructor.
     * @param Product $product
     * @param Cart $cart
     * @param Customer $customer
     * @param Order $order
     * @param OrderDetail $orderDetail
     */
    public function __construct(Product $product, Cart $cart, Customer $customer, Order $order, OrderDetail $orderDetail)
    {
        $this->product = $product;
        $this->cart = $cart;
        $this->customer = $customer;
        $this->order = $order;
        $this->orderDetail = $orderDetail;
    }

    /**
     * Get list cart
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $customer = $this->customer->with(['carts'])->find(auth()->id());
        $subtotal = 0;
        $data = [];

        foreach ($customer['carts'] as $cart) {
            $data[] = [
                'id' => $cart['id'],
                'product_id' => $cart['product']['id'],
                'product_name' => $cart['product']['name'],
                'product_slug' => $cart['product']['slug'],
                'product_thumb' => hwa_image_url("products/thumbs", $cart['product']['thumb']),
                'product_price' => $cart['product']['price'],
                'quantity' => $cart['quantity']
            ];
            $subtotal += $cart['quantity'] * $cart['product']['price'];
        }

        return view("{$this->viewPath}.index")->with([
            'subtotal' => $subtotal,
            'results' => $data,
            'path' => $this->viewPath
        ]);
    }

    /**
     * Add to cart
     *
     * @param Request $request
     * @param string $productId
     * @return RedirectResponse
     */
    public function create(Request $request, $productId = "")
    {
        $customerId = auth()->id();
        if ($request->getMethod() == 'GET') {
            if (!$product = $this->product->find($productId)) {
                abort(404);
            } else {
                if ($cart = $this->cart->whereCustomerId($customerId)->whereProductId($productId)->first()) {
                    $cart->quantity += 1;
                    $cart->save();
                } else {
                    $this->cart->create([
                        'customer_id' => $customerId,
                        'product_id' => $productId,
                        'quantity' => 1,
                    ]);
                }
                hwa_notify_success("Thêm giỏ hàng thành công.");
                return redirect()->back();
            }
        } else {
            if (!$product = $this->product->find($productId)) {
                abort(404);
            } else {
                $validator = Validator::make($request->all(), [
                    'quantity' => ['required', 'between:1,100'],
                ], [
                    'quantity.required' => "Số lượng là trường bắt buộc.",
                    'quantity.between' => "Số lượng trong khoảng 1-100.",
                ]);
                if ($validator->fails()) {
                    hwa_notify_error($validator->getMessageBag()->first());
                    return redirect()->back()->withInput();
                } else {
                    if ($cart = $this->cart->whereCustomerId($customerId)->whereProductId($productId)->first()) {
                        $cart->quantity += ($request['quantity'] ?? intval(1));
                        $cart->save();
                    } else {
                        $this->cart->create([
                            'customer_id' => $customerId,
                            'product_id' => $productId,
                            'quantity' => ($request['quantity'] ?? intval(1)),
                        ]);
                    }
                    hwa_notify_success("Thêm giỏ hàng thành công.");
                    return redirect()->back();
                }
            }
        }
    }

    /**
     * Update cart
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request)
    {
        $qty = $request->input('items', []);
        $product_ids = $request->input('products', []);

        foreach ($product_ids as $product_id) {
            $cart = $this->cart->whereCustomerId(auth()->id())->whereProductId($product_id)->first();
            if ($product_id == $cart['product_id']) {
                $cart->quantity = $qty[$cart['id']];
                $cart->save();
            }
        }

        hwa_notify_success("Cập nhật giỏ hàng thành công.");
        return redirect()->back();
    }

    /**
     * Remove cart
     *
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id = "")
    {
        if (!$record = $this->cart->find($id)) {
            abort(404);
        } else {
            $record->delete();
            hwa_notify_success("Xóa thành công sản phẩm khỏi giỏ hàng.");
            return redirect()->route("{$this->viewPath}.index");
        }
    }

    /**
     * Remove all resource from database.
     *
     * @return RedirectResponse
     */
    public function remove_all()
    {
        $carts = $this->cart->where([
            ['customer_id', '=', auth()->id()]
        ])->get();
        foreach ($carts as $cart) {
            $cart->delete();
        }
        hwa_notify_success("Xóa giỏ hàng thành công.");
        return redirect()->back();
    }

    /**
     * Check out
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function checkout(Request $request)
    {
        $customerId = auth()->id();
        $customer = $this->customer->with(['carts', 'customer_addresses'])->find($customerId);
        $subtotal = 0;
        $data = [];

        foreach ($customer['carts'] as $cart) {
            $data[] = [
                'id' => $cart['id'],
                'product_id' => $cart['product']['id'],
                'product_name' => $cart['product']['name'],
                'product_sku' => $cart['product']['sku'],
                'product_slug' => $cart['product']['slug'],
                'product_price' => $cart['product']['price'],
                'quantity' => $cart['quantity']
            ];
            $subtotal += $cart['quantity'] * $cart['product']['price'];
        }

        $ship = 30000;
        $total = $subtotal + $ship;

        $addresses = $customer['customer_addresses'] ?? [];

        $path = $this->viewPath;
        if ($request->getMethod() == 'GET') {
            if (count($data) == 0) {
                hwa_notify_error("Vui lòng thêm sản phẩm vào giỏ hàng trước.");
                return redirect()->route('client.home');
            } else {
                return view("{$path}.checkout")->with([
                    'path' => $path,
                    'cart' => $data,
                    'subtotal' => $subtotal,
                    'total' => $total,
                    'addresses' => $addresses,
                ]);
            }
        } else {
            $data_order = [
                'customer_id' => $customerId,
                'customer_address_id' => $request['customer_address_id'],
                'subtotal' => $subtotal,
                'total' => $total,
                'ship' => $ship,
                'comment' => $request['comment'],
                'payment_method' => $request['payment_option'],
                'store_id' => auth()->user()->store_id ?? null,
            ];

            $order = $this->order->create($data_order);

            if ($order) {
                foreach ($data as $item) {
                    $this->orderDetail->create([
                        'order_id' => $order['id'],
                        'product_id' => $item['product_id'],
                        'name' => $item['product_name'],
                        'sku' => $item['product_sku'],
                        'price' => $item['product_price'],
                        'qty' => $item['quantity'],
                        'total' => $item['quantity'] * $item['product_price'],
                    ]);
                }
            }

            if ($request['payment_option'] == 'vnpay') {
                if (hwa_setting('vnp_sandbox') == 'sandbox') {
                    $vnp_Url = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
                } else {
                    $vnp_Url = 'https://pay.vnpay.vn/Transaction/PaymentMethod.html';
                }
                $vnp_ReturnUrl = route('client.vn_pay.callback');
                $vnp_TmnCode = hwa_setting('vnp_key');
                $vnp_HashSecret = hwa_setting('vnp_secret');

                $vnp_OrderInfo = hwa_app_name() . ' - ' . Carbon::now('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');

                $transitionId = 'GYM' . str_pad(rand(0, pow(10, 5) - 1), 5, '0', STR_PAD_LEFT);

                $inputData = [
                    "vnp_Version" => "2.0.0",
                    "vnp_TmnCode" => $vnp_TmnCode,
                    "vnp_Amount" => $total * 100,
                    "vnp_Command" => "pay",
                    "vnp_CreateDate" => date('YmdHis'),
                    "vnp_CurrCode" => "VND",
                    "vnp_IpAddr" => request()->ip(),
                    "vnp_Locale" => 'vn',
                    "vnp_OrderInfo" => $vnp_OrderInfo,
                    "vnp_ReturnUrl" => $vnp_ReturnUrl,
                    "vnp_TxnRef" => $transitionId,
                ];

                if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                    $inputData['vnp_BankCode'] = $vnp_BankCode;
                }

                ksort($inputData);
                $query = "";
                $i = 0;
                $hashData = "";
                foreach ($inputData as $key => $value) {
                    if ($i == 1) {
                        $hashData .= '&' . $key . "=" . $value;
                    } else {
                        $hashData .= $key . "=" . $value;
                        $i = 1;
                    }
                    $query .= urlencode($key) . "=" . urlencode($value) . '&';
                }

                $vnp_Url = $vnp_Url . "?" . $query;
                if (isset($vnp_HashSecret)) {
                    $vnpSecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
                    $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
                }

                if ($vnp_HashSecret && $vnp_TmnCode) {
                    $order->transaction = $transitionId;
                    $order->save();
                }

                return redirect()->to($vnp_Url);
            }

            $carts = $this->cart->whereCustomerId(auth()->id())->get();
            foreach ($carts as $cart) {
                $cart->delete();
            }

            session(['payment' => true]);

            hwa_notify_success("Đặt hàng thành công.");
            return redirect()->route('client.checkout.complete');
        }
    }

    /**
     * VNPay Callback
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function vnPayCallback(Request $request)
    {
        $vnp_HashSecret = hwa_setting('vnp_secret');

        $inputData = array();
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $vnp_Amount = $inputData['vnp_Amount'] / 100; // Số tiền thanh toán VNPAY phản hồi

        $orderId = $inputData['vnp_TxnRef'];
        try {
            //Check order id
            //Kiểm tra checksum của dữ liệu
            if ($secureHash == $vnp_SecureHash) {
                //Lấy thông tin đơn hàng lưu trong Database và kiểm tra trạng thái của đơn hàng, mã đơn hàng là: $orderId
                //Việc kiểm tra trạng thái của đơn hàng giúp hệ thống không xử lý trùng lặp, xử lý nhiều lần một giao dịch
                //Giả sử: $order = mysqli_fetch_assoc($result);

                $order = $this->order->whereTransaction($orderId)->first();

                if ($order != NULL) {
                    if ($order["total"] == $vnp_Amount) //Kiểm tra số tiền thanh toán của giao dịch: giả sử số tiền kiểm tra là đúng. //$order["Amount"] == $vnp_Amount
                    {
                        if ($order['payment_status'] == 0) {
                            if ($inputData['vnp_ResponseCode'] == '00' || $inputData['vnp_TransactionStatus'] == '00') {
                                $payment_status = 1; // Trạng thái thanh toán thành công
                                $order_status = 'processing';
                            } else {
                                $payment_status = 0;
                                $order_status = 'fail';
                            }

                            // Xử lý và cập nhật trạng thái đơn hàng
                            $order->payment_status = $payment_status;
                            $order->active = $order_status;
                            $order->save();

                            if ($payment_status == 1) {
                                $carts = $this->cart->whereCustomerId(auth()->id())->get();
                                foreach ($carts as $cart) {
                                    $cart->delete();
                                }

                                session(['payment' => true]);

                                hwa_notify_success("Đặt hàng thành công.");
                                return redirect()->route('client.checkout.complete');
                            } else {
                                hwa_notify_error("Lỗi thanh toán.");
                                return redirect()->route('client.checkout');
                            }
                        } else {
                            hwa_notify_error("Đơn hàng đã thanh toán.");
                        }
                    } else {
                        hwa_notify_error("Số tiền thanh toán không hợp lệ.");
                    }
                } else {
                    hwa_notify_error("Không tìm thấy đơn hàng.");
                }
            } else {
                hwa_notify_error("Chữ ký không hợp lệ.");
            }
        } catch (\Exception $e) {
            hwa_notify_error("Lỗi không xác định.");
        }

        return redirect()->route('client.home');
    }

    /**
     * Order Completed
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function completeCheckout()
    {
        $payment = session('payment');
        if (!$payment) {
            hwa_notify_error("Vui lòng chọn sản phẩm và đặt hàng trước.");
            return redirect()->route('client.home');
        }
        request()->session()->forget('payment');
        return view("{$this->viewPath}.completed");
    }
}
