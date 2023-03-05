<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\Menu;
use App\Models\Role;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

class RoleController extends Controller
{
    private $roleModel;

    public function __construct(Role $roleModel)
    {
        $this->roleModel = $roleModel;
    }

    public function index(Request $request)
    {
        $roles = $this->roleModel->findRoleByRole($request->get('inputRole'));
        return view('admin.role.index',
            ['roles' => $roles->paginate(10)->withQueryString()]);
    }

    public function create()
    {
        $method = explode('/', URL::current());
        $menus = Menu::whereNull('parent_id')->with(["childMenus", "roles"])->get();
        return view('admin.role.editable', ['method' => end($method)])->with('menus', $menus);
    }

    public function store(RoleRequest $request)
    {
        $data = $request->input();

        $menusId = $request->checkMenu;
        try {
            $role = $this->roleModel->saveRole($data['role']);
            if ($menusId != null) {
                    $role->menus()->attach($menusId);
            }

            // Cache::forget('menu_sidebar');
            return redirect()->route('role.index')->with('status', 'Successfully Create Role');
        } catch (\Throwable $e) {
            return Redirect::back()->withErrors($e->getMessage());
        }
    }


    public function show($id)
    {

    }

    public function edit($id)
    {
        $method = explode('/', URL::current());
        $role = $this->roleModel->findRoleById($id);
        $menus = Menu::whereNull('parent_id')->with(["childMenus", "roles"])->get();
        return view('admin.role.editable', ['method' => end($method)])->with('menus', $menus)->with('role', $role);
    }

    public function update(RoleRequest $request, $id)
    {
        $data = $request->validated();
        var_dump($data);
        $menusId = $request->checkMenu;
        $role = $this->roleModel->findRoleById($id);
        try {
            //check duplicate role
            if (($this->roleModel->isExistRole($data['role'])) == false) {
                $roleById = $this->roleModel->updateRole($id, $data['role']);
            } else {
                $roleById = $role;
            }
            $this->roleModel->deleteAccessRoleById($id);
            $roleById->menus()->sync($menusId);
            // Cache::forget('menu_sidebar');

            return redirect()->route('role.index')->with('status', 'Successfully Update Role');
        } catch (\Throwable $e) {
            return Redirect::back()->withErrors($e->getMessage());
        }
    }
    
    public function destroy($id)
    {
        try {
            $this->roleModel->deleteRole($id);
            // Cache::forget('menu_sidebar');
            return Redirect::back()->with('status', 'Successfully Delete Role');
        } catch (\Throwable $e) {
            return Redirect::back()->withErrors($e->getMessage());
        }
    }
}
