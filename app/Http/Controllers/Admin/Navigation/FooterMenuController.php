<?php

namespace App\Http\Controllers\Admin\Navigation;

use App\Http\Controllers\Controller;
use App\Models\FooterMenu;
use Illuminate\Http\Request;
use Validator;

class FooterMenuController extends Controller
{
    public function index(Request $request)
    {
        $footerMenuLinks = FooterMenu::orderBy('sort_id', 'asc')->get();
        return view('admin.navigation.footer-menu.index', ['footerMenuLinks' => $footerMenuLinks]);
    }

    public function create()
    {
        return view('admin.navigation.footer-menu.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100', 'unique:footer_menu'],
            'link' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        $countLinks = FooterMenu::get()->count();
        $sortId = $countLinks + 1;
        $createMenu = FooterMenu::create([
            'name' => $request->name,
            'link' => $request->link,
            'sort_id' => $sortId,
        ]);
        if ($createMenu) {
            toastr()->success(admin_lang('Created Successfully'));
            return redirect()->route('admin.footer-menu.index');
        }
    }

    public function sort(Request $request)
    {
        if ($request->has('ids') && !is_null($request->ids)) {
            $arr = explode(',', $request->ids);
            foreach ($arr as $sortOrder => $id) {
                $menu = FooterMenu::find($id);
                $menu->sort_id = $sortOrder;
                $menu->save();
            }
        }
        toastr()->success(admin_lang('Updated Successfully'));
        return back();
    }

    public function show(FooterMenu $footerMenu)
    {
        return abort(404);
    }

    public function edit(FooterMenu $footerMenu)
    {
        return view('admin.navigation.footer-menu.edit', ['footerMenu' => $footerMenu]);
    }

    public function update(Request $request, FooterMenu $footerMenu)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100', 'unique:footer_menu,name,' . $footerMenu->id],
            'link' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        $updateMenu = $footerMenu->update([
            'name' => $request->name,
            'link' => $request->link,
        ]);
        if ($updateMenu) {
            toastr()->success(admin_lang('Updated Successfully'));
            return back();
        }
    }

    public function destroy(FooterMenu $footerMenu)
    {
        $footerMenu->delete();
        toastr()->success(admin_lang('Deleted Successfully'));
        return back();
    }
}
