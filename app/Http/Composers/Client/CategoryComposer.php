<?php

namespace App\Http\Composers\Client;

use App\Models\Category;
use Illuminate\View\View;

class CategoryComposer
{
    public $category;

    /**
     * AdminComposer constructor.
     */
    public function __construct()
    {
        $this->category = Category::whereNull('parent_id')
            ->with(['childCategories'])
            ->select(['id', 'slug', 'name', 'active', 'parent_id'])
            ->get();
    }

    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with('global_categories', $this->category); // Share data to view
    }

}
