<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ReviewExport;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReviewController extends Controller
{
    /**
     * @var string Paths
     */
    protected $viewPath = 'admin.reviews';

    /**
     * @var Customer
     */
    protected $customer;

    /**
     * @var Product
     */
    protected $product;

    /**
     * @var Review
     */
    protected $review;

    /**
     * ReviewController constructor.
     * @param Customer $customer
     * @param Product $product
     * @param Review $review
     */
    public function __construct(Customer $customer, Product $product, Review $review)
    {
        $this->customer = $customer;
        $this->product = $product;
        $this->review = $review;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        if (!hwa_check_permission('view_review')) {
            abort(404);
        }

        $path = $this->viewPath;
        $results = $this->review->with([
            'customer',
            'product'
        ])->orderBy('id', 'desc')->get();

        return view("{$path}.index")->with([
            'path' => $path,
            'results' => $results
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return RedirectResponse|BinaryFileResponse
     */
    public function create()
    {
        $results = $this->review->with([
            'product',
            'customer',
        ])->orderBy('id', 'asc')
            ->select(array_merge($this->review->getFillable(), ['id', 'created_at']))
            ->get();

        $file_name = strtolower("danh_gia_san_pham_" . date('d_m_y') . '_' . time() . '.xlsx');
        if (count($results) > 0) {
            return Excel::download(new ReviewExport($results), $file_name);
        } else {
            hwa_notify_error("Không có đánh giá.");
            return redirect()->back();
        }
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
        if (!hwa_check_permission('edit_review') || !$result = $this->review->with([
            'product',
            'customer',
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
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if (!hwa_check_permission('edit_review') || !$result = $this->review->find($id)) {
            abort(404);
        } else {
            // Validate rule and custom message
            $validator = Validator::make($request->all(), [
                'active' => ['required', Rule::in(['published', 'unpublished'])],
            ], [
                'active.required' => 'Trạng thái là trường bắt buộc.',
                'active.in' => 'Trạng thái không hợp lệ.',
            ]);

            if ($validator->fails()) {
                // Invalid data
                hwa_notify_error($validator->getMessageBag()->first());
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                if ($result->fill([
                    'active' => strtolower(trim($request['active'])),
                ])->save()) {
                    // Update success
                    hwa_notify_success("Xét duyệt đánh giá thành công.");
                    return redirect()->back();
                } else {
                    // Error update
                    hwa_notify_error("Lỗi xét duyệt đánh giá.");
                    return redirect()->back()->withInput();
                }
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
        if (!hwa_check_permission('delete_review') || !$result = $this->review->find($id)) {
            abort(404);
        } else {
            if ($result->delete()) {
                hwa_notify_success("Xóa đánh giá thành công.");
            } else {
                hwa_notify_error("Lỗi xóa đánh giá.");
            }
            return redirect()->back();
        }
    }
}
