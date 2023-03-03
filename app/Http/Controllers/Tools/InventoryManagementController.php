<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Models\InventoryManagement;
use App\Http\Requests\InventoryManagementRequest;
use Illuminate\Support\Str;

class InventoryManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inventory_config = config('inventory');
        $inventory_management = InventoryManagement::getAll();

        return view('tools.inventory.editable', compact('inventory_config', 'inventory_management'));
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
    public function store(InventoryManagementRequest $request)
    {
        try {
            $input = $request->validated();
            $data = [];
            $type = '';
            unset($input['_token']);
            foreach ($input as $key => $item) {
                $type = $key;
                foreach ($item as $inventory) {
                    $inventory['type'] = $type;
                    $inventory['size'] = $inventory['creative_size'] ?? null;
                    //if (isset($inventory['creative_size'])) {
                        unset($inventory['creative_size']);
                   // }
//                    $inventory['code'] = $inventory['code'] ?? "<script>
//                    function jsFunc(arg1, arg2) {
//                       if (arg1 && arg2) document.body.innerHTML = 'achoo';
//                    }
//                </script>";
                    $inventory['code'] = $inventory['code'] ?? "";
                    $inventory['created_by'] = auth()->id();
                    $inventory['template_id'] = $inventory['template_id'] ?? null;
                    $inventory['adunit_size'] = $inventory['adunit_size'] ?? null;
                    $inventory['placement_id'] = $inventory['placement_id'] ?? null;
                    $inventory['id'] = intval($inventory['id']) ?? null;
                    array_push($data, $inventory);
                } 
            }
            // return $data;
            InventoryManagement::upsert($data, ['id'], ['inventory', 'slot_name', 'adunit_size', 'size', 'template_id', 'code', 'created_by', 'placement_id']);

            return redirect()->route('inventory-management.index')->with('status', 'Successfully Update Inventory');
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors($e->getMessage());
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
