<?php

namespace App\Http\Controllers\Admin\Members;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class AgentController extends Controller
{
    public function index()
    {
        $agents = User::agents();

        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $agents->where('firstname', 'like', $searchTerm)
                ->OrWhere('lastname', 'like', $searchTerm)
                ->OrWhere('email', 'like', $searchTerm);
        }

        $agents = $agents->orderbyDesc('id')->paginate(30);
        $agents->appends(request()->only(['search']));

        return view('admin.members.agents.index', ['agents' => $agents]);
    }

    public function create()
    {
        $departments = Department::active()->get();
        return view('admin.members.agents.create', ['departments' => $departments]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['nullable', 'string', 'block_patterns', 'max:50'],
            'lastname' => ['nullable', 'string', 'block_patterns', 'max:50'],
            'email' => ['required', 'email', 'string', 'block_patterns', 'max:100', 'unique:users'],
            'departments' => ['nullable', 'array'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($request->has('departments')) {
            foreach ($request->departments as $department) {
                $checkExist = Department::where('id', $department)->active()->firstOrFail();
                if (!$checkExist) {
                    toastr()->error(admin_lang('Invalid department'));
                    return back();
                }
            }
        }

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        $agent = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($agent) {
            $agent->forceFill(['email_verified_at' => Carbon::now()])->save();
            $agent->assignRole(User::ROLE_AGENT);
            $agent->departments()->sync($request->departments);
            toastr()->success(admin_lang('Created Successfully'));
            return redirect()->route('admin.members.agents.edit', $agent->id);
        }
    }

    public function edit(User $agent)
    {
        abort_if(!$agent->isAgent(), 404);
        $departments = Department::active()->get();
        $agentDepartmentIds = $agent->departments->pluck('id')->toArray();
        return view('admin.members.agents.edit', [
            'agent' => $agent,
            'departments' => $departments,
            'agentDepartmentIds' => $agentDepartmentIds,
        ]);
    }

    public function update(Request $request, User $agent)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['nullable', 'string', 'block_patterns', 'max:50'],
            'lastname' => ['nullable', 'string', 'block_patterns', 'max:50'],
            'email' => ['required', 'email', 'string', 'block_patterns', 'max:100', 'unique:users,email,' . $agent->id],
            'departments' => ['nullable', 'array'],
            'avatar' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        abort_if(!$agent->isAgent(), 404);

        if ($request->has('departments')) {
            foreach ($request->departments as $department) {
                $checkExist = Department::where('id', $department)->active()->firstOrFail();
                if (!$checkExist) {
                    toastr()->error(admin_lang('Invalid department'));
                    return back();
                }
            }
        }

        if ($request->has('avatar')) {
            $avatar = imageUpload($request->file('avatar'), 'images/avatars/', '120x120', null, $agent->avatar);
        } else {
            $avatar = $agent->avatar;
        }

        $google2fa_status = 0;
        if ($request->has('google2fa_status')) {
            if (!$agent->google2fa_status) {
                toastr()->error(admin_lang('Two-Factor authentication cannot activated from admin side'));
                return back();
            } else {
                $google2fa_status = 1;
            }
        }

        $update = $agent->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'avatar' => $avatar,
            'google2fa_status' => $google2fa_status,
        ]);

        if ($update) {
            $agent->departments()->sync($request->departments);
            toastr()->success(admin_lang('Updated Successfully'));
            return back();
        }
    }

    public function destroy(User $agent)
    {
        abort_if(!$agent->isAgent(), 404);
        $ticketReplies = $agent->replies;
        if ($ticketReplies->count() > 0) {
            foreach ($ticketReplies as $ticketReply) {
                $ticket = $ticketReply->ticket;
                $ticket->load('replies.attachments');
                $ticket->delete();
            }
        }
        removeFile($agent->avatar);
        $agent->delete();
        toastr()->success(admin_lang('Deleted Successfully'));
        return back();
    }

}
