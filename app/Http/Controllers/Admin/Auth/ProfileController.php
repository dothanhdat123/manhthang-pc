<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserMeta;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    /**
     * @var string View path
     */
    protected $viewPath = 'admin.auth.profile';

    /**
     * @var string User path
     */
    protected $imagePath = 'users';

    /**
     * @var User
     */
    protected $user;

    /**
     * ProfileController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Profile admin
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function profile(Request $request)
    {
        // Get auth path
        $path = $this->viewPath;

        // Get current user logged id
        $user_id = auth()->guard('admin')->id();

        // Get user info with meta data
        $userWithMeta = $this->user->findUserMetaByUserId($user_id);

        if ($request->getMethod() == 'GET') {
            // Show profile form
            return view("{$path}")->with([
                'path' => $path,
                'user' => $userWithMeta
            ]);
        } else {
            // Validate data
            $validator = Validator::make($request->all(), [
                'first_name' => ['required', 'max:191'],
                'last_name' => ['required', 'max:191'],
                'username' => ['required', 'max:191', 'unique:users,username,' . $user_id],
                'email' => ['required', 'email', 'max:191', 'unique:users,email,' . $user_id],
                'phone' => ['nullable', 'max:20'],
                'gender' => ['nullable', Rule::in(['male', 'female'])],
            ], [
                'first_name.required' => 'Tên là trường bắt buộc.',
                'first_name.max' => 'Tên có tối đa 191 ký tự.',
                'last_name.required' => 'Họ, đệm là trường bắt buộc.',
                'last_name.max' => 'Tên có tối đa 191 ký tự.',
                'username.required' => 'Tên tài khoản là trường bắt buộc.',
                'username.max' => 'Tên tài khoản có tối đa 191 ký tự.',
                'username.unique' => 'Tên tài khoản đã tồn tại.',
                'email.required' => 'Email là trường bắt buộc.',
                'email.max' => 'Email có tối đa 191 ký tự.',
                'email.email' => 'Email không đúng định dạng.',
                'email.exists' => 'Email đã tồn tại.',
                'phone.max' => 'Số điện thoại có tối đa 20 ký tự.',
                'gender.in' => 'Giới tính không hợp lệ.',
            ]);

            if ($validator->fails()) {
                // Validate fail and notice error message
                hwa_notify_error($validator->getMessageBag()->first());
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                if (!hwa_demo_env()) {
                    // Get user image
                    $currentImage = $userWithMeta['avatar'] ?? '';

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
                    $user = $this->user->find($user_id);

                    // Get user data
                    $data = [
                        'first_name' => $request['first_name'],
                        'last_name' => $request['last_name'],
                        'username' => $request['username'],
                        'email' => $request['email'],
                    ];

                    // Get user meta data
                    $metaData = [
                        'phone' => $request['phone'],
                        'gender' => $request['gender'],
                        'avatar' => $updateImage,
                    ];

                    if ($user->fill($data)->save()) {
                        // Update profile success

                        // delete old image
                        if ($request->has('avatar')) {
                            if (file_exists($imagePath = hwa_image_path($this->imagePath, $currentImage))) {
                                File::delete($imagePath);
                            }
                        }

                        // Update user meta data
                        foreach ($metaData as $metaKey => $metaValue) {
                            UserMeta::_update($user_id, $metaKey, $metaValue);
                        }

                        // Notice and return back
                        hwa_notify_success("Cập nhật tài khoản thành công.");
                        return redirect()->back();
                    } else {
                        // Delete new image just upload
                        if ($request->has('avatar')) {
                            if (file_exists($imagePath = hwa_image_path($this->imagePath, $updateImage))) {
                                File::delete($imagePath);
                            }
                        }

                        // Notice error and return back
                        hwa_notify_error("Lỗi cập nhật tài khoản.");
                        return redirect()->back()->withInput();
                    }
                } else {
                    // Notice and return back
                    hwa_notify_success("Cập nhật tài khoản thành công.");
                    return redirect()->back()->withInput();
                }
            }

        }
    }

    /**
     * Change admin password
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function changePassword(Request $request)
    {
        // Get current user
        $user = $this->user->find(auth()->guard('admin')->id());

        // Validate data
        $validator = Validator::make($request->all(), [
            'old_password' => ['required', 'min:6', 'max:32'],
            'password' => ['required', 'min:6', 'max:32'],
            'password_confirmation' => ['required', 'min:6', 'max:32', 'same:password'],
        ],[
            'old_password.required' => 'Mật khẩu cũ là trường bắt buộc.',
            'old_password.min' => 'Mật khẩu cũ có tối thiểu 6 ký tự.',
            'old_password.max' => 'Mật khẩu cũ có tối đa 191 ký tự.',
            'password.required' => 'Mật khẩu mới là trường bắt buộc.',
            'password.min' => 'Mật khẩu mới có tối thiểu 6 ký tự.',
            'password.max' => 'Mật khẩu mới có tối đa 191 ký tự.',
            'password_confirmation.required' => 'Mật khẩu nhập lại là trường bắt buộc.',
            'password_confirmation.min' => 'Mật khẩu nhập lại có tối thiểu 6 ký tự.',
            'password_confirmation.max' => 'Mật khẩu nhập lại có tối đa 191 ký tự.',
            'password_confirmation.same' => 'Mật khẩu nhập lại không khớp.',
        ]);

        if ($validator->fails()) {
            // Validate fail and notice error message
            hwa_notify_error($validator->getMessageBag()->first());
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            if (!password_verify($request['old_password'], $user['password'])) {
                // Old password wrong
                hwa_notify_error('Mật khẩu cũ không đúng.');
                return redirect()->back()->withInput()->withErrors([
                    'old_password' => 'Mật khẩu cũ không đúng.'
                ]);
            } else {
                if (password_verify($request['password'], $user['password'])) {
                    // New password must be different old password
                    hwa_notify_error('Mật khẩu mới phải khác mật khẩu cũ.');
                    return redirect()->back()->withInput()->withErrors([
                        'password' => 'Mật khẩu mới phải khác mật khẩu cũ.'
                    ]);
                } else {
                    if (!hwa_demo_env()) {
                        // Update new password
                        $user['password'] = bcrypt($request['password']);
                        $user->save();
                    }

                    // Notice and return users list
                    hwa_notify_success("Đổi mật khẩu thành công.");
                    return redirect()->back();
                }
            }
        }
    }
}
