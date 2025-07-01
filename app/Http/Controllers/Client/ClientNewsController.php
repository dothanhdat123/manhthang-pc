<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ClientNewsController extends Controller
{
    /**
     * @var string View path
     */
    protected $viewPath = 'client.news';

    /**
     * @var News
     */
    protected $news;

    /**
     * ClientNewsController constructor.
     * @param News $news
     */
    public function __construct(News $news)
    {
        $this->news = $news;
    }

    /**
     * Get list news
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $path = $this->viewPath;
        $results = $this->news->whereActive('published')->paginate(12);

        return view("{$path}.index")->with([
            'results' => $results
        ]);
    }

    /**
     * Show special news
     *
     * @param $slug
     * @return Application|Factory|View
     */
    public function show($slug)
    {
        if (!$result = $this->news->whereActive('published')->whereSlug($slug)->first()) {
            abort(404);
        } else {
            $result->views += 1;
            $result->save();

            $relatePosts = $this->news->whereNotIn('id', [$result['id']])
                ->whereActive('published')
                ->whereCategoryId($result['category_id'])
                ->orderBy('id', 'desc')
                ->take(2)
                ->get();

            return view("{$this->viewPath}.show")->with([
                'result' => $result,
                'relatePosts' => $relatePosts,
            ]);
        }
    }
}
