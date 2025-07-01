<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\News;
use App\Models\Newsletter;
use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Message;

class HomeController extends Controller
{
    /**
     * @var Newsletter
     */
    protected $newsletter;

    /**
     * @var Brand
     */
    protected $brand;

    /**
     * @var Banner
     */
    protected $banner;

    /**
     * @var Product
     */
    protected $product;

    /**
     * @var News
     */
    protected $news;

    /**
     * HomeController constructor.
     * @param Newsletter $newsletter
     * @param Brand $brand
     * @param Banner $banner
     * @param Product $product
     * @param News $news
     */
    public function __construct(Newsletter $newsletter, Brand $brand, Banner $banner, Product $product, News $news)
    {
        $this->newsletter = $newsletter;
        $this->banner = $banner;
        $this->brand = $brand;
        $this->product = $product;
        $this->news = $news;
    }

    /**
     * Homepage
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $banners = $this->banner->whereActive(1)->whereBannerType('banner')->select(['id', 'title', 'sub_title', 'image', 'url', 'target'])->get()->toArray();
        $brands = $this->brand->whereActive(1)->select(['id', 'slug', 'name', 'images'])->get();

        $storeId = auth()->user()->store_id ?? null; // Assuming user has store_id attribute
        
        $product_new = $this->product->whereActive('published');
        $product_sale = $this->product->whereActive('published');
        $product_trending = $this->product->whereActive('published');

        if ($storeId) {
            $product_new->where('store_id', $storeId);
            $product_sale->where('store_id', $storeId);
            $product_trending->where('store_id', $storeId);
        }

        $product_new = $product_new->orderBy('sale', 'desc')->take(8)->get();
        $product_sale = $product_sale->orderBy('sale', 'desc')->take(8)->get();
        $product_trending = $product_trending->orderBy('sale', 'desc')->take(8)->get();
       
    
        $news = $this->news->whereActive('published')->orderBy('id', 'desc')->take(3)->get();
        $messages = Message::latest()->take(50)->get()->reverse();

        return view('client.home')->with([
            'banners' => $banners,
            'brands' => $brands,
            'product_new' => $product_new,
            'product_sale' => $product_sale,
            'product_trending' => $product_trending,
            'messages' => $messages,
            'news' => $news,
        ]);
    }

    public function show()
    {
        $messages = Message::latest()->take(50)->get()->reverse();
        return view('client.chat.index', compact('messages'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'message' => 'required',
        ]);

        Message::create($request->only('name', 'message'));

        return response()->json(['success' => true]);
    }

    public function fetch()
    {
        $messages = Message::latest()->take(50)->get()->reverse();
        return response()->json($messages);
    }

    /**
     * Register newsletter
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function newsletter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'unique:newsletters,email']
        ], [
            'email.required' => 'Email là trường bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã đăng ký trước đó.',
        ]);

        if ($validator->fails()) {
            hwa_notify_error($validator->getMessageBag()->first());
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $this->newsletter->create([
                'email' => strtolower($request['email'])
            ]);
            hwa_notify_success("Đăng ký nhận bản tin thành công.");
            return redirect()->back();
        }
    }
}
