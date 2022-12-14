<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FrontEndMenu extends Model
{
    use HasFactory,LogsActivity;

    protected $guarded = [];

    public function child()
    {
        return $this->hasMany(FrontEndMenu::class, 'parent_id');
    }

    public function child_desc()
    {
        return $this->hasMany(FrontEndMenu::class, 'parent_id')->orderBy('order', 'desc');
    }

    public function childMenus()
    {
        return $this->hasMany(FrontEndMenu::class, 'parent_id')->with('child.child');
    }

    public function slug()
    {
        return $this->slug;
    }

    public function title()
    {
        return $this->title;
    }

    public function menu_name()
    {
        $position = '';
        $arr = json_decode($this->position);
        foreach($arr as $item){
            $position .= "<span>". $item . "</span>";
        }
        return $this->title . "<i>" . $position  . "</i>";
    }

    public function childs()
    {
        return $this->child;
    }

    public function order()
    {
        return $this->order;
    }

    public function parent()
    {
        return $this->parent_id;
    }
    public function position()
    {
        return $this->position;
    }

    public static function getAll()
    {
        $category = self::select("*", "title as menu_name")->orderBy('parent_id')->orderBy('order')->get()->toArray();
        return self::convertCategoryDataToResponse($category);
    }

    public static function convertCategoryDataToResponse($dataRaw)
    {
        $tree = [];
        $references = [];

        foreach ($dataRaw as $id => &$node) {
            $references[intval($node['id'])] = &$node;
            $node['children'] = array();
            if (is_null($node['parent_id'])) {
                $tree[intval($node['id'])] = &$node;
            } else {
                $references[$node['parent_id']]['children'][intval($node['id'])] = &$node;
            }
        }

        return array_values(self::array_values_recursive($tree));
    }

    public static function array_values_recursive($arr)
    {
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $arr[$key] = self::array_values_recursive($value);
            }
        }

        if (isset($arr['children'])) {
            $arr['children'] = array_values($arr['children']);
        }

        return $arr;
    }

    protected $recordEvents = ['created', 'updated', 'deleted'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You have {$eventName} frontend menu";
    }

    public function getLogNameToUse(): ?string
    {
        return "frontend menu";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
