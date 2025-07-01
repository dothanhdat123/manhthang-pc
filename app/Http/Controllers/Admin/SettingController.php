<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class SettingController extends Controller
{
    protected $viewPath = 'admin.settings';

    /**
     * General Settings
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $path = $this->viewPath;
        if ($request->getMethod() == 'GET') {
            if (!hwa_check_permission('view_system')) {
                abort(404);
            }

            return view("{$path}.index")->with([
                'path' => $path
            ]);
        } else {
            if (!hwa_check_permission('update_system')) {
                abort(404);
            }

            $validator = Validator::make($request->all(), [
                'admin_email' => ['nullable', 'email'],
                'time_zone' => ['nullable', Rule::in(array_keys(hwa_timezone_list()))],
            ], [
                'admin_email.email' => 'Admin email không đúng định dạng.',
                'time_zone.in' => 'Múi giời không hợp lệ.',
            ]);

            if ($validator->fails()) {
                // Invalid data and notice error message
                hwa_notify_error($validator->getMessageBag()->first());
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                if (!hwa_demo_env()) {
                    $favicon = $this->uploadImage($request, 'favicon');
                    $smallLogo = $this->uploadImage($request, 'admin_logo_small');
                    $logo = $this->uploadImage($request, 'admin_logo');
                    $auth_bg = $this->uploadImage($request, 'auth_bg');

                    $generalSettings = [
                        "site_name" => trim($request['site_name']),
                        "admin_title" => trim($request['admin_title']),
                        "admin_email" => strtolower(trim($request['admin_email'])),
                        "time_zone" => trim($request['time_zone']),
                        "favicon" => $favicon,
                        "admin_logo_small" => $smallLogo,
                        "admin_logo" => $logo,
                        "auth_bg" => $auth_bg,
                        "email_from_name" => trim($request['email_from_name']),
                        "vnp_sandbox" => $request['vnp_sandbox'],
                        "vnp_key" => $request['vnp_key'],
                        "vnp_secret" => $request['vnp_secret'],
                    ];

                    $this->saveSettings($generalSettings);
                }
                // Notice success
                hwa_notify_success("Cập nhật thành công.");
                return redirect()->back();
            }
        }
    }

    /**
     * Email settings
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function email(Request $request)
    {
        $path = $this->viewPath;
        if ($request->getMethod() == 'GET') {
            if (!hwa_check_permission('view_system')) {
                abort(404);
            }

            return view("{$path}.email")->with([
                'path' => $path
            ]);
        } else {
            if (!hwa_check_permission('update_system')) {
                abort(404);
            }

            // Validate data input
            $validator = Validator::make($request->all(), [
                'email_driver' => ['required', Rule::in(hwaCore()->getEmailDriver())],
                'email_from_address' => ['nullable', 'email']
            ], [
                'email_driver.required' => 'Máy chủ email là trường bắt buộc.',
                'email_driver.in' => 'Máy chủ email không hợp lệ.',
                'email_from_address.email' => 'Người gửi không hợp lệ'
            ]);

            if ($validator->fails()) {
                // Invalid data and notice error message
                hwa_notify_error($validator->getMessageBag()->first());
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                // Get data setting from request
                $emailSettings = [
                    "email_driver" => strtolower(trim($request['email_driver'])),
                    "email_port" => trim($request['email_port']),
                    "email_host" => strtolower(trim($request['email_host'])),
                    "email_username" => strtolower(trim($request['email_username'])),
                    "email_password" => trim($request['email_password']),
                    "email_encryption" => $request['email_encryption'],
                    "email_mail_gun_domain" => strtolower(trim($request['email_mail_gun_domain'])),
                    "email_mail_gun_secret" => $request['email_mail_gun_secret'],
                    "email_mail_gun_endpoint" => strtolower(trim($request['email_mail_gun_endpoint'])),
                    "email_ses_key" => $request['email_ses_key'],
                    "email_ses_secret" => trim($request['email_ses_secret']),
                    "email_ses_region" => $request['email_ses_region'],
                    "email_from_name" => trim($request['email_from_name']),
                    "email_from_address" => strtolower(trim($request['email_from_address'])),
                    "email_admin_report_enable" => $request['email_admin_report_enable'] ?? 0,
                ];

                if (!hwa_demo_env()) {
                    // Save data if not demo env
                    $this->saveSettings($emailSettings);
                }

                // Notice success
                hwa_notify_success("Cập nhật thành công.");
                return redirect()->back();
            }
        }
    }

    /**
     * Social Login settings
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function socialLogin(Request $request)
    {
        $path = $this->viewPath;
        if ($request->getMethod() == 'GET') {
            if (!hwa_check_permission('view_system')) {
                abort(404);
            }

            return view("{$path}.social")->with([
                'path' => $path
            ]);
        } else {
            if (!hwa_check_permission('update_system')) {
                abort(404);
            }

            if (!hwa_demo_env()) {
                $socialLogin = [
                    "social_login_enable" => trim($request['social_login_enable']),
                    "social_login_google_enable" => trim($request['social_login_google_enable']),
                    "social_login_google_app_id" => trim($request['social_login_google_app_id']),
                    "social_login_google_app_secret" => trim($request['social_login_google_app_secret']),
                    "social_login_facebook_enable" => trim($request['social_login_facebook_enable']),
                    "social_login_facebook_app_id" => trim($request['social_login_facebook_app_id']),
                    "social_login_facebook_app_secret" => trim($request['social_login_facebook_app_secret']),
                    "social_login_twitter_enable" => trim($request['social_login_twitter_enable']),
                    "social_login_twitter_app_id" => trim($request['social_login_twitter_app_id']),
                    "social_login_twitter_app_secret" => trim($request['social_login_twitter_app_secret']),
                    "social_login_linkedin_enable" => trim($request['social_login_linkedin_enable']),
                    "social_login_linkedin_app_id" => trim($request['social_login_linkedin_app_id']),
                    "social_login_linkedin_app_secret" => trim($request['social_login_linkedin_app_secret']),
                ];

                $this->saveSettings($socialLogin);
            }

            // Notice success
            hwa_notify_success("Cập nhật thành công.");
            return redirect()->back();
        }
    }

    /**
     * Shop settings
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function shopSetting(Request $request)
    {
        $path = $this->viewPath;
        if ($request->getMethod() == 'GET') {
            if (!hwa_check_permission('view_system')) {
                abort(404);
            }

            return view("{$path}.shop")->with([
                'path' => $path
            ]);
        } else {
            if (!hwa_check_permission('update_system')) {
                abort(404);
            }

            $validator = validator($request->all(), [
                'site_email' => ['nullable', 'email'],
                'site_description' => ['nullable', 'max:255'],
                'site_title' => ['nullable', 'max:255'],
            ], [
                'site_email.email' => 'Email không đúng định dạng.',
                'site_description.email' => 'Mô tả có tối đa 255 ký tự.',
                'site_title.email' => 'Tiêu đề có tối đa 255 ký tự.',
            ]);

            if ($validator->fails()) {
                hwa_notify_error($validator->getMessageBag()->first());
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $favicon = $this->uploadImage($request, 'site_favicon');
                $logoDark = $this->uploadImage($request, 'site_logo_dark');
                $logoLight = $this->uploadImage($request, 'site_logo_light');

                $shopSetting = [
                    "site_title" => trim($request['site_title']),
                    "site_description" => trim($request['site_description']),
                    "site_keyword" => trim($request['site_keyword']),
                    "site_address" => trim($request['site_address']),
                    "site_email" => trim($request['site_email']),
                    "site_phone" => trim($request['site_phone']),
                    "site_favicon" => $favicon,
                    "site_logo_dark" => $logoDark,
                    "site_logo_light" => $logoLight,
                    "site_google_map" => trim($request['site_google_map']),
                    "site_social_facebook" => trim($request['site_social_facebook']),
                    "site_social_twitter" => trim($request['site_social_twitter']),
                    "site_social_youtube" => trim($request['site_social_youtube']),
                    "site_social_instagram" => trim($request['site_social_instagram']),
                ];

                $this->saveSettings($shopSetting);

                // Notice success
                hwa_notify_success("Cập nhật thành công.");
                return redirect()->back();
            }
        }
    }

    /**
     * Save setting
     *
     * @param array $data
     */
    private function saveSettings(array $data)
    {
        foreach ($data as $settingKey => $settingValue) {
            Setting::updateOrCreate([
                'key' => $settingKey,
            ], [
                'key' => $settingKey,
                'value' => $settingValue
            ]);
        }
    }

    /**
     * Upload image
     *
     * @param $request
     * @param $key
     * @return string
     */
    private function uploadImage($request, $key)
    {
        if ($request->hasFile($key)) {
            $file = $request->file($key);
            $name = strtolower("hwa_" . md5(Str::random(20) . time() . Str::random(20)) . '.' . $file->getClientOriginalExtension());
            Image::make($file->getRealPath())->save(hwa_image_path("system", $name));
        } else {
            $name = hwa_setting($key);
        }
        return $name;
    }

}
