<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Validator;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return view('admin.departments.index', ['departments' => $departments]);
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'block_patterns', 'max:255'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        $request->status = ($request->has('status')) ? 1 : 0;

        $department = new Department();
        $department->name = $request->name;
        $department->status = $request->status;
        $department->save();

        toastr()->success(admin_lang('Created Successfully'));
        return redirect()->route('admin.departments.index');

    }

    public function edit(Department $department)
    {
        return view('admin.departments.edit', ['department' => $department]);
    }

    public function update(Request $request, Department $department)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'block_patterns', 'max:255'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        $request->status = ($request->has('status')) ? 1 : 0;

        $department->name = $request->name;
        $department->status = $request->status;
        $department->save();

        toastr()->success(admin_lang('Updated Successfully'));
        return back();
    }

    public function destroy(Department $department)
    {
        if ($department->tickets->count() > 0) {
            toastr()->error(admin_lang('Department has tickets it cannot be deleted'));
            return back();
        }
        $department->delete();
        toastr()->success(admin_lang('Deleted Successfully'));
        return back();
    }
}