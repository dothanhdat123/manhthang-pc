<?php

namespace App\Libraries;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;

class HwaCore
{
    /**
     * Recall itself
     *
     * @var $_instance
     */
    public static $_instance;

    /**
     * Check instance and reset it
     *
     * @return HwaCore $_instance
     */
    public static function instance(): HwaCore
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Admin menu
     *
     * @return array
     */
    public function getAdminMenu()
    {
        return [
            [
                'label' => 'Bảng điều khiển',
                'icon' => 'bx-home-circle',
                'route' => 'home'
            ],
             [
                'label' => 'Quản lý cửa hàng',
                'icon' => 'bx-info-circle',
                'route' => 'stores.index',
            ],
            [
                'label' => 'Nội dung',
                'icon' => 'bx-chalkboard',
                'items' => [
                    [
                        'label' => 'Banner',
                        'icon' => 'bx-images',
                        'route' => 'banners.index',
                        'permission' => 'view_banner',
                    ],
                    [
                        'label' => 'Đánh giá',
                        'icon' => 'bx-like',
                        'route' => 'reviews.index',
                        'permission' => 'view_review',
                    ],
                    [
                        'label' => 'Tin tức',
                        'icon' => 'bx-detail',
                        'route' => 'news.index',
                        'permission' => 'view_blog',
                    ],
                ],
                'permissions' => [
                    'view_banner',
                    'view_review',
                    'view_blog',
                ]
            ],
            [
                'label' => 'Marketing',
                'icon' => 'bx-cast',
                'items' => [
                    [
                        'label' => 'Khách hàng',
                        'icon' => 'bx-user-voice',
                        'route' => 'customers.index',
                        'permission' => 'view_customer',
                    ],
                    [
                        'label' => 'Đăng ký',
                        'icon' => 'bx-paper-plane',
                        'route' => 'newsletter.index',
                        'permission' => 'view_newsletter',
                    ],
                    [
                        'label' => 'Liên hệ',
                        'icon' => 'bxs-user-detail',
                        'route' => 'contacts.index',
                        'permission' => 'view_contact',
                    ],
                ],
                'permissions' => [
                    'view_customer',
                    'view_newsletter',
                    'view_contact',
                ]
            ],
            [
                'label' => 'Quản lý bán hàng',
                'icon' => 'bx-store',
                'items' => [
                    [
                        'label' => 'Nhà cung cấp',
                        'icon' => 'bx-first-aid',
                        'route' => 'suppliers.index',
                        'permission' => 'view_supplier',
                    ],
                    [
                        'label' => 'Danh mục',
                        'icon' => 'bx-folder-open',
                        'route' => 'categories.index',
                        'permission' => 'view_category',
                    ],
                    [
                        'label' => 'Thương hiệu',
                        'icon' => 'bx-copyright',
                        'route' => 'brands.index',
                        'permission' => 'view_brand',
                    ],
                    [
                        'label' => 'Sản phẩm',
                        'icon' => 'bx-barcode',
                        'route' => 'products.index',
                        'permission' => 'view_product',
                    ],
                    [
                        'label' => 'Đơn hàng',
                        'icon' => 'bx-cart-alt',
                        'route' => 'orders.index',
                        'permission' => 'view_order',
                    ],
                ],
                'permissions' => [
                    'view_supplier',
                    'view_category',
                    'view_brand',
                    'view_product',
                    'view_order',
                ]
            ],
            [
                'label' => 'Thiết lập cửa hàng',
                'icon' => 'bx-cog',
                'items' => [
                    [
                        'label' => 'Cơ bản',
                        'icon' => 'bx-receipt',
                        'route' => 'settings.shop',
                        'permission' => 'view_system',
                    ],
                    [
                        'label' => 'FAQs',
                        'icon' => 'bx-question-mark',
                        'route' => 'faqs.index',
                        'permission' => 'view_faq',
                    ],
                    [
                        'label' => 'Trang tĩnh',
                        'icon' => 'bx-file-find',
                        'route' => 'pages.index',
                        'permission' => 'view_page',
                    ],
                ],
                'permissions' => [
                    'view_system',
                    'view_faq',
                    'view_page',
                ]
            ],
            [
                'label' => 'Quản trị hệ thống',
                'icon' => 'bx-rocket',
                'items' => [
                    [
                        'label' => 'Cơ bản',
                        'icon' => 'bx-customize',
                        'route' => 'settings.index',
                        'permission' => 'view_system',
                    ],
                    [
                        'label' => 'Quyền và chức vụ',
                        'icon' => 'bx-shield-quarter',
                        'route' => 'roles.index',
                        'permission' => 'view_role',
                    ],
                    [
                        'label' => 'Quản trị viên',
                        'icon' => 'bx-group',
                        'route' => 'users.index',
                        'permission' => 'view_user',
                    ],
                    [
                        'label' => 'Thiết lập Email',
                        'icon' => 'bx-envelope',
                        'route' => 'settings.email',
                        'permission' => 'view_system',
                    ],
                    [
                        'label' => 'Đăng nhập MXH',
                        'icon' => 'bx-rss',
                        'route' => 'settings.social_login',
                        'permission' => 'view_system',
                    ],
                ],
                'permissions' => [
                    'view_system',
                    'view_role',
                    'view_user',
                ]
            ],
            [
                'label' => 'Thông tin hệ thống',
                'icon' => 'bx-info-circle',
                'route' => 'system.info',
            ],
        ];
    }

