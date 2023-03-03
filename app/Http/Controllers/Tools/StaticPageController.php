<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class StaticPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $static = StaticPage::latest()->with(['users']);

        if ($request->get('startDate') && $request->get('endDate')) {
            $startDate = Carbon::parse(($request->get('startDate')))
                ->toDateTimeString();

            $endDate = Carbon::parse($request->get('endDate'))
                ->toDateTimeString();
            $static->whereBetween('created_at', [
                $startDate, $endDate
            ]);
        }

        if ($request->get('inputTitle')) {
            $static->where('title', 'like', '%' . $request->inputTitle . '%');
        }

        if ($request->get('inputSlug')) {
            $static-> where('slug', 'like', '%' . $request->inputSlug . '%');
        }

        if ($request->get('status')) {
            $status = $request->status;
            if($status == 2) {
                $static ->where('is_active', "0");
            }else {
                $static ->where('is_active', "1");
            }
        }

        // return view('admin.tags.index',compact('tags'));
        $method = explode('/', URL::current());
        return view('tools.static.index',  [
            'type' => end($method),
            'static_page' => $static->paginate(10)->withQueryString()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //masih copas dari news

        $method = explode('/', URL::current());

        return view('tools.static.editable', [
            'method' => end($method),
        ]);
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
        $data = $request->validate([
            '_token' => ['required'],
            'title' => ['required', 'max:255'],
            'slug' => ['required'],
            'content' => ['required'],
            'isActive' => ['required'],
            'save' => ['required'],
        ]);
        var_dump($data);
        die();

        try {
            $static = new StaticPage([
                'title' => $data['title'],
                'slug' => Str::slug($data['slug']),
                'content' => $data['content'],
                'is_active' => $request->has('isActive') == false ? '0' : '1',
                'created_by' => auth()->id()
            ]);

            $static->save();

            return \redirect()->route('static-page.index')->with('status', 'Successfully Create Static Page');
        } catch (\Throwable $e) {
            return Redirect::back()->withErrors($e->getMessage());
        }
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
        $method = explode('/', URL::current());
        $static = StaticPage::where('id', $id)->first();

        return view('tools.static.editable', [
            'method' => end($method),
            'static' => $static
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $data = $request->input();
        $static = StaticPage::find($id);

        try {
            $input = [
                'title' => $data['title'],
                'slug' => Str::slug($data['slug']),
                'content' => $data['content'],
                'is_active' => $request->has('isActive') == false ? '0' : '1',
                'updated_by' => auth()->id()
            ];

            $static->update($input);


            return \redirect()->route('static-page.index')->with('status', 'Successfully Update Static Page');
        } catch (\Throwable $e) {
            return Redirect::back()->withErrors($e->getMessage());
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
        try {
            StaticPage::where('id', $id)->update([
                'deleted_by' => Auth::user()->uuid,
            ]);
            StaticPage::destroy($id);
            return Redirect::back()->with('status', 'Successfully Delete Static Page');
        } catch (\Throwable $e) {
            return Redirect::back()->withErrors($e->getMessage());
        }
    }
}
