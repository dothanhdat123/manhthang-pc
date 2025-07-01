<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ContactExport;
use App\Exports\CustomerExport;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\CustomerMeta;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CustomerController extends Controller
{
    /**
     * @var string View path
     */
    protected $viewPath = 'admin.customers';

    /**
     * @var string Image path
     */
    protected $imagePath = 'customers';

    /**
     * @var Customer
     */
    protected $customer;

    /**
     * @var CustomerAddress
     */
    protected $address;

    /**
     * CustomerController constructor.
     * @param Customer $customer
     * @param CustomerAddress $address
     */
    public function __construct(Customer $customer, CustomerAddress $address)
    {
        $this->customer = $customer;
        $this->address = $address;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        if (!hwa_check_permission('view_customer')) {
            abort(404);
        }

        // Get user path
        $path = $this->viewPath;

        $storeId = auth()->user()->store_id ?? null; // Assuming user has store_id attribute
         
        $results = $this->customer->select([
            'id', 'name', 'username', 'email', 'active'
        ]);

        if ($storeId) {
            $results->where('store_id', $storeId);
        }
        $results = $results->orderBy('id', 'desc')->get();

    

        // Show list customer
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
        $result_ids = $this->customer->orderBy('id', 'asc')->select(['id'])->get()->pluck('id')->toArray();
        $results = [];
        foreach ($result_ids as $id) {
            $results[] = $this->customer->findCustomerMetaById($id);
        }

        $file_name = strtolower("khach_hang_" . date('d_m_y') . '_' . time() . '.xlsx');
        if (count($results) > 0) {
            return Excel::download(new CustomerExport($results), $file_name);
        } else {
            hwa_notify_error("Không có khách hàng.");
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
        // Get user path
        $path = $this->viewPath;

        if (!hwa_check_permission('view_customer') || !$result = $this->customer->findCustomerMetaById($id)) {
            // User not found
            abort(404);
        } else {
            // show edit form
            return view("{$path}.show")->with([
                'path' => $path,
                'result' => $result
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return Application|Factory|View|Response
     */
    public function edit($id)
    {
        // Get user path
        $path = $this->viewPath;

        if (!hwa_check_permission('edit_customer') || !$result = $this->customer->findCustomerMetaById($id)) {
            // User not found
            abort(404);
        } else {
            // show edit form
            return view("{$path}.form")->with([
                'path' => $path,
                'result' => $result
            ]);
        }
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
        // Get user path
        $path = $this->viewPath;

        if (!hwa_check_permission('edit_customer') || !$result = $this->customer->findCustomerMetaById($id)) {
            // User not found
            abort(404);
        } else {
            $rules = [
                'name' => ['required', 'max:191'],
                'username' => ['required', 'max:191', 'unique:customers,username,' . $id],
                'email' => ['required', 'email', 'max:191', 'unique:customers,email,' . $id],
                'password' => ['nullable', 'min:6', 'max:32'],
                'phone' => ['nullable', 'max:20'],
                'gender' => ['nullable', Rule::in(['male', 'female'])],
                'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg'],
                'active' => ['required', Rule::in(['0', '1'])],
            ];

            $messages = [
                'name.required' => 'Họ tên là trường bắt buộc.',
                'name.max' => 'Họ tên có tối đa 191 ký tự.',
                'username.required' => 'Tên người dùng là trường bắt buộc.',
                'username.max' => 'Tên người dùng có tối đa 191 ký tự.',
                'username.unique' => 'Tên người dùng đã tồn tại.',
                'email.required' => 'Email là trường bắt buộc.',
                'email.email' => 'Email là không đúng định dạng.',
                'email.max' => 'Email có tối đa 191 ký tự.',
                'email.unique' => 'Email đã tồn tại.',
                'password.min' => 'Mật khẩu có tối thiểu 6 ký tự.',
                'password.max' => 'Mật khẩu có tối đa 32 ký tự.',
                'phone.max' => 'SĐT có tối đa 20 ký tự.',
                'gender.in' => 'Giới tính không hợp lệ.',
                'avatar.image' => 'Ảnh đại diện là không hợp lệ.',
                'avatar.mimes' => 'Định dạng ảnh không hợp lệ.',
                'active.required' => 'Trạng thái là trường bắt buộc.',
                'active.in' => 'Trạng thái không hợp lệ.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                hwa_notify_error($validator->getMessageBag()->first());
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                // Get user image
                $currentImage = $result['avatar'] ?? '';

                // Upload user image
                if ($request->has('avatar')) {
                    $file = $request->file('avatar'); // Get file
                    // Rename image
                    $updateImage = strtolower("hwa_" . md5(Str::random(12) . time() . Str::random(25)) . '.' . $file->getClientOriginalExtension());
                    // Save image to /public/storage/users
                    Image::make($file->getRealPath())->resize(720, 720)->save(hwa_image_path($this->imagePath, $updateImage));
                } else {
                    $updateImage = $currentImage; // No file update
                }

                // Select customer
                $selectResult = $this->customer->find($id);

                // Get customer data
                $data = [
                    'name' => $request['name'],
                    'username' => $request['username'],
                    'email' => $request['email'],
                    'password' => !empty($request['password']) ? bcrypt($request['password']) : $selectResult['password'],
                    'active' => $request['active'],
                ];

                // Get user meta data
                $metaData = [
                    'phone' => $request['phone'],
                    'gender' => $request['gender'],
                    'avatar' => $updateImage,
                ];

                if ($selectResult->fill($data)->save()) {
                    // delete old image
                    if ($request->has('avatar')) {
                        if (file_exists($imagePath = hwa_image_path($this->imagePath, $currentImage))) {
                            File::delete($imagePath);
                        }
                    }

                    // Update customer meta data
                    foreach ($metaData as $metaKey => $metaValue) {
                        CustomerMeta::_update($id, $metaKey, $metaValue);
                    }

                    // Notice and return users list
                    hwa_notify_success("Cập nhật khách hàng thành công.");
                    return redirect()->route("{$path}.index");
                } else {
                    // Delete new image just upload
                    if ($request->has('avatar')) {
                        if (file_exists($imagePath = hwa_image_path($this->imagePath, $updateImage))) {
                            File::delete($imagePath);
                        }
                    }

                    // Notice error and return back
                    hwa_notify_error("Lỗi cập nhật khách hàng.");
                    return redirect()->back()->withInput();
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return RedirectResponse|Response
     */
    public function destroy($id)
    {
        if (!hwa_check_permission('delete_customer') || !$result = $this->customer->findCustomerMetaById($id)) {
            abort(404);
        } else {
            // Get user image
            $avatar = $result['avatar'] ?? '';

            $selectResult = $this->customer->find($id);
            if ($selectResult->delete()) {
                // Delete success
                if (file_exists($path = hwa_image_path($this->imagePath, $avatar))) {
                    File::delete($path); // Delete user image
                }
                hwa_notify_success("Xóa khách hàng thành công.");
            } else {
                hwa_notify_error("Lỗi xóa khách hàng.");
            }
            return redirect()->back();
        }
    }

    /**
     * Delete address
     *
     * @param $customerID
     * @param $addressId
     * @return RedirectResponse
     */
    public function destroy_address($customerID, $addressId)
    {
        if (!hwa_check_permission('delete_address_customer') || !$customer = $this->customer->find($customerID)) {
            abort(404);
        } else {
            if (!$address = $this->address->find($addressId)) {
                abort(404);
            } else {
                if ($address->delete()) {
                    hwa_notify_success("Xóa địa chỉ thành công.");
                } else {
                    hwa_notify_error("Lỗi xóa địa chỉ.");
                }
                return redirect()->back();
            }
        }
    }

}