    /**
     * Setting default
     *
     * @return array
     */
    public function getSettings()
    {
        return [
            'locale' => 'en',
            'time_zone' => 'Asia/Ho_Chi_Minh',
            'favicon' => null,
            'logo' => null,
            'logo_small' => null,
            'social_login_enable' => 0,
            'social_login_facebook_enable' => 0,
            'social_login_google_enable' => 0,
            'social_login_twitter_enable' => 0,
            'social_login_linkedin_enable' => 0,
            'social_login_facebook_app_id' => null,
            'social_login_facebook_app_secret' => null,
            'social_login_google_app_id' => null,
            'social_login_google_app_secret' => null,
            'social_login_twitter_app_id' => null,
            'social_login_twitter_app_secret' => null,
            'social_login_linkedin_app_id' => null,
            'social_login_linkedin_app_secret' => null,
        ];
    }

    /**
     * Get the Composer file contents as an array
     * @return array
     * @throws FileNotFoundException
     */
    public function getComposerArray()
    {
        return hwa_get_file_data(base_path('composer.json'));
    }

    /**
     * Get Installed packages & their Dependencies
     *
     * @param array $packagesArray
     * @return array
     */
    public function getPackagesAndDependencies(array $packagesArray): array
    {
        $packages = [];
        foreach ($packagesArray as $key => $value) {
            $packageFile = base_path('vendor/' . $key . '/composer.json');

            if ($key !== 'php' && File::exists($packageFile)) {
                $json2 = file_get_contents($packageFile);
                $dependenciesArray = json_decode($json2, true);
                $dependencies = array_key_exists('require', $dependenciesArray) ? $dependenciesArray['require'] : 'No dependencies';
                $devDependencies = array_key_exists('require-dev', $dependenciesArray) ? $dependenciesArray['require-dev'] : 'No dependencies';

                $packages[] = [
                    'name' => $key,
                    'version' => $value,
                    'dependencies' => $dependencies,
                    'dev-dependencies' => $devDependencies,
                ];
            }
        }

        return $packages;
    }


    /**
     * Get System environment details
     *
     * @return array
     */
    public function getSystemEnv(): array
    {
        return [
            'version' => App::version(),
            'timezone' => config('app.timezone'),
            'debug_mode' => config('app.debug'),
            'storage_dir_writable' => File::isWritable(base_path('storage')),
            'cache_dir_writable' => File::isReadable(base_path('bootstrap/cache')),
            'app_size' => hwa_human_file_size(hwaCore()->folderSize(base_path())),
        ];
    }

    /**
     * Get the system app's size
     *
     * @param $directory
     * @return int
     */
    protected function folderSize($directory): int
    {
        $size = 0;
        foreach (File::glob(rtrim($directory, '/') . '/*', GLOB_NOSORT) as $each) {
            $size += File::isFile($each) ? File::size($each) : self::folderSize($each);
        }

        return $size;
    }

    /**
     * Get PHP/Server environment details
     * @return array
     */
    public function getServerEnv(): array
    {
        return [
            'version' => phpversion(),
            'server_software' => Request::server('SERVER_SOFTWARE'),
            'server_os' => function_exists('php_uname') ? php_uname() : 'N/A',
            'database_connection_name' => config('database.default'),
            'ssl_installed' => hwaCore()->checkSslIsInstalled(),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_connection' => config('queue.default'),
            'mbstring' => extension_loaded('mbstring'),
            'openssl' => extension_loaded('openssl'),
            'curl' => extension_loaded('curl'),
            'exif' => extension_loaded('exif'),
            'pdo' => extension_loaded('pdo'),
            'fileinfo' => extension_loaded('fileinfo'),
            'tokenizer' => extension_loaded('tokenizer'),
            'imagick_or_gd' => extension_loaded('imagick') || extension_loaded('gd'),
        ];
    }

    /**
     * Check if SSL is installed or not
     * @return boolean
     */
    protected function checkSslIsInstalled(): bool
    {
        return !empty(Request::server('HTTPS')) && Request::server('HTTPS') != 'off';
    }

    /**
     * Mail driver
     *
     * @return string[]
     */
    public function getEmailDriver()
    {
        return [
            'smtp',
            'mailgun',
            'ses',
        ];
    }

