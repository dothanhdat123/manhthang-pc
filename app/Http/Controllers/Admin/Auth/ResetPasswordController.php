<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\Admin\ResetPasswordRequest;
use App\Notifications\Admin\ResetPasswordSuccess;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /**
     * Dashboard redirect
     */
    protected const DASHBOARD = 'admin.home';

    protected const LOGIN_DIR = 'admin.auth.login';

    /**
     * @var string View path
     */
    protected $viewPath = 'admin.auth';

    /**
     * @var User
     */
    protected $user;

    /**
     * ResetPasswordController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Forget admin password
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function forget(Request $request)
    {
        $path = $this->viewPath;
        if ($request->getMethod() == 'GET') {
            // GET Method
            if (auth()->guard('admin')->check()) {
                // redirect to dashboard if logged
                return redirect()->route(self::DASHBOARD);
            } else {
                // redirect login form
                return view("{$path}.password.forget")->with([
                    'path' => $path
                ]);
            }
        } else {
            // POST Method

            // Validate data input
            $validator = Validator::make($request->only(['email']), [
                'email' => ['required', 'max:191', 'email', 'exists:users,email']
            ], [
                'email.required' => 'Email là trường bắt buộc.',
                'email.max' => 'Email có tối đa 191 ký tự.',
                'email.email' => 'Email không đúng định dạng.',
                'email.exists' => 'Email không tồn tại.',
            ]);

            if ($validator->fails()) {
                // check validate and return error message.
                hwa_notify_error($validator->getMessageBag()->first(), ['top' => true]);
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                // Get email from request
                $email = strtolower(trim($request['email']));

                // Check existed and active user
                $user = $this->user->where('email', $email)->where('active', 1)->first();
                if (!$user) {
                    // User is blocked.
                    hwa_notify_error("Tài khoản bị khóa.", ['top' => true]);
                    return redirect()->back()->withInput()->withErrors([
                        'email' => 'Tài khoản bị khóa.'
                    ]);
                } else {
                    if (!hwa_demo_env()) {
                        // Create or update token for recovery password
                        $passwordReset = PasswordReset::updateOrCreate([
                            'email' => $user->email
                        ], [
                            'email' => $user->email,
                            'token' => Str::random(60),
                        ]);

                        if ($passwordReset) {
                            // Send mail to reset password
                            try {
                                $dataSend = [
                                    'subject' => hwa_app_name() . " | Yêu cầu đổi mật khẩu",
                                    'first_name' => $user->first_name,
                                    'token' => $passwordReset->token,
                                ];
                                $user->notify(new ResetPasswordRequest($dataSend));
                            } catch (Exception $exception) {
                                Log::error($exception->getMessage());
                            }

                            // Notify success
                            hwa_notify_success("Chúng tôi đã gửi qua e-mail liên kết đặt lại mật khẩu của bạn!", ['top' => true]);
                            return redirect()->route(self::LOGIN_DIR);
                        } else {
                            // Notify error
                            hwa_notify_error("Lỗi đối với mật khẩu khôi phục.", ['top' => true]);
                            return redirect()->back()->withInput();
                        }
                    } else {
                        // Notify success
                        hwa_notify_success("Chúng tôi đã gửi qua e-mail liên kết đặt lại mật khẩu của bạn!", ['top' => true]);
                        return redirect()->route(self::LOGIN_DIR);
                    }
                }
            }
        }
    }

    /**
     * Reset admin password
     *
     * @param $token
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function reset($token, Request $request)
    {
        $path = $this->viewPath;
        if ($request->getMethod() == 'GET') {
            // GET Method
            if (auth()->guard('admin')->check()) {
                // redirect to dashboard if logged
                return redirect()->route(self::DASHBOARD);
            } else {
                if (!$passwordReset = PasswordReset::where('token', $token)->first()) {
                    // Invalid token
                    hwa_notify_error("Token không hợp lệ hoặc hết hạn.", ['top' => true]);
                    return redirect()->route(self::LOGIN_DIR); // redirect to login page
                } else {
                    // Token is invalid after hour
                    if (Carbon::parse($passwordReset->updated_at)->addMinutes(30)->isPast()) {
                        $passwordReset->delete();
                        hwa_notify_error("Tokens đã hết hạn.", ['top' => true]);
                        return redirect()->route(self::LOGIN_DIR);
                    } else {
                        // Call view reset password
                        return view("{$path}.password.reset")->with([
                            'path' => $path,
                            'passwordReset' => $passwordReset,
                        ]);
                    }
                }
            }
        } else {
            // Token not found
            if (!$passwordReset = PasswordReset::where('token', $token)->first()) {
                hwa_notify_error("Token không hợp lệ hoặc hết hạn.", ['top' => true]); // notify
                return redirect()->route(self::LOGIN_DIR); // redirect to login page
            } else {
                // Existed token
                if (Carbon::parse($passwordReset->updated_at)->addMinutes(60)->isPast()) {
                    // Delete token if invalid
                    $passwordReset->delete();
                    hwa_notify_error("Tokens đã hết hạn.", ['top' => true]);
                    return redirect()->route(self::LOGIN_DIR); // redirect to login page
                } else {
                    if (!$user = $this->user->where('email', $passwordReset->email)->where('active', 1)->first()) {
                        // Delete token if user is blocked
                        $passwordReset->delete();
                        hwa_notify_error("Tài khoản bị khóa.", ['top' => true]); // notify
                        return redirect()->route(self::LOGIN_DIR); // redirect to login page
                    } else {
                        // Validate data input
                        $validator = Validator::make($request->all(), [
                            'password' => ['required', 'min:6', 'max:191'],
                            'password_confirmation' => ['required', 'min:6', 'max:191', 'same:password'],
                        ], [
                            'password.required' => 'Mật khẩu mới là trường bắt buộc.',
                            'password.min' => 'Mật khẩu mới có tối thiểu 6 ký tự.',
                            'password.max' => 'Mật khẩu mới có tối đa 191 ký tự.',
                            'password_confirmation.required' => 'Mật khẩu nhập lại là trường bắt buộc.',
                            'password_confirmation.min' => 'Mật khẩu nhập lại có tối thiểu 6 ký tự.',
                            'password_confirmation.max' => 'Mật khẩu nhập lại có tối đa 191 ký tự.',
                            'password_confirmation.same' => 'Mật khẩu nhập lại không khớp.',
                        ]);

                        if ($validator->fails()) {
                            // Invalid data
                            hwa_notify_error($validator->getMessageBag()->first(), ['top' => true]); // notify
                            return redirect()->back()->withInput()->withErrors($validator); // redirect back
                        } else {
                            if (!hwa_demo_env()) {
                                // Get password
                                $password = $request['password'];
                                if ($user->fill(['password' => bcrypt($password)])->save()) {
                                    // Delete token if update success
                                    $passwordReset->delete();

                                    // Send mail to reset password successfully
                                    try {
                                        $dataSend = [
                                            'subject' => hwa_app_name() . " | Mật khẩu của bạn đã được thay đổi thành công",
                                            'first_name' => $user->first_name,
                                            'email' => $user->email,
                                            'password' => $password,
                                            'updated_at' => $user->updated_at,
                                        ];
                                        $user->notify(new ResetPasswordSuccess($dataSend)); // send notify

                                        // using queue

                                    } catch (Exception $exception) {
                                        // Error to send email
                                        Log::error($exception->getMessage());
                                    }

                                    // Login with user
                                    auth()->guard('admin')->login($user);
                                    hwa_notify_success("Đặt lại mật khẩu thành công."); // notify
                                    return redirect()->route(self::DASHBOARD); // redirect to home page
                                } else {
                                    // Error to update
                                    hwa_notify_error("Lỗi khi đặt lại mật khẩu.", ['top' => true]); // notify
                                    return redirect()->route(self::LOGIN_DIR); // redirect to login page
                                }
                            } else {
                                // Notify success
                                hwa_notify_success("Đặt lại mật khẩu thành công."); // notify
                                return redirect()->route(self::DASHBOARD); // redirect to home page
                            }
                        }
                    }
                }
            }
        }
    }
}
