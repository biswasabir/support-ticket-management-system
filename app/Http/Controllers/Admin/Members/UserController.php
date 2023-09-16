<?php

namespace App\Http\Controllers\Admin\Members;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class UserController extends Controller
{
    public function index()
    {
        $counters['active'] = User::active()->get()->count();
        $counters['banned'] = User::banned()->get()->count();

        $users = User::users();

        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $users->where('firstname', 'like', $searchTerm)
                ->OrWhere('lastname', 'like', $searchTerm)
                ->OrWhere('email', 'like', $searchTerm);
        }

        if (request()->filled('status')) {
            $users->where('status', request('status'));
        }

        $users = $users->orderbyDesc('id')->paginate(30);
        $users->appends(request()->only(['search', 'status']));

        return view('admin.members.users.index', [
            'counters' => $counters,
            'users' => $users,
        ]);
    }

    public function create()
    {
        return view('admin.members.users.create');
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

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($user) {
            if (settings('actions')->email_verification_status) {
                $user->forceFill(['email_verified_at' => Carbon::now()])->save();
            }
            $user->assignRole(User::ROLE_USER);
            toastr()->success(admin_lang('Created Successfully'));
            return redirect()->route('admin.members.users.edit', $user->id);
        }
    }

    public function edit(User $user)
    {
        abort_if(!$user->isUser(), 404);
        return view('admin.members.users.edit', ['user' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['nullable', 'string', 'block_patterns', 'max:50'],
            'lastname' => ['nullable', 'string', 'block_patterns', 'max:50'],
            'email' => ['required', 'email', 'string', 'block_patterns', 'max:100', 'unique:users,email,' . $user->id],
            'avatar' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        abort_if(!$user->isUser(), 404);

        if ($request->has('avatar')) {
            $avatar = imageUpload($request->file('avatar'), 'images/avatars/', '120x120', null, $user->avatar);
        } else {
            $avatar = $user->avatar;
        }

        $status = ($request->has('status')) ? 1 : 0;

        $google2fa_status = 0;
        if ($request->has('google2fa_status')) {
            if (!$user->google2fa_status) {
                toastr()->error(admin_lang('Two-Factor authentication cannot activated from admin side'));
                return back();
            } else {
                $google2fa_status = 1;
            }
        }

        $update = $user->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'avatar' => $avatar,
            'google2fa_status' => $google2fa_status,
            'status' => $status,
        ]);

        if ($update) {
            $emailValue = ($request->has('email_status')) ? Carbon::now() : null;
            $user->forceFill([
                'email_verified_at' => $emailValue,
            ])->save();
            toastr()->success(admin_lang('Updated Successfully'));
            return back();
        }
    }

    public function destroy(User $user)
    {
        abort_if(!$user->isUser(), 404);
        $tickets = $user->tickets;
        if ($tickets->count() > 0) {
            foreach ($tickets as $ticket) {
                $ticket->load('replies.attachments');
                $ticket->delete();
            }
        }
        removeFile($user->avatar);
        $user->delete();
        toastr()->success(admin_lang('Deleted Successfully'));
        return back();
    }

    public function sendMail(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'subject' => ['required', 'string', 'block_patterns'],
            'reply_to' => ['required', 'email', 'block_patterns'],
            'message' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        if (!settings('smtp')->status) {
            toastr()->error(admin_lang('SMTP is not enabled'));
            return back()->withInput();
        }
        try {
            $email = $user->email;
            $subject = $request->subject;
            $replyTo = $request->reply_to;
            $msg = $request->message;
            \Mail::send([], [], function ($message) use ($msg, $email, $subject, $replyTo) {
                $message->to($email)
                    ->replyTo($replyTo)
                    ->subject($subject)
                    ->html($msg);
            });
            toastr()->success(admin_lang('Sent successfully'));
            return back();
        } catch (\Exception $e) {
            toastr()->error(admin_lang('Sent error'));
            return back();
        }
    }
}
