<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * @var Category
     */
    protected $category;

    /**
     * @var Product
     */
    protected $product;

    public function __construct(Category $category, Product $product)
    {
        $this->category = $category;
        $this->product = $product;
    }

    /**
     * Search result
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $key = $request['q'];
        $categories = $this->category->whereActive(1)
            ->orderBy('id', 'asc')
            ->select(['id', 'slug', 'name', 'active', 'parent_id'])
            ->get();

        $products = $this->product->whereActive('published')
            ->where('name', 'LIKE', "%{$key}%")
            ->orderBy('id', 'desc')
            ->select(['id', 'name', 'slug', 'thumb', 'price', 'description'])
            ->paginate(12);

        return view("client.search")->with([
            'result' => [
                'keyword' => $key
            ],
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
