<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsCollection;
use App\Models\News;
use App\Models\User;
use App\Models\NewsPagination;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    /**
     * Get News
     * @OA\Get (
     *     tags={"News"},
     *     path="/api/news/",
     *     security={{"Authentication_Token":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="success",
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
        $news = News::with(['categories', 'tags', 'users', 'news_paginations'])
        ->where('is_published','=','1')
        ->where('published_at','<=',now());

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

        

        return response()->json(new NewsCollection($news->paginate($limit)->withQueryString()));

    }

}
