<?php

namespace App\Http\Controllers\Admin\Members;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::admins()->where('id', '!=', Auth::user()->id);

        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $admins->where('firstname', 'like', $searchTerm)
                ->OrWhere('lastname', 'like', $searchTerm)
                ->OrWhere('email', 'like', $searchTerm);
        }

        $admins = $admins->orderbyDesc('id')->paginate(30);
        $admins->appends(request()->only(['search']));

        return view('admin.members.admins.index', [
            'admins' => $admins,
        ]);
    }

    public function create()
    {
        return view('admin.members.admins.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['nullable', 'string', 'block_patterns', 'max:50'],
            'lastname' => ['nullable', 'string', 'block_patterns', 'max:50'],
            'email' => ['required', 'email', 'string', 'block_patterns', 'max:100', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        $admin = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($admin) {
            $admin->forceFill(['email_verified_at' => Carbon::now()])->save();
            $admin->assignRole(User::ROLE_ADMIN);
            toastr()->success(admin_lang('Created Successfully'));
            return redirect()->route('admin.members.admins.edit', $admin->id);
        }
    }

    public function edit(User $admin)
    {
        abort_if(!$admin->isAdmin() || $admin->id == Auth::user()->id, 404);
        return view('admin.members.admins.edit', ['admin' => $admin]);
    }

    public function update(Request $request, User $admin)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['nullable', 'string', 'block_patterns', 'max:50'],
            'lastname' => ['nullable', 'string', 'block_patterns', 'max:50'],
            'email' => ['required', 'email', 'string', 'block_patterns', 'max:100', 'unique:users,email,' . $admin->id],
            'avatar' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        abort_if(!$admin->isAdmin() || $admin->id == Auth::user()->id, 404);

        if ($request->has('avatar')) {
            $avatar = imageUpload($request->file('avatar'), 'images/avatars/', '120x120', null, $admin->avatar);
        } else {
            $avatar = $admin->avatar;
        }

        $google2fa_status = 0;
        if ($request->has('google2fa_status')) {
            if (!$admin->google2fa_status) {
                toastr()->error(admin_lang('Two-Factor authentication cannot activated from admin side'));
                return back();
            } else {
                $google2fa_status = 1;
            }
        }

        $update = $admin->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'avatar' => $avatar,
            'google2fa_status' => $google2fa_status,
        ]);

        if ($update) {
            toastr()->success(admin_lang('Updated Successfully'));
            return back();
        }
    }

    public function destroy(User $admin)
    {
        abort_if(!$admin->isAdmin() || $admin->id == Auth::user()->id, 404);
        $ticketReplies = $admin->replies;
        if ($ticketReplies->count() > 0) {
            foreach ($ticketReplies as $ticketReply) {
                $ticket = $ticketReply->ticket;
                $ticket->load('replies.attachments');
                $ticket->delete();
            }
        }
        removeFile($admin->avatar);
        $admin->delete();
        toastr()->success(admin_lang('Deleted Successfully'));
        return back();
    }

}
