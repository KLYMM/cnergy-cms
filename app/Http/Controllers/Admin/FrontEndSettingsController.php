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
        return view('admin.menu.settings.index', compact('menu_settings'));
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
                $fileFormatPath = new FileFormatPath('front-end-settings/site-logo', $file);
                $data['site_logo'] = $fileFormatPath->storeFile();
                if (isset($menu->site_logo)) {
                    if (Storage::exists($menu->site_logo)) {
                        Storage::delete($menu->site_logo);
                    }
                }
            }
            if ($request->hasFile('favicon')) {
                $file = $request->file('favicon');
                $fileFormatPath = new FileFormatPath('front-end-settings/site-logo', $file);
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
}
