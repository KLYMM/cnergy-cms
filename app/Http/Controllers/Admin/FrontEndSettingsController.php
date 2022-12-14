<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FrontEndSettingsRequest;
use App\Http\Requests\GenerateTokenRequest;
use App\Http\Requests\GenerateConfigurationRequest;
use App\Http\Utils\FileFormatPath;
use App\Models\FrontEndSetting;
use App\Models\MenuSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FrontEndSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menu_settings = FrontEndSetting::first();
        $info_config = ["news", "photo", "video", "tag"];
        $cache_keys = config('cache-keys');
        $info_config2 = ["headline", "secondary", "thumbnail"];
        $i = null;
        if (isset($menu_settings['image_info'])) {
            $array_response = json_decode($menu_settings['image_info'], true);

            foreach ($info_config as $item) {
                if ($item === 'tag') {
                    $i[$item]["photo"] = $array_response[$item]["photo"];
                } else if ($item === 'photo') {
                    foreach ($info_config2 as $item2) {
                        $i[$item][$item2] = $array_response["photonews"][$item2];
                    }
                } else {
                    foreach ($info_config2 as $item2) {
                        $i[$item][$item2] = $array_response[$item][$item2];
                    }
                }
            }
        }


        return view('admin.menu.settings.index', compact('menu_settings', 'info_config', 'info_config2', 'i', 'cache_keys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function generateToken(GenerateTokenRequest $request)
    {
        try {
            $input = $request->validated();
            $menu = FrontEndSetting::first(['token']);
            if (!$menu) {
                $latest_token[$input["token_name"]] = sha1(Str::random(64));
            } else {
                $latest_token = json_decode($menu->token, true);
                $latest_token[$input["token_name"]] = sha1(Str::random(64));
            }
            FrontEndSetting::updateOrCreate([
                'id' => 1
            ], [
                'token' => json_encode($latest_token)
            ]);
            $token = $input["token_name"];
            $code = $latest_token[$input["token_name"]];
            return redirect()->back()->with('status', 'Successfully Generate Token - ' . $token . ' : ' . $code . ' ');
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function imageSize(Request $request)
    {
        try {
            $data = $request->all();
            $result = json_encode(
                [
                    "tag" => [
                        "photo" => $data['photo_size_tag']
                    ],
                    "video" => [
                        "headline" => $data['headline_size_video'],
                        "secondary" => $data['secondary_size_video'],
                        "thumbnail" => $data['thumbnail_size_video']
                    ],
                    "photonews" => [
                        "headline" => $data['headline_size_photo'],
                        "secondary" => $data['secondary_size_photo'],
                        "thumbnail" => $data['thumbnail_size_photo']
                    ],
                    "news" => [
                        "headline" => $data['headline_size_news'],
                        "secondary" => $data['secondary_size_news'],
                        "thumbnail" => $data['thumbnail_size_news']
                    ],
                ]
            );
            FrontEndSetting::updateOrCreate([
                'id' => 1
            ], [
                'image_info' => $result
            ]);
            return redirect()->back()->with('status', 'Successfully Input Image Size Info');
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function generateConfiguration(GenerateConfigurationRequest $request)
    {
        try {
            $input = $request->validated();
            $data = [
                "facebook_fanspage" => $input["facebook_fanspage"],
                "advertiser_id" => $input["advertiser_id"],
                "cse_id" => $input["cse_id"],
                "gtm_id" => $input["gtm_id"],
                "robot_txt" => $input["robot_txt"],
                "ads_txt" => $input["ads_txt"],
                "embed_code_data_studio" => $input["embed_code_data_studio"],
                "domain_name" => $input["domain_name"],
                "domain_url" => $input["domain_url"],
                "domain_url_mobile" => $input["domain_url_mobile"],
                "logo_url" => $input["logo_url"],
                "copyright" => $input["copyright"],
                "email_domain" => $input["email_domain"],
            ];

            FrontEndSetting::updateOrCreate([
                'id' => 1
            ], $data);
            return redirect()->back()->with('status', 'Successfully Update Frontend Settings');
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FrontEndSettingsRequest $request, $id = 1)
    {
        try {
            $input = $request->validated();
            $data = [
                "site_title" => $input["site_title"],
                "site_description" => $input["site_description"],
                "address" => $input["address"],
                "facebook" => $input["facebook"],
                "facebook_app_id" => $input["facebook_app_id"],
                "instagram" => $input["instagram"],
                "youtube" => $input["youtube"],
                "twitter" => $input["twitter"],
                "twitter_username" => $input["twitter_username"],
                "accent_color" => $input["accent_color"],
            ];
            $menu = FrontEndSetting::first(['site_logo', 'favicon']);
            if ($request->hasFile('site_logo')) {
                $file = $request->file('site_logo');
                $fileFormatPath = new FileFormatPath('trstdly', $file);
                $data['site_logo'] = $fileFormatPath->storeFile();
                if (isset($menu->site_logo)) {
                    if (Storage::exists($menu->site_logo)) {
                        Storage::delete($menu->site_logo);
                    }
                }
            }
            if ($request->hasFile('favicon')) {
                $file = $request->file('favicon');
                $fileFormatPath = new FileFormatPath('trstdly', $file);
                $data['favicon'] = $fileFormatPath->storeFile();
                if (isset($menu->favicon)) {
                    if (Storage::exists($menu->favicon)) {
                        Storage::delete($menu->favicon);
                    }
                }
            }
            FrontEndSetting::updateOrCreate([
                'id' => 1
            ], $data);
            return redirect()->back()->with('status', 'Successfully Update Frontend Settings');
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function cacheClear(Request $request)
    {

        try {
            $data = $request->all();

            if($data['key'] === 'all'){
                Cache::flush();
                return redirect()->back()->with('status', 'Successfully Clear All API Cache');
            }

            if (Cache::has($data['key'])) {
                $cache_keys = Cache::get($data['key']);

                foreach ($cache_keys as $key) {
                    Cache::forget($key);
                }

                Cache::forget($data['key']);
            }

            return redirect()->back()->with('status', 'Successfully Clear API Cache');
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
