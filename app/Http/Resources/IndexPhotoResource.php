<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\ImageBank;

class IndexPhotoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        $category = new IndexCategoryResource($this->categories);
        return [
            'news_id' => $this->id,
            'news_entry' => date('Y-m-d G:i:s', strtotime($this->created_at)),
            'news_last_update' => date('Y-m-d G:i:s', strtotime($this->updated_at)),
            'news_level' => $this->is_published,
            'news_top_headline' => $this->is_headline,
            'news_editor_pick' => $this->editor_pick,
            'news_hot' => '',
            'news_home_headline'=> $this->is_home_headline,
            'news_category_headline'=> $this->is_category_headline,
            'news_curated'=> $this->is_curated,
            'news_advertorial'=> $this->is_advertorial,
            'news_disable_interactions'=> $this->is_disable_interactions,
            'news_branded_content'=> $this->is_branded_content,
            'news_category' => [$category],
            'news_title' => $this->title,
            'news_subtitle' => '',
            'news_synopsis' => $this->synopsis,
            'news_content' => $this->content,
            'news_description' => $this->description,
            'news_image_prefix' => '/trstdly/',
            // 'news_image' => [
            //     'real' => $this->image,
            // ],
            "news_image" => $this->newsImage($this->image),
            'news_image_thumbnail' => [
                'real' => ''
            ],
            'news_image_potrait' => [
                'real' => ''
            ],
            'news_image_headline' => '',
            'news_imageinfo' => $this->description,
            'news_url' => $this->slug,
            'news_date_publish' => $this->published_at,
            'news_type' => $this->types,
            'news_reporter' => self::arrayUserToObjectUser(json_decode($this->reporters)),
            'news_editor' => self::arrayUserToObjectUserEditor($this->created_by),
            'news_photographer' => self::arrayUserToObjectUser(json_decode($this->photographers)),
            'news_hastag' => '',
            'news_city' => '',
            'news_sponsorship' => null,
            'has_paging' => '',
            'is_splitter' => '',
            'paging_style' => '',
            'news_mature' => $this->is_adult_content,
            'news_seo_url' => $this->is_seo,
            'news_sensitive' => $this->is_verify_age,
            'news_top_headtorial' => '',
            'news_date_headtorial' => '',
            'tracker_dmp' => null,
            'special_event_name' => null,
            'news_id_import' => null,
            'news_guide' => null,
            'paging' => [],
            'quote' => [],
            'photonews' => PhotonewsResource::customCollection($this->news_photo,$this->slug),
            'video' => null,
            'category_name' => $category->category,
            'news_url_full' => env('APP_URL') . '/' . Str::slug(strtolower($category->category)) . '/read/' . $this->slug,
            'news_url_full_mobile' => '',
            // 'news_paging' => [],
            // 'news_paging_order' => null,
            // 'news_quote' => '',
            // 'news_video' => $this->videoResponse($this->video),
            'news_tag' => IndexPhotoTagResource::collection($this->tags),
            'news_keywords' => $this->keywordResponse($this->keywords),
            'news_related' => [],
            'news_dfp' => [],
            'news_dmp' => [],
            'cdn_image' => [
                "klimg_url" => "",
                "cdnimg_url" => "",
                "file_image" => "",
                "file_image_thumbnail" => "",
                "file_image_potrait" => "",
            ]
        ];
    }

    private function arrayUserToObjectUser($array)
    {
        $temp = array();
        if ($array != null) {
            foreach ($array as $uuid) {
                array_push(
                    $temp,
                    self::userResponse($uuid)
                );
            }
        }
        return $temp;
    }

    private function arrayUserToObjectUserEditor($array)
    {
        /*$temp = array();
        if ($array != null) {
            foreach ($array as $uuid) {
                if (User::join('roles', 'users.role_id', '=', 'roles.id')
                    ->where('roles.role', "Editor")
                    ->where('uuid', $uuid)
                    ->exists()
                )
                    array_push(
                        $temp,
                        self::userResponse($uuid)
                    );
            }
        }
        return $temp;*/
        $userById = User::where('uuid', '=', $array)->get(['name','profile_image'])->first();
        return [
            "id" => $array,
            "name" => $userById->name,
            "image" => $userById->profile_image
        ];
    }

    private function userResponse($uuid)
    {
        $userById = User::where('uuid', '=', $uuid)->get(['name','profile_image'])->first();
        return [
            "id" => $uuid,
            "name" => $userById->name,
            "image" => $userById->profile_image
        ];
    }

    private function keywordResponse($dataRaw2){
        return $dataRaw2->transform(function ($item, $key) {
            return [
                "news_keyword_id" => $item->pivot->id,
                "keyword_id" => $item->id,
                "keyword_name" => $item->keywords,
            ];
        });
    }

    private function newsImage($image){
        if($image === NULL){
            return null;
        }else{
            return [
                "real" => env('APP_URL') . '/storage/' . $this->image
            ];
        }
    }

    private function newsImageInfo($image2){
        if($image2 === NULL){
            return null;
        }else{
            $info = ImageBank::where('slug', '=', '/'. $image2)->get('description')->first();
            if($info === NULL){
                return null;
            }else{
                return $info->description;
            }
        }
    }
}
