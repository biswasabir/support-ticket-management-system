<?php

namespace App\Http\Controllers\Admin;

use App\Events\AdminNewReplyCreated;
use App\Events\AdminTicketCreated;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\TicketReplyAttachment;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TicketController extends Controller
{
    public function index()
    {
        $counters['opened_tickets'] = Ticket::opened()->count();
        $counters['closed_tickets'] = Ticket::closed()->count();

        $users = User::users()->get();
        $departments = Department::all();
        $tickets = Ticket::query();

        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $tickets->where('subject', 'like', $searchTerm)
                ->orWhereHas('replies', function ($query) use ($searchTerm) {
                    $query->where('body', 'like', $searchTerm);
                });
        }

        if (request()->filled('user')) {
            $tickets->where('user_id', request('user'));
            $ticketsClone = clone $tickets;
            $counters['opened_tickets'] = $tickets->opened()->count();
            $counters['closed_tickets'] = $ticketsClone->closed()->count();
        }

        if (request()->filled('department')) {
            $tickets->where('department_id', request('department'));
        }

        if (request()->filled('priority')) {
            $tickets->where('priority', request('priority'));
        }

        if (request()->filled('status')) {
            $tickets->where('status', request('status'));
        }

        $tickets = $tickets->orderbyDesc('id')->paginate(50);
        $tickets->appends(request()->only(['search', 'user', 'department', 'status']));

        return view('admin.tickets.index', [
            'counters' => $counters,
            'users' => $users,
            'departments' => $departments,
            'tickets' => $tickets,
        ]);
    }

    public function create()
    {
        $users = User::users()->get();
        $departments = Department::active()->get();
        return view('admin.tickets.create', ['users' => $users, 'departments' => $departments]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => ['required', 'string', 'block_patterns', 'max:255'],
            'user' => ['required', 'integer', 'exists:users,id'],
            'department' => ['required', 'integer', 'exists:departments,id'],
            'priority' => ['required', 'integer', 'in:' . implode(',', array_keys(Ticket::getPriorityOptions()))],
            'description' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        $department = Department::where('id', $request->department)->active()->firstOrFail();

        try {
            $admin = Auth::user();
            $ticket = Ticket::create([
                'subject' => $request->subject,
                'priority' => $request->priority,
                'user_id' => $request->user,
                'department_id' => $request->department,
            ]);
            if ($ticket) {
                $ticketReply = TicketReply::create([
                    'body' => $request->description,
                    'ticket_id' => $ticket->id,
                    'user_id' => $admin->id,
                ]);
                if ($ticketReply) {
                    if ($request->hasFile('attachments')) {
                        foreach ($request->file('attachments') as $attachment) {
                            $ticketReplyAttachment = new TicketReplyAttachment();
                            $ticketReplyAttachment->name = $attachment->getClientOriginalName();
                            $ticketReplyAttachment->path = storageUpload($attachment, "tickets/{$ticket->id}/");
                            $ticketReplyAttachment->ticket_reply_id = $ticketReply->id;
                            $ticketReplyAttachment->save();
                        }
                    }
                }
                event(new AdminTicketCreated($ticket));
                toastr()->success(admin_lang('Ticket Created Successfully'));
                return redirect()->route('admin.tickets.show', $ticket->id);
            }
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    public function show(Ticket $ticket)
    {
        $departments = Department::active()->get();
        return view('admin.tickets.show', ['ticket' => $ticket, 'departments' => $departments]);
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $admin = Auth::user();
        $validator = Validator::make($request->all(), [
            'message' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        try {
            $ticketReply = TicketReply::create([
                'body' => $request->message,
                'ticket_id' => $ticket->id,
                'user_id' => $admin->id,
            ]);
            if ($ticketReply) {
                if ($request->hasFile('attachments')) {
                    foreach ($request->file('attachments') as $attachment) {
                        $ticketReplyAttachment = new TicketReplyAttachment();
                        $ticketReplyAttachment->name = $attachment->getClientOriginalName();
                        $ticketReplyAttachment->path = storageUpload($attachment, "tickets/{$ticket->id}/");
                        $ticketReplyAttachment->ticket_reply_id = $ticketReply->id;
                        $ticketReplyAttachment->save();
                    }
                }
                $ticket->status = Ticket::STATUS_OPENED;
                $ticket->update();
            }
            event(new AdminNewReplyCreated($ticketReply));
            toastr()->success(admin_lang('Reply Sent Successfully'));
            return back();
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    public function download(Ticket $ticket, TicketReplyAttachment $attachment)
    {
        $filePath = $attachment->path;
        $fileName = $attachment->name;
        try {
            $disk = Storage::disk('public');
            if (!$disk->exists($filePath)) {
                toastr()->error(admin_lang('The requested file are not exists'));
                return back();
            }
            $headers = [
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ];
            return new StreamedResponse(function () use ($disk, $filePath) {
                $stream = $disk->readStream($filePath);
                while (!feof($stream) && connection_status() === 0) {
                    echo fread($stream, 1024 * 8);
                    flush();
                }
                fclose($stream);
            }, 200, $headers);
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back();
        }
    }

    public function close(Request $request, Ticket $ticket)
    {
        $ticket->status = Ticket::STATUS_CLOSED;
        $ticket->update();
        toastr()->success(admin_lang('Ticket Closed Successfully'));
        return back();
    }

    public function transfer(Request $request, Ticket $ticket)
    {
        $validator = Validator::make($request->all(), [
            'department' => ['required', 'integer', 'exists:departments,id'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        $department = Department::where('id', $request->department)->active()->firstOrFail();

        if ($ticket->department->id != $request->department) {
            $ticket->department_id = $request->department;
            $ticket->update();
            toastr()->success(admin_lang('Ticket Transferred Successfully'));
        }
        return back();
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->load('replies.attachments');
        $ticket->delete();
        toastr()->success(admin_lang('Deleted Successfully'));
        return back();
    }
}
