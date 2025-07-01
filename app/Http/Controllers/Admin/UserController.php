<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HwaRole;
use App\Models\User;
use App\Models\UserMeta;
use App\Notifications\Admin\RegisterUserRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

/**
 * Class UserController
 * @package App\Http\Controllers\Admin
 */
class UserController extends Controller
{
    /**
     * @var string View path
     */
    protected $viewPath = 'admin.users';

    /**
     * @var string Image path
     */
    protected $imagePath = 'users';

    /**
     * @var User
     */
    protected $user;

    /**
     * @var HwaRole
     */
    protected $role;

    /**
     * UserController constructor.
     * @param User $user
     * @param HwaRole $hwaRole
     */
    public function __construct(User $user, HwaRole $hwaRole)
    {
        $this->user = $user;
        $this->role = $hwaRole;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        if (!hwa_check_permission('view_user')) {
            abort(404);
        } else {
            // Get user path
            $path = $this->viewPath;

            // Get list users
            $results = $this->user->whereNotIn('username', ['admin'])->select([
                'id', 'full_name', 'username', 'email', 'active'
            ])->orderBy('id', 'desc')->get();

            // Show list users
            return view("{$path}.index")->with([
                'path' => $path,
                'results' => $results
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        if (!hwa_check_permission('add_user')) {
            abort(404);
        }

        // Get user path
        $path = $this->viewPath;

        // Show form create new user
        return view("{$path}.form")->with([
            'path' => $path,
            'roles' => $this->role->select(['id', 'title'])->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function store(Request $request)
    {
        if (!hwa_check_permission('add_user')) {
            abort(404);
        }

        // Get user path
        $path = $this->viewPath;

        // Validate rule
        $validator = $this->validateRequest($request);
        if ($validator->fails()) {
            // Validate fail and notice error message
            hwa_notify_error($validator->getMessageBag()->first());
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            if (!hwa_demo_env()) {
                // Get user password
                $password = trim($request['password']);

                // Upload user image
                $avatar = '';
                if ($request->has('avatar')) {
                    $file = $request->file('avatar'); // Get file
                    // Rename image
                    $avatar = strtolower("hwa_" . md5(Str::random(12) . time() . Str::random(25)) . '.' . $file->getClientOriginalExtension());
                    // Save image to /public/storage/users
                    Image::make($file->getRealPath())->resize(720, 720)->save(hwa_image_path($this->imagePath, $avatar));
                }

                // Get user data
                $data = [
                    'first_name' => $request['first_name'],
                    'last_name' => $request['last_name'],
                    'username' => $request['username'],
                    'email' => $request['email'],
                    'password' => bcrypt($password),
                    'active' => $request['active'],
                ];

                // Get user meta data
                $metaData = [
                    'phone' => $request['phone'],
                    'gender' => $request['gender'],
                    'avatar' => $avatar,
                ];

                if ($result = $this->user->create($data)) {
                    $result->assignRole($request['roles']);

                    // Get new user id
                    $id = $result->id;

                    // Send notify to email
                    try {
                        $dataSend = [
                            'subject' => hwa_app_name() . " | Thêm quản trị viên thành công",
                            'first_name' => $result->first_name,
                            'email' => $result->email,
                            'password' => $password,
                        ];
                        $result->notify(new RegisterUserRequest($dataSend));
                    } catch (\Exception $exception) {
                        Log::error($exception->getMessage());
                    }

                    // Add user meta data
                    foreach ($metaData as $metaKey => $metaValue) {
                        UserMeta::_add($id, $metaKey, $metaValue);
                    }

                    // Notice and return users list
                    hwa_notify_success("Thêm quản trị viên thành công.");
                    return redirect()->route("{$path}.index");
                } else {
                    // Delete new image just upload
                    if (file_exists($imagePath = hwa_image_path($this->imagePath, $avatar))) {
                        File::delete($imagePath);
                    }

                    // Notice error and return back
                    hwa_notify_error("Lỗi thêm quản trị viên.");
                    return redirect()->back()->withInput();
                }
            } else {
                // Notice and return users list
                hwa_notify_success("Thêm quản trị viên thành công.");
                return redirect()->route("{$path}.index");
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $id
     * @return Application|Factory|View|Response
     */
    public function edit($id)
    {
        // Get user path
        $path = $this->viewPath;

        if (!hwa_check_permission('edit_user') || !$result = $this->user->findUserMetaByUserId($id)) {
            // User not found
            abort(404);
        } else {
            // show user edit form
            return view("{$path}.form")->with([
                'path' => $path,
                'result' => $result,
                'user' => $this->user->find($id),
                'roles' => $this->role->select(['id', 'title'])->get(),
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
        // Get users path
        $path = $this->viewPath;

        if (!hwa_check_permission('edit_user') || !$result = $this->user->findUserMetaByUserId($id)) {
            // User not found
            abort(404);
        } else {
            // Validate rule
            $validator = $this->validateRequest($request, $result);
            if ($validator->fails()) {
                // Validate fail and notice error message
                hwa_notify_error($validator->getMessageBag()->first());
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                if (auth()->guard('admin')->id() == $id && $request['active'] != 1) {
                    // notify error
                    hwa_notify_error("Không thể hủy kích hoạt người dùng này. Người dùng này đã đăng nhập!");
                    return redirect()->back()->withInput();
                } else {
                    if (!hwa_demo_env()) {
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

                        // Select user
                        $selectResult = $this->user->find($id);

                        // Get user data
                        $data = [
                            'first_name' => $request['first_name'],
                            'last_name' => $request['last_name'],
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
                            $selectResult->syncRoles([$request['roles']]);

                            // delete old image
                            if ($request->has('avatar')) {
                                if (file_exists($imagePath = hwa_image_path($this->imagePath, $currentImage))) {
                                    File::delete($imagePath);
                                }
                            }

                            // Update user meta data
                            foreach ($metaData as $metaKey => $metaValue) {
                                UserMeta::_update($id, $metaKey, $metaValue);
                            }

                            // Notice and return users list
                            hwa_notify_success("Cập nhật quản trị viên thành công.");
                            return redirect()->route("{$path}.index");
                        } else {
                            // Delete new image just upload
                            if ($request->has('avatar')) {
                                if (file_exists($imagePath = hwa_image_path($this->imagePath, $updateImage))) {
                                    File::delete($imagePath);
                                }
                            }

                            // Notice error and return back
                            hwa_notify_error("Lỗi cập nhật quản trị viên.");
                            return redirect()->back()->withInput();
                        }
                    } else {
                        // Notice and return users list
                        hwa_notify_success("Cập nhật quản trị viên thành công.");
                        return redirect()->route("{$path}.index");
                    }
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
        if (!hwa_check_permission('delete_user') || !$result = $this->user->findUserMetaByUserId($id)) {
            // User not found
            abort(404);
        } else {
            if (auth()->guard('admin')->id() == $id) {
                // notify error
                hwa_notify_error("Không thể xóa người dùng này. Người dùng này đã đăng nhập!");
            } else {
                if (!hwa_demo_env()) {
                    // Get user image
                    $avatar = $result['avatar'] ?? '';

                    // Select user
                    $selectResult = $this->user->find($id);

                    if ($selectResult->delete()) {
                        // Delete success
                        if (file_exists($path = hwa_image_path("users", $avatar))) {
                            File::delete($path); // Delete user image
                        }
                        // notify success
                        hwa_notify_success("Xóa quản trị viên thành công.");
                    } else {
                        // notify error
                        hwa_notify_error("Lỗi xóa quản trị viên.");
                    }
                } else {
                    // notify success
                    hwa_notify_success("Xóa quản trị viên thành công.");
                }
            }
            return redirect()->back();
        }
    }

    /**
     * Validate data
     *
     * @param $request
     * @param $result
     * @return Application|\Illuminate\Contracts\Validation\Factory|\Illuminate\Contracts\Validation\Validator
     */
    protected function validateRequest($request, $result = null)
    {
        $rules = [
            'first_name' => ['required', 'max:191'],
            'last_name' => ['required', 'max:191'],
            'username' => ['required', 'max:191', 'unique:users,username'],
            'email' => ['required', 'email', 'max:191', 'unique:users,email'],
            'password' => ['required', 'min:6', 'max:32'],
            'phone' => ['nullable', 'max:20'],
            'gender' => ['nullable', Rule::in(['male', 'female'])],
            'active' => ['required', Rule::in(['0', '1'])],
            'roles' => ['required'],
        ];

        $messages = [
            'first_name.required' => 'Tên là trường bắt buộc.',
            'first_name.max' => 'Tên có tối đa 191 ký tự.',
            'last_name.required' => 'Họ, đệm là trường bắt buộc.',
            'last_name.max' => 'Họ, đệm có tối đa 191 ký tự.',
            'username.required' => 'Tên người dùng là trường bắt buộc.',
            'username.max' => 'Tên người dùng có tối đa 191 ký tự.',
            'username.unique' => 'Tên người dùng đã tồn tại.',
            'email.required' => 'Email là trường bắt buộc.',
            'email.max' => 'Email có tối đa 191 ký tự.',
            'email.unique' => 'Email đã tồn tại.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Mật khẩu là trường bắt buộc.',
            'password.max' => 'Mật khẩu có tối đa 32 ký tự.',
            'password.min' => 'Mật khẩu có tối thiểu 6 ký tự.',
            'phone.max' => 'Số điện thoại có tối đa 20 ký tự.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'active.required' => 'Trạng thái là trường bắt buộc.',
            'active.in' => 'Trạng thái không hợp lệ.',
            'roles.required' => 'Chức vụ là trường bắt buộc.',
        ];

        if ($result) {
            unset($rules['username']);
            unset($rules['email']);
            unset($rules['password']);

            $rules = array_merge($rules, [
                'username' => ['required', 'max:191', 'unique:users,username,' . $result['id']],
                'email' => ['required', 'email', 'max:191', 'unique:users,email,' . $result['id']],
            ]);

            unset($messages['password.required']);
        }

        return validator($request->all(), $rules, $messages);
    }
}
