<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Http\Resources\TagCollection;
use App\Http\Utils\CacheStorage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\IndexTagResource;

class TagsController extends Controller
{
    /**
     * Get Tag
     * @OA\Get (
     *     tags={"Tag"},
     *     path="/api/tag/",
     *     security={{"Authentication_Token":{}}},
     *     @OA\Parameter(
     *         in="query",
     *         name="limit",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="slug",
     *         @OA\Schema(type="string")
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
        $tag = Tag::latest();

        if($request->get("slug")){
            $tag->where('slug', '=', $request->get('slug', ''));
        }

        $limit = $request->get('limit', 20);
        if($limit > 20){
            $limit = 20;
        }

        if(!Cache::has("tagsCache")){
            CacheStorage::cache("tagsCache", 'tag');
            Cache::put("tagsCache", new TagCollection($tag->paginate($limit)->withQueryString()), now()->addMinutes(10));
        }

        return response()->json(Cache::get("tagsCache"));
    }

    /**
     * Get Tag By ID
     * @OA\Get (
     *     tags={"Tag"},
     *     path="/api/tag/{id}/",
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
        $filterId = Tag::whereNull('deleted_at')
        ->where('id', $id)
        ->first();

        if ($filterId == null){
            return response()->json(['message'=>'Tag Not Found'], Response::HTTP_NOT_FOUND);
        }

        $cacheKey = "tagDetail-$id";
        if(!Cache::has($cacheKey)){
            CacheStorage::cache($cacheKey, 'tag');
            Cache::put($cacheKey, new IndexTagResource($filterId), now()->addDay());
        }

        return response()->json(Cache::get($cacheKey), Response::HTTP_OK);
    }
}