    /**
     * Order status
     *
     * @return array
     */
    public function getOrderStatus()
    {
        return [
            [
                'label' => 'Đơn hàng mới',
                'value' => 'pending',
            ],
            [
                'label' => 'Đang xử lý',
                'value' => 'processing',
            ],
            [
                'label' => 'Đã hủy',
                'value' => 'cancel',
            ],
            [
                'label' => 'Hoàn thành',
                'value' => 'done',
            ],
            [
                'label' => 'Thất bại',
                'value' => 'fail',
            ],
        ];
    }

    /**
     * Permissions list
     *
     * @return string[]
     */
    public function getPermissions()
    {
        return [
            // Banner
            'view_banner',
            'add_banner',
            'edit_banner',
            'delete_banner',

            // Review
            'view_review',
            'edit_review',
            'delete_review',

            // Blog
            'view_blog',
            'add_blog',
            'edit_blog',
            'delete_blog',

            // Customer
            'view_customer',
            'edit_customer',
            'delete_customer',
            'delete_address_customer',

            // Newsletter
            'view_newsletter',
            'edit_newsletter',
            'delete_newsletter',

            // Contact
            'view_contact',
            'edit_contact',
            'delete_contact',

            // Supplier
            'view_supplier',
            'add_supplier',
            'edit_supplier',
            'delete_supplier',

            // Category
            'view_category',
            'add_category',
            'edit_category',
            'delete_category',

            // Brand
            'view_brand',
            'add_brand',
            'edit_brand',
            'delete_brand',

            // Product
            'view_product',
            'add_product',
            'edit_product',
            'delete_product',

            // Order
            'view_order',
            'edit_order',
            'delete_order',

            // Role
            'view_role',
            'add_role',
            'edit_role',
            'delete_role',

            // User
            'view_user',
            'add_user',
            'edit_user',
            'delete_user',

            // Faqs
            'view_faq',
            'add_faq',
            'edit_faq',
            'delete_faq',

            // Page
            'view_page',
            'update_page',

            // System
            'view_system',
            'update_system',
        ];
    }

    /**
     * Config Permission input
     *
     * @return array[]
     */
    public function configPermissionInput()
    {
        $full_permission = [
            [
                'key' => 'view',
                'label' => 'Xem',
            ],
            [
                'key' => 'add',
                'label' => 'Thêm',
            ],
            [
                'key' => 'edit',
                'label' => 'Sửa',
            ],
            [
                'key' => 'delete',
                'label' => 'Xóa',
            ],
        ];

        $update_permission  = [
            [
                'key' => 'view',
                'label' => 'Xem',
            ],
            [
                'key' => 'edit',
                'label' => 'Sửa',
            ],
            [
                'key' => 'delete',
                'label' => 'Xóa',
            ],
        ];

        return [
            [
                'key' => 'banner',
                'label' => 'Banner',
                'permissions' => $full_permission,
            ],
            [
                'key' => 'review',
                'label' => 'Đánh giá',
                'permissions' => $update_permission,
            ],
            [
                'key' => 'blog',
                'label' => 'Tin tức',
                'permissions' => $full_permission
            ],
            [
                'key' => 'customer',
                'label' => 'Khách hàng',
                'permissions' => array_merge($update_permission, [
                    [
                        'key' => 'delete_address',
                        'label' => 'Xóa địa chỉ',
                    ]
                ]),
            ],
            [
                'key' => 'newsletter',
                'label' => 'Newsletter',
                'permissions' => $update_permission,
            ],
            [
                'key' => 'contact',
                'label' => 'Liên hệ',
                'permissions' => $update_permission,
            ],
            [
                'key' => 'supplier',
                'label' => 'Nhà cung cấp',
                'permissions' => $full_permission
            ],
            [
                'key' => 'category',
                'label' => 'Danh mục',
                'permissions' => $full_permission
            ],
            [
                'key' => 'brand',
                'label' => 'Thương hiệu',
                'permissions' => $full_permission
            ],
            [
                'key' => 'product',
                'label' => 'Sản phẩm',
                'permissions' => $full_permission
            ],
            [
                'key' => 'order',
                'label' => 'Đơn hàng',
                'permissions' => $update_permission,
            ],
            [
                'key' => 'user',
                'label' => 'Quản trị viên',
                'permissions' => $full_permission
            ],
            [
                'key' => 'role',
                'label' => 'Chức vụ',
                'permissions' => $full_permission
            ],
            [
                'key' => 'faq',
                'label' => 'FAQs',
                'permissions' => $full_permission
            ],
            [
                'key' => 'page',
                'label' => 'Trang tĩnh',
                'permissions' => [
                    [
                        'key' => 'view',
                        'label' => 'Xem',
                    ],
                    [
                        'key' => 'update',
                        'label' => 'Sửa',
                    ],
                ]
            ],
            [
                'key' => 'system',
                'label' => 'Quản lý hệ thống',
                'permissions' => [
                    [
                        'key' => 'view',
                        'label' => 'Xem',
                    ],
                    [
                        'key' => 'update',
                        'label' => 'Sửa',
                    ],
                ]
            ],
        ];
    }
}
