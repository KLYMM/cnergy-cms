<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Http\Services\NewsServices;
use App\Models\Menu;
use App\Models\Role;
use App\Models\Keywords;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use App\Models\News;
use App\Models\User;
use App\Models\Log;
use App\Models\Tag;
use App\Models\Category;
use App\Models\PhotoNews;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Utils\FileFormatPath;
use App\Models\ImageBank;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller implements NewsServices
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $news = News::with(['categories', 'tags'])->where('types', '=', 'photonews')->latest();
        $editors = User::join('roles', 'users.role_id', '=', 'roles.id')->where('roles.role', "Editor");
        $reporters = User::join('roles', 'users.role_id', '=', 'roles.id')->where('roles.role', "Reporter");
        $photographers = User::join('roles', 'users.role_id', '=', 'roles.id')->where('roles.role', "Photographer");

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

        // if ($request->get('editor')) {
        //     $editor = $request->editor;
        //     $news->whereJsonContains('contributors', $editor);
        // }

        // if ($request->get('reporter')) {
        //     $reporter = $request->reporter;
        //     $news->whereJsonContains('reporters',$reporter);
        // }

        // if ($request->get('photographer')) {
        //     $photographer = $request->photographer;
        //     $news->whereJsonContains('photographers',$photographer);
        // }

        // return response()->json($news);

        $method = explode('/', URL::current());
        $photoRole = Menu::where('menu_name', '=', 'Photo')->with(['childMenusRoles', 'roles_user'])->first();
        $newsRole = [];
        foreach ($photoRole->childMenusRoles as $r) {
            array_push($newsRole, $r->menu_name);
        }

        return view('news.index', [
            'type' => end($method),
            'news' => $news->orderBy("id", "DESC")->paginate(10)->withQueryString(),
            'editors' => $editors->get(),
            'reporters' => $reporters->get(),
            'photographers' => $photographers->get(),
            'newsRole' => $newsRole
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
        $users = User::all();
        $categories = Category::whereNull('deleted_at')
            ->where('is_active', '=', '1')
            ->whereJsonContains('types', 'photonews')->get();
        //        $tags = Tag::all();
        $types = 'photonews';
        $date = date('Y-m-d');
        $time = time();
        return view('news.photonews.editable', [
            'method' => end($method),
            'categories' => $categories,
            'types' => $types,
            'users' => $users,
            //            'tags' => $tags,
            'contributors' => []
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
        // return $data;
        $date = $data['date'];
        $time = $data['time'];
        $mergeDate = date('Y-m-d H:i:s', strtotime("$date $time"));
        $keyArr[] = null;
        try {
            if ($data['keywords'] != null) {
                foreach ($data['keywords'] as $t) {
                    if (is_numeric($t)) {
                        $keyArr[] =  $t;
                    } else {
                        $newKeyword = Keywords::create([
                            'keywords' => $t,
                            'created_at' => now(),
                            'created_by' => Auth::user()->uuid,
                        ]);
                        $keyArr[] = $newKeyword->id;
                    }
                }
            }

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
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'content' => $data['content'],
                'synopsis' => $data['synopsis'],
                'description' => $data['description'],
                'types' => 'photonews',
                //'keywords' => $data['keywords'],
                'photographers' => $request->has('photographers') == false ? null : json_encode($data['photographers']),
                'reporters' => $request->has('reporters') == false ? null : json_encode($data['reporters']),
                'contributors' => $request->has('contributors') == false ? null : json_encode($data['contributors']),
                'image' => explode(Storage::url(""), $data['upload_image_selected'])[1] ?? null,
                'is_published' => $data['isPublished'],
                'published_at' => $mergeDate,
                'published_by' => $request->has('isPublished') == false ? null : auth()->id(),
                'created_by' => auth()->id(),
                'category_id' => $data['category']
            ]);


            if ($news->save()) {
                $log = new Log(
                    [
                        'news_id' => $news->id,
                        'updated_at' => now(),
                        'updated_by' => \auth()->id(),
                        'updated_content' => json_encode($news->getOriginal())
                    ]
                );
                $log->save();
            }

            if ($request->has('tags')) {
                foreach ($data['tags'] as $t) {
                    if (!is_numeric($t)) {
                        $checkId = Tag::where('tags', $t)->first('id');
                        $news->tags()->attach($checkId, ['created_by' => auth()->id()]);
                    } else {
                        $news->tags()->attach($t, ['created_by' => auth()->id()]);
                    }
                }
            }

            if ($keyArr != null) {
                foreach ($keyArr as $k) {
                    $news->keywords()->attach($k, ['created_by' => auth()->id()]);
                }
            }

            $index = 1;
            if (isset($data['photonews'])) {
                if (count($data['photonews'])) {
                    foreach ($data['photonews'] as $photonews) {
                        PhotoNews::create([
                            'title' => $news->title,
                            'image' => $photonews['caption'],
                            'url' => $photonews['url'],
                            'copyright' => $photonews['copyright'],
                            'description' => $photonews['description'],
                            'keywords' => $photonews['keywords'],
                            'order_by_no' => $index,
                            'news_id' => $news->id,
                            'created_by' => auth()->id(),
                            'is_active' => "1",
                            'photo_id' => $photonews['id'],
                        ]);
                        $index++;
                    }
                }
            }

            return \redirect()->route('photo.index')->with('status', 'Successfully Create PhotoNews');
        } catch (\Throwable $e) {
            return Redirect::back()->withErrors($e->getMessage());
        }
    }

    function deleteNewsImages(Request $request)
    {
        try {
            $id = $request->id;
            PhotoNews::where('id', $id)->update([
                'deleted_by' => Auth::user()->uuid,
                'is_active' => '0',
            ]);
            PhotoNews::destroy($id);
            return response()->json([
                'message' => 'successfully delete photonews',
                'id' => $id,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json($e->getMessage());
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
        $news = News::where('id', $id)->with(['users', 'news_photo'])->first();
        $categories = Category::whereNull('deleted_at')
            ->where('is_active', '=', '1')
            ->whereJsonContains('types', 'photonews')->get();
        //        $tags = Tag::all();
        $keywords = Keywords::all();
        $types = 'photonews';
        $contributors = $news->users;
        $users = User::with(['roles'])->get();
        return view('news.photonews.editable', [
            'method' => end($method),
            'categories' => $categories,
            'types' => $types,
            'news' => $news,
            //            'tags' => $tags,
            'keywords' => $keywords,
            'contributors' => $contributors,
            'users' => $users
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
        // $new = array();
        // $old = array();
        $news = News::where('id', $id)->with(['users', 'news_photo'])->first();
        $i = 0;
        // foreach ($news->news_photo as $item) {
        //     $old[$i] = $item->id;
        //     $i++;
        // }

        //return $id_image[];
        $news_images_old = array();
        $i = 0;
        if (isset($data['photonews']['old']) && count($data['photonews']['old']) >= 1) {
            foreach ($data['photonews']['old'] as $key => $value) {
                if ($key !== "old") {
                    $news_images_old[$i] = [
                        'title' => $data['title'],
                        'image' => $data['photonews']['old'][$key]['caption'],
                        'url' => explode(Storage::url(""), $data['photonews']['old'][$key]['url'])[1] ?? null,
                        'copyright' => $data['photonews']['old'][$key]['copyright'],
                        'description' => $data['photonews']['old'][$key]['description'],
                        'keywords' => $data['photonews']['old'][$key]['keywords'],
                        'order_by_no' => $i+1,
                        'is_active' => $data['photonews']['old'][$key]['is_active'],
                        'created_by' => $data['photonews']['old'][$key]['created_by'],
                        'updated_by' => auth()->id(),
                        'news_id' => $id,
                        'id' => $key,
                        'photo_id' => $data['photonews']['old'][$key]['photo_id'],
                    ];

                    $new[$i] = $key;
                    $i++;
                }
                // }
            }

            // return $old;

            if (count($data['photonews']) >= 1) {
                foreach ($data['photonews'] as $key => $value) {
                    if ($key === "old") {
                        continue;
                    }
                    $news_images_old[$i] = [
                        'title' => $data['title'],
                        'image' => $data['photonews'][$key]['caption'],
                        'url' => explode(Storage::url(""), $data['photonews'][$key]['url'])[0] ?? null,
                        'copyright' => $data['photonews'][$key]['copyright'],
                        'description' => $data['photonews'][$key]['description'],
                        'keywords' => $data['photonews'][$key]['keywords'],
                        'order_by_no' => $i+1,
                        'created_by' => auth()->id(),
                        'updated_by' => null,
                        'is_active' => "1",
                        'news_id' => $id,
                        'id' => null,
                        'photo_id' => $data['photonews'][$key]['id'] ?? null,
                    ];
                    $i++;
                }
            }

            // return response()->json($news_images_old);
            // if (count($new) !== count($old)) {
            //     $diff = array_diff($old, $new);
            //     $i = 0;
            //     if (count($diff) > 0) {
            //         foreach ($diff as $value) {
            //             if ($value !== null) {
            //                 $x[$i] = $value;
            //                 $i++;
            //             }
            //         }
            //     }
            //     // $this->deleteNewsImages($x);
            // }
        }

        //return $diff;

        $newsById = News::find($id);
        $date = $data['date'];
        $time = $data['time'];
        $margeDate = date('Y-m-d H:i:s', strtotime("$date $time"));
        $keyArr[] = null;
        try {
            if ($data['keywords'] != null) {
                foreach ($data['keywords'] as $t) {
                    if (is_numeric($t)) {
                        $keyArr[] =  $t;
                    } else {
                        $newKeyword = Keywords::create([
                            'keywords' => $t,
                            'created_at' => now(),
                            'created_by' => Auth::user()->uuid,
                        ]);
                        $keyArr[] = $newKeyword->id;
                    }
                }
            }

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
                // 'slug' => Str::slug($data['title']),
                'content' => $data['content'],
                'synopsis' => $data['synopsis'],
                'description' => $data['description'],
                'types' => 'photonews',
                //'keywords' => $data['keywords'],
                'image' => explode(Storage::url(""), $data['upload_image_selected'])[1] ?? null,
                'photographers' => $request->has('photographers') == false ? null : json_encode($data['photographers']),
                'reporters' => $request->has('reporters') == false ? null : json_encode($data['reporters']),
                'contributors' => $request->has('contributors') == false ? null : json_encode($data['contributors']),
                'is_published' => $data['isPublished'],
                'published_at' => $margeDate,
                'published_by' => $request->has('isPublished') == false ? null : auth()->id(),
                'updated_by' => auth()->id(),
                'category_id' => $data['category'],
                // 'video' => $data['video'] ?? null
            ];

            $newsById->update($input);

            if ($request->has('tags')) {
                $newsById::find($id)->tags()->detach();
                foreach ($data['tags'] as $t) {
                    if (!is_numeric($t)) {
                        $checkId = Tag::where('tags', $t)->first('id');
                        $newsById->tags()->attach($checkId, ['created_by' => auth()->id()]);
                    } else {
                        $newsById->tags()->attach($t, ['created_by' => auth()->id()]);
                    }
                }
            }

            if ($keyArr != null) {
                $newsById::find($id)->keywords()->detach();
                foreach ($keyArr as $k) {
                    $newsById->keywords()->attach($k, ['created_by' => auth()->id()]);
                }
            }

            PhotoNews::upsert($news_images_old, ['id'], ['title', 'is_active', 'url', 'image', 'description', 'keywords', 'copyright', 'order_by_no', 'created_by', 'news_id', 'updated_by']);

            $log = new Log(
                [
                    'news_id' => $id,
                    'updated_at' => now(),
                    'updated_by' => \auth()->id(),
                    'updated_content' => json_encode($newsById->getChanges())
                ]
            );
            $log->save();

            return \redirect()->route('photo.index')->with('status', 'Successfully PhotoUpdate News');
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
                        'updated_at' => now(),
                        'updated_content' => json_encode('DELETED')
                    ]
                );
                $log->save();
            }
            return Redirect::back()->with('status', 'Successfully Delete Photo News');
        } catch (\Throwable $e) {
            return Redirect::back()->withErrors($e->getMessage());
        }
    }
}
