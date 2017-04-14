<?php

namespace App\Http\Controllers\Frontend;

use App\Repositories\Frontend\Access\Tag\TagRepository;
use App\Tag;
use Illuminate\Http\Request;
use XblogConfig;

use App\Http\Controllers\Controller;

class TagController extends Controller
{
    public $tagRepository;

    /**
     * TagController constructor.
     * @param TagRepository $tagRepository
     */
    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function index()
    {
        return view('tag.index');
    }

    public function show($name)
    {
        $tag = $this->tagRepository->get($name);
        $page_size = $page_size = XblogConfig::getValue('page_size', 7);

        $posts = $this->tagRepository->pagedPostsByTag($tag, $page_size);
        return view('tag.show', compact('posts', 'name'));
    }
}
