<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Log;
use App\Models\News;
use App\Models\Tag;
use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\NewsRequest;
use App\Http\Utils\FileFormatPath;
use App\Models\NewsPagination;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $news = News::with(['categories', 'tags']);
        $editors = User::join('roles', 'users.role_id', '=', 'roles.id')->where('roles.role', "Editor");
        $reporters = User::join('roles', 'users.role_id', '=', 'roles.id')->where('roles.role', "Reporter");
        $photographers = User::join('roles','users.role_id','=','roles.id')->where('roles.role', "Photographer");

        if ($request->get('published')) {
            $published = $request->get('published');
            if ($published == 2) {
                $news->where('is_published', "0");
            } else {
                $news->where('is_published', "1");
            }
        }

        if ($request->get('inputTitle')) {
            $news->where('title', 'like', '%' . $request->inputTitle . '%');
        }

        if ($request->get('inputCategory')) {
            $news->whereHas('categories', function ($query) use ($request) {
                $query->where('category', 'like', "%{$request->get('inputCategory')}%");
            });
        }

        if ($request->get('headline')) {
            $headline = $request->get('headline');
            if ($headline == 2) {
                $news->where('is_headline', "0");
            } else {
                $news->where('is_headline', "1");
            }
        }

        if ($request->get('inputTag')) {
            $news->whereHas('tags', function ($query) use ($request) {
                $query->where('tags', 'like', "%{$request->get('inputTag')}%");
            });
        }

        if ($request->get('startDate') && $request->get('endDate')) {
            $startDate = Carbon::parse(($request->get('startDate')))
                ->toDateTimeString();

            $endDate = Carbon::parse($request->get('endDate'))
                ->toDateTimeString();
            $news->whereBetween('created_at', [
                $startDate, $endDate
            ]);
        }

        if ($request->get('editor')) {
            $editor = $request->editor;
            $news->where('updated_by', $editor);
        }

        if ($request->get('reporter')) {
            $reporter = $request->reporter;
            $news->whereJsonContains('reporters',$reporter);
        }

        if ($request->get('photographer')) {
            $photographer = $request->photographer;
            $news->whereJsonContains('photographers',$photographer);
        }

        // return response()->json($news);
        $method = explode('/', URL::current());
        return view('news.index', [
            'type' => end($method),
            'news' => $news->paginate(10)->withQueryString(),
            'editors' => $editors->get(),
            'reporters' => $reporters->get(),
            'photographers'=>$photographers->get()
            // 'categories' => Category::whereNull("parent_id"),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $method = explode('/', URL::current());
        $users = User::with(['roles'])->get();
        $categories = Category::all();
        $tags = Tag::all();
        $types = ['news', 'photonews', 'video'];
//        return response()->json($users);
        return view('news.editable', [
            'method' => end($method),
            'categories' => $categories,
            'types' => $types,
            'users' => $users,
            'tags' => $tags,
            'contributors'=>[]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->input();

        $news_paginations = array();
        
        try {
            for ($i = 0; $i < count($data['title']) - 1; $i++) {
                $news_paginations[$i] = [
                    'title' => $data['title'][$i + 1],
                    // 'synopsis' => $data['synopsis'][$i + 1],
                    'content' => $data['content'][$i + 1],
                    'order_by_no' => $i
                ];
            }

            if ($request->file('upload_image') && !$data['upload_image_selected']) {
                $file = $request->file('upload_image');
                $fileFormatPath = new FileFormatPath($data['types'], $file);
                $data['image'] = $fileFormatPath->storeFile();
            }

            if ($data['upload_image_selected'] && !$request->file('upload_image')) {
                $data['image'] = explode(Storage::url(""), $data['upload_image_selected'])[1];
            }

            // return $news_paginations;

            $news = new News([
                'is_headline' => $request->has('isHeadline') == false ? '0' : '1',
                'is_home_headline' => $request->has('isHomeHeadline') == false ? '0' : '1',
                'is_category_headline' => $request->has('isCategoryHeadline') == false ? '0' : '1',
                'editor_pick' => $request->has('editorPick') == false ? '0' : '1',
                'is_curated' => $request->has('isCurated') == false ? '0' : '1',
                'is_adult_content' => $request->has('isAdultContent') == false ? '0' : '1',
                'is_verify_age' => $request->has('isVerifyAge') == false ? '0' : '1',
                'is_advertorial' => $request->has('isAdvertorial') == false ? '0' : '1',
                'is_seo' => $request->has('isSeo') == false ? '0' : '1',
                'is_disable_interactions' => $request->has('isDisableInteractions') == false ? '0' : '1',
                'is_branded_content' => $request->has('isBrandedContent') == false ? '0' : '1',
                'title' => $data['title'][0],
                'slug' => Str::slug($data['title'][0]),
                'content' => $data['content'],
                'synopsis' => $data['synopsis'][0],
                'description' => $data['description'],
                'types' => $data['types'],
                'keywords' => $data['keywords'],
                'photographers'=>$request->has('photographers') == false ? null :json_encode($data['photographers']),
                'reporters'=>$request->has('reporters') == false ? null :json_encode($data['reporters']),
                'image' => $data['image'] ?? null,
                'is_published' => $data['isPublished'],
                'published_at' => $request->has('isPublished') == false ? null : $data['publishedAt'],
                'published_by' => $request->has('isPublished') == false ? null : auth()->id(),
                'created_by' => auth()->id(),
                'category_id' => $data['category'],
                'video' => $data['video'] ?? null
            ]);
            if ($news->save()) {
                $log = new Log(
                    [
                        'news_id' => $news->id,
                        'updated_by' => \auth()->id(),
                        'updated_content' => json_encode($news->getOriginal())
                    ]
                );
                $log->save();
            }
            foreach ($data['tags'] as $t) {
                $news->tags()->attach($t);
            }

            if(count($news_paginations)){
                foreach ($news_paginations as $news_page) {
                    NewsPagination::create([
                        'title' => $news_page['title'],
                        'content' => $news_page['content'],
                        'order_by_no' => $news_page['order_by_no'],
                        'news_id' => $news->id,
                    ]);
                }
            }

            return \redirect()->route('news.index')->with('status', 'Successfully Add New News');
        } catch (\Throwable $e) {
            return Redirect::back()->withErrors($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $method = explode('/', URL::current());
        $news = News::where('id', $id)->with(['users', 'news_paginations'])->first();
        $categories = Category::all();
        $tags = Tag::all();
        $types = ['news', 'photonews', 'video'];
        $contributors = $news->users;
        $users = User::with(['roles'])->get();
        return view('news.editable', [
            'method' => end($method),
            'categories' => $categories,
            'types' => $types,
            'news' => $news,
            'tags' => $tags,
            'contributors'=>$contributors,
            'users'=>$users
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->input();
        $newsById = News::find($id);
        try {
            $input = [
                'is_headline' => $request->has('isHeadline') == false ? '0' : '1',
                'is_home_headline' => $request->has('isHomeHeadline') == false ? '0' : '1',
                'is_category_headline' => $request->has('isCategoryHeadline') == false ? '0' : '1',
                'editor_pick' => $request->has('editorPick') == false ? '0' : '1',
                'is_curated' => $request->has('isCurated') == false ? '0' : '1',
                'is_adult_content' => $request->has('isAdultContent') == false ? '0' : '1',
                'is_verify_age' => $request->has('isVerifyAge') == false ? '0' : '1',
                'is_advertorial' => $request->has('isAdvertorial') == false ? '0' : '1',
                'is_seo' => $request->has('isSeo') == false ? '0' : '1',
                'is_disable_interactions' => $request->has('isDisableInteractions') == false ? '0' : '1',
                'is_branded_content' => $request->has('isBrandedContent') == false ? '0' : '1',
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'content' => $data['content'],
                'synopsis' => $data['synopsis'],
                'description' => $data['description'],
                'types' => $data['types'],
                'keywords' => $data['keywords'],
                'photographers'=>$request->has('photographers') == false ? null :json_encode($data['photographers']),
                'reporters'=>$request->has('reporters') == false ? null :json_encode($data['reporters']),
                'is_published' => $data['isPublished'],
                'published_at' => $request->has('isPublished') == false ? null : $data['publishedAt'],
                'published_by' => $request->has('isPublished') == false ? null : auth()->id(),
                'updated_by' => auth()->id(),
                'category_id' => $data['category'],
                'video' => $data['video'] ?? null
            ];

            if ($request->file('upload_image') && !$data['upload_image_selected']) {
                $file = $request->file('upload_image');
                $fileFormatPath = new FileFormatPath($data['types'], $file);
                $input['image'] = $fileFormatPath->storeFile();
            }

            if ($data['upload_image_selected'] && !$request->file('upload_image')) {
                $input['image'] = explode(Storage::url(""), $data['upload_image_selected'])[1];
            }
            $newsById->update($input);
            $newsById::find($id)->tags()->detach();
            foreach ($data['tags'] as $t) {
                $newsById->tags()->attach($t);
            }

            $log = new Log(
                [
                    'news_id' => $id,
                    'updated_by' => \auth()->id(),
                    'updated_content' => json_encode($newsById->getChanges())
                ]
            );
            $log->save();

            return \redirect()->route('news.index')->with('status', 'Successfully Update News');
        } catch (\Throwable $e) {
            return Redirect::back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            News::where('id', $id)->update([
                'deleted_by' => Auth::user()->uuid,
            ]);
            if (News::destroy($id)) {
                $log = new Log(
                    [
                        'news_id' => $id,
                        'updated_by' => \auth()->id(),
                        'updated_content' => json_encode('{}')
                    ]
                );
                $log->save();
            }
            return Redirect::back()->with('status', 'Successfully to Delete News');
        } catch (\Throwable $e) {
            return Redirect::back()->withErrors($e->getMessage());
        }
    }
}
