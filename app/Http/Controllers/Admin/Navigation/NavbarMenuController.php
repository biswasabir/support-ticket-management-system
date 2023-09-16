<?php

namespace App\Http\Controllers\Admin\Navigation;

use App\Http\Controllers\Controller;
use App\Models\NavbarMenu;
use Illuminate\Http\Request;
use Validator;

class NavbarMenuController extends Controller
{
    public function index(Request $request)
    {
        $navbarMenuLinks = NavbarMenu::whereNull('parent_id')->with(['children' => function ($query) {
            $query->byOrder();
        }])->byOrder()->get();
        return view('admin.navigation.navbar-menu.index', ['navbarMenuLinks' => $navbarMenuLinks]);

    }

    public function create()
    {
        return view('admin.navigation.navbar-menu.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100', 'unique:navbar_menu'],
            'link' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        $countLinks = NavbarMenu::all()->count();
        $navbarMenu = NavbarMenu::create([
            'name' => $request->name,
            'link' => $request->link,
            'order' => $countLinks + 1,
        ]);
        if ($navbarMenu) {
            toastr()->success(admin_lang('Created Successfully'));
            return redirect()->route('admin.navbar-menu.index');
        }
    }

    public function nestable(Request $request)
    {
        if ($request->has('ids') && !is_null($request->ids)) {
            $data = json_decode($request->ids, true);
            $i = 1;
            foreach ($data as $arr) {
                $menu = NavbarMenu::find($arr['id']);
                $menu->update([
                    'order' => $i,
                    'parent_id' => null,
                ]);
                if (isset($arr['children'])) {
                    $sub_i = 1;
                    foreach ($arr['children'] as $children) {
                        $menu = NavbarMenu::find($children['id']);
                        $menu->update([
                            'order' => $sub_i,
                            'parent_id' => $arr['id'],
                        ]);
                        $sub_i++;
                    }
                }
                $i++;
            }
        }
        toastr()->success(admin_lang('Updated Successfully'));
        return back();
    }

    public function show(NavbarMenu $navbarMenu)
    {
        return abort(404);
    }

    public function edit(NavbarMenu $navbarMenu)
    {
        return view('admin.navigation.navbar-menu.edit', ['navbarMenu' => $navbarMenu]);
    }

    public function update(Request $request, NavbarMenu $navbarMenu)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100', 'unique:navbar_menu,name,' . $navbarMenu->id],
            'link' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        $updateMenu = $navbarMenu->update([
            'name' => $request->name,
            'link' => $request->link,
        ]);
        if ($updateMenu) {
            toastr()->success(admin_lang('Updated Successfully'));
            return back();
        }
    }

    public function destroy(NavbarMenu $navbarMenu)
    {
        $navbarMenu->delete();
        toastr()->success(admin_lang('Deleted Successfully'));
        return back();
    }
}
