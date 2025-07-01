<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    /**
     * Dashboard redirect
     */
    protected const DASHBOARD = 'admin.home';

    /**
     * @var string View Path
     */
    protected $viewPath;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var array Social provider
     */
    protected $social_provider = [
        'google',
        'facebook',
        'twitter',
        'linkedin',
    ];

    /**
     * ResetPasswordController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->viewPath = 'admin.auth';

        foreach ($this->social_provider as $provider) {
            Config::set("services.{$provider}", [
                'client_id'     => hwa_setting("social_login_{$provider}_app_id", false),
                'client_secret' => hwa_setting("social_login_{$provider}_app_secret", false),
                'redirect'      => route('admin.auth.social.callback', $provider),
            ]);
        }
    }

    /**
     * Social redirect
     *
     * @param $social
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider($social)
    {
        $social = strtolower(trim($social));

        if (!in_array($social, $this->social_provider) || !hwa_setting("social_login_{$social}_enable", false)) {
            // Invalid provider
            hwa_notify_error("Lỗi đăng nhập.", ['top' => true]);
            return redirect()->route("{$this->viewPath}.login");
        } else {
            // Valid provider
            return Socialite::driver($social)->redirect();
        }
    }

    /**
     * Social handle callback
     *
     * @param $social
     * @return RedirectResponse
     */
    public function handleProviderCallback($social)
    {
        if (auth()->guard('admin')->check()) {
            return redirect()->route(self::DASHBOARD);
        } else {
            $social = strtolower(trim($social));
            if (!in_array($social, $this->social_provider)) {
                // Invalid provider
                hwa_notify_error("Lỗi đăng nhập.", ['top' => true]);
                return redirect()->route("{$this->viewPath}.login");
            } else {
                try {
                    // Valid provider
                    $socialUser = Socialite::driver($social)->user(); // Get social user

                    $email = $socialUser->getEmail() ?? $socialUser->getNickname(); // Get email
                    $checkExistUser = $this->user->where('email', $email)->first(); // Check existed user

                    if (!$checkExistUser) {
                        // User not existed
                        hwa_notify_error("Bạn không có quyền truy cập.", ['top' => true]);
                        return redirect()->route("{$this->viewPath}.login");
                    } else {
                        if ($checkExistUser->active != 1) {
                            // Check active user
                            hwa_notify_error("Tài khoản bị khóa.", ['top' => true]);
                            return redirect()->route("{$this->viewPath}.login");
                        } else {
                            // Existed user
                            $user_id = $checkExistUser->id; // get user id

                            // Get existed social account
                            $socialAccount = SocialAccount::whereUserId($user_id)
                                ->whereProvider($social)
                                ->whereProviderUserId($socialUser->getId())
                                ->first();

                            if (!$socialAccount) {
                                // Do not existed social account
                                SocialAccount::create([
                                    'user_id' => $user_id,
                                    'provider_user_id' => $socialUser->getId(),
                                    'provider' => strtolower(trim($social))
                                ]);
                            }

                            auth()->guard('admin')->login($checkExistUser); // login with existed user
                            hwa_notify_success("Đăng nhập thành công."); // notify
                            return redirect()->route(self::DASHBOARD); // redirect to home
                        }
                    }
                } catch (\Exception $exception) {
                    // Invalid provider
                    hwa_notify_error("Lỗi đăng nhập.", ['top' => true]);
                    return redirect()->route("{$this->viewPath}.login");
                }
            }
        }
    }
}
