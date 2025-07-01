<?php

namespace App\Http\Controllers\Admin;

use App\Exports\OrderDetailExport;
use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrderController extends Controller
{
    /**
     * @var string View path
     */
    protected $viewPath = 'admin.orders';

    /**
     * @var Product
     */
    protected $product;

    /**
     * @var Customer
     */
    protected $customer;

    /**
     * @var CustomerAddress
     */
    protected $customer_address;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var OrderDetail
     */
    protected $order_detail;

    /**
     * OrderController constructor.
     * @param Product $product
     * @param Customer $customer
     * @param CustomerAddress $customerAddress
     * @param Order $order
     * @param OrderDetail $orderDetail
     */
    public function __construct(Product $product, Customer $customer, CustomerAddress $customerAddress, Order $order, OrderDetail $orderDetail)
    {
        $this->product = $product;
        $this->customer = $customer;
        $this->customer_address = $customerAddress;
        $this->order = $order;
        $this->order_detail = $orderDetail;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        if (!hwa_check_permission('view_order')) {
            abort(404);
        }

        $path = $this->viewPath;

        $storeId = auth()->user()->store_id ?? null; // Assuming user has store_id attribute
        
        $results = $this->order->with([
            'customer_address',
        ]);


        if ($storeId) {
            $results->where('store_id', $storeId);
        }
        $results = $results->orderBy('id', 'desc')->get();

      
        return view("{$path}.index")->with([
            'path' => $path,
            'results' => $results
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Application|Factory|View|Response
     */
    public function show($id)
    {
        $path = $this->viewPath;
        if (!hwa_check_permission('view_order') || !$result = $this->order->with([
            'customer',
            'customer_address',
            'order_details',
        ])->find($id)) {
            abort(404);
        } else {
            return view("{$path}.show")->with([
                'path' => $path,
                'result' => $result,
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function update(Request $request, $id)
    {
        if (!hwa_check_permission('edit_order') || !$order = $this->order->with(['order_details'])->find($id)) {
            abort(404);
        } else {
            $validator = validator($request->all(), [
                'payment_status' => ['required', Rule::in(['0', '1'])],
                'shipping_status' => ['required', Rule::in(['0', '1', '2'])],
                'active' => ['required', Rule::in(array_column(hwaCore()->getOrderStatus(), 'value'))],
            ], [
                'payment_status.required' => 'Trạng thái giao hàng là trường bắt buộc.',
                'payment_status.in' => 'Trạng thái giao hàng không hợp lệ.',
                'shipping_status.required' => 'Trạng thái thanh toán là trường bắt buộc.',
                'shipping_status.in' => 'Trạng thái thanh toán không hợp lệ.',
                'active.required' => 'Trạng thái đơn hàng là trường bắt buộc.',
                'active.in' => 'Trạng thái đơn hàng không hợp lệ.',
            ]);

            if ($validator->fails()) {
                hwa_notify_error($validator->getMessageBag()->first());
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $order->fill([
                    'payment_status' => $request['payment_status'],
                    'shipping_status' => $request['shipping_status'],
                    'active' => $request['active'],
                ])->save();

                if ($request['active'] == 'done' && $request['shipping_status'] == 2 && $request['payment_status'] == 1) {
                    if (isset($order['order_details'])) {
                        foreach ($order['order_details'] as $detail) {
                            $product = $this->product->find($detail['product_id']);
                            if ($product) {
                                $product->quantity -= $detail['qty'];
                                $product->sale += $detail['qty'];
                                $product->save();
                            }
                        }
                    }
                }

                hwa_notify_success("Cập nhật thành công đơn hàng.");
                return redirect()->back();
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        if (!hwa_check_permission('delete_order') || !$result = $this->order->find($id)) {
            abort(404);
        } else {
            if ($result->delete()) {
                hwa_notify_success("Xoá đơn hàng thành công.");
            } else {
                hwa_notify_error("Xóa đơn hàng thất bại.");
            }
            return redirect()->back();
        }
    }

    /**
     * Export list orders
     *
     * @return RedirectResponse|BinaryFileResponse
     */
    public function export()
    {
        $results = $this->order->with([
            'customer',
        ])->orderBy('id', 'asc')
            ->select(array_merge($this->order->getFillable(), ['id', 'created_at']))
            ->get();

        $file_name = strtolower("ds_don_hang_" . date('dmy') . '_' . time() . '.xlsx');
        if (count($results) > 0) {
            return Excel::download(new OrderExport($results), $file_name);
        } else {
            hwa_notify_error("Không có đơn hàng.");
            return redirect()->back();
        }
    }

    /**
     * Order detail export
     *
     * @param $id
     * @return BinaryFileResponse
     */
    public function export_detail($id)
    {
        if (!$result = $this->order->with([
            'customer',
            'customer_address',
            'order_details',
        ])->find($id)) {
            abort(404);
        } else {
            $file_name = strtolower("hoa_don_{$id}_" . Carbon::parse($result['created_at'])->format('dmY') . '_' . time() . '.xlsx');
            return Excel::download(new OrderDetailExport($result), $file_name);
        }
    }
}
