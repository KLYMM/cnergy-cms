<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsCollection;
use App\Http\Utils\CacheStorage;
use App\Http\Resources\IndexNewsResource;
use App\Models\News;
use App\Models\User;
use App\Models\NewsPagination;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class NewsController extends Controller
{
    /**
     * Get News
     * @OA\Get (
     *     tags={"News"},
     *     path="/api/news/",
     *     security={{"Authentication_Token":{}}},
     *     @OA\Parameter(
     *         in="query",
     *         name="limit",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="category",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="headline",
     *         @OA\Schema(type="string", enum = {1, 0})
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="max_id",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="editorpick",
     *         @OA\Schema(type="string", enum = {1, 0})
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="alltype",
     *         @OA\Schema(type="string", enum = {1, 0})
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="orderby",
     *         @OA\Schema(type="string", enum = {"asc", "desc"})
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="published",
     *         @OA\Schema(type="string", enum = {1, 0})
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="sensitive",
     *         @OA\Schema(type="string", enum = {1, 0})
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="last_update",
     *         @OA\Schema(type="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="bad request",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="unauthorized",
     *       @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The security token is invalid"),
     *          )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $news = News::with(['categories', 'tags', 'users', 'news_paginations', 'keywords'])
        ->where('types','=','news')
        ->where('is_published','=','1');

        $order = $request->get('orderby');
        if($order){
            $data = explode("-" , $order);
            if ($data[0] == 'news_date_publish') {
                $news->OrderBy('published_at', $data[1]);
            }
            if ($data[0] == 'news_entry') {
                $news->OrderBy('created_at', $data[1]);
            }
            if ($data[0] == 'news_last_update') {
                $news->OrderBy('updated_at', $data[1]);
            }
        }else{
            $news->latest('published_at');
        }

        $limit = $request->get('limit', 10);
        if($limit > 10){
            $limit = 10;
        }

        if($request->get("headline")){
            $news->where('is_headline', '=', $request->get('headline', ''));
        }

        if($request->get("category")){
            $news->where('category_id', '=', $request->get('category', ''));
        }

        if($request->get("max_id")){
            $news->where('id', '<', $request->get('max_id', ''));
        }

        if($request->get("editorpick")){
            $news->where('editor_pick', '=', $request->get('editorpick', ''));
        }

        $alltype = $request->get('alltype', 1);
        if($alltype == 0){
            $news->where('types', '=', "news");
        }

        $published = $request->get('published', 1);
        if($published == 0){
            $news->where('is_published', '=', "0");
        }

        if($request->get("sensitive")){
            $news->where('is_verify_age', '=', $request->get('sensitive', ''));
        }

        if ($request->get('last_update')) {
            $last_update = Carbon::parse(($request->get('last_update')))
                ->toDateTimeString();
            $news->where('updated_at', '>=', $last_update);
        }

        if(!Cache::has("newsCache")){
            CacheStorage::cache("newsCache", 'news');
            Cache::put("newsCache", new NewsCollection($news->paginate($limit)->withQueryString()), now()->addMinutes(10));
        }

        return response()->json(Cache::get("newsCache"));
    }

    /**
     * Get News By ID
     * @OA\Get (
     *     tags={"News"},
     *     path="/api/news/{id}/",
     *     security={{"Authentication_Token":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *     ),
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="bad request",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="unauthorized",
     *       @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The security token is invalid"),
     *          )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="not found",
     *       @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="ID Not Found"),
     *          )
     *     )
     * )
     */
    public function show($id){
        $filterId = News::with(['users'])
        ->where('types','=','news')
        ->where('id', $id)
        ->where('is_published','=','1')
        ->first();

        if ($filterId == null){
            return response()->json(['message'=>'News Not Found'], Response::HTTP_NOT_FOUND);
        }

        $cacheKey = "newsDetail-$id";
        if(!Cache::has($cacheKey)){
            CacheStorage::cache($cacheKey, 'news');
            Cache::put($cacheKey, new IndexNewsResource($filterId), now()->addDay());
        }

        return response()->json(Cache::get($cacheKey), Response::HTTP_OK);
    }
}
