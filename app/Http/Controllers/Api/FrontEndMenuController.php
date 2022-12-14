<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FrontEndMenu;
use App\Http\Resources\FrontEndMenuCollection;
use App\Http\Utils\CacheStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FrontEndMenuController extends Controller
{
    /**
     * Get Front End Menu
     * @OA\Get (
     *     tags={"Front End Menu"},
     *     path="/api/setting-fe-menu/",
     *     security={{"Authentication_Token":{}}},
     *     @OA\Parameter(
     *         in="query",
     *         name="limit",
     *         @OA\Schema(type="int")
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="nested",
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="position",
     *         @OA\Schema(type="string", enum = {"header", "footer"})
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
        /*$fe_menus = FrontEndMenu::latest()
        ->whereNull('parent_id')
        ->with(["childMenus" => function($query){
            $query->orderBy('order','asc');
        }])
        ->where('slug', 'like', '%' . $request->get('slug', '') . '%')
        ->where('position', 'like', '%' . $request->get('position', '') . '%')
        ->paginate($request->get('limit', 20));

        if($request->get('nested', 'true')){
            $fe_menus->where('id', '=', $request->get('id', 1));
        }
        else if($request->get('nested', 'false')){
            $fe_menus->where('id', '=', $request->get('id', 2));
        }*/

        $fe_menus = FrontEndMenu::orderBy('id');

        $true = FrontEndMenu::select('parent_id')
        ->whereNotNull('parent_id');

        $false = FrontEndMenu::select('parent_id')
        ->whereNull('parent_id');

        if($request->get('nested') == "true"){
            $fe_menus->whereIn('id',  $true);
        }
        else if($request->get('nested') == "false"){
            $fe_menus->whereNotIn('id',  $true);
        }
        else{
            $fe_menus
            ->whereNull('parent_id')
            ->with(["childMenus" => function($query){
                $query->orderBy('order','asc');
            }]);
        }

        $fe_menus
        ->where('position', 'like', '%' . $request->get('position', '') . '%');

        $limit = $request->get('limit', 20);
        if($limit > 20){
            $limit = 20;
        }

        if(!Cache::has("frontEndMenuCache")){
            CacheStorage::cache("frontEndMenuCache", 'front-end-menu');
            Cache::put("frontEndMenuCache", new FrontEndMenuCollection($fe_menus->paginate($limit)->withQueryString()), now()->addMinutes(10));
        }

        return response()->json(Cache::get("frontEndMenuCache"));
        //return response()->json(new FrontEndMenuCollection($fe_menus->withQueryString()));
    }

    /*private function convertDataToResponse($dataRaw){
        return $dataRaw->transform(function ($item, $key) {
            return [
                'id' => $item->id,
                'parent' => $item->parent_id,
                'title'=>$item->title,
                'url'=>'/'.$item->slug,
                'type'=>'link',
                'target'=>'self',
                'order'=>$item->order,
                'position'=>json_decode($item->position),
                'children'=>$this->convertDataToResponse($item->childMenus)
            ];
        });
    }*/
}
