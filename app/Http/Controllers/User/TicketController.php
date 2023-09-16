<?php

namespace App\Http\Controllers\User;

use App\Events\UserNewReplyCreated;
use App\Events\UserTicketCreated;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\TicketReplyAttachment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Validator;

class TicketController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $counters['opened_tickets'] = Ticket::where('user_id', $user->id)->opened()->count();
        $counters['closed_tickets'] = Ticket::where('user_id', $user->id)->closed()->count();

        $tickets = Ticket::where('user_id', $user->id);
        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $tickets->where('subject', 'like', $searchTerm)
                ->orWhereHas('replies', function ($query) use ($searchTerm) {
                    $query->where('body', 'like', $searchTerm);
                });
        }
        if (request()->filled('status_opened')) {
            $tickets->where('status', Ticket::STATUS_OPENED);
        }
        if (request()->filled('status_closed')) {
            $tickets->where('status', Ticket::STATUS_CLOSED);
        }

        $tickets = $tickets->orderbyDesc('id')->paginate(12);

        $tickets->appends(request()->only(['search', 'status_opened', 'status_closed']));

        return view('user.tickets.index', [
            'counters' => $counters,
            'tickets' => $tickets,
        ]);
    }

    public function create()
    {
        $departments = Department::active()->get();
        return view('user.tickets.create', ['departments' => $departments]);
    }

    public function store(Request $request)
    {
        $files = $request->file('attachments');
        $allowedExts = explode(',', settings('tickets')->file_types);
        $maxFileSize = settings('tickets')->max_file_size;

        $validator = Validator::make($request->all(), [
            'subject' => ['required', 'string', 'block_patterns', 'max:255'],
            'department' => ['required', 'integer', 'exists:departments,id'],
            'priority' => ['required', 'integer', 'in:' . implode(',', array_keys(Ticket::getPriorityOptions()))],
            'description' => ['required', 'string'],
            'attachments' => [
                'max:' . ($maxFileSize * 1024),
                function ($attribute, $value, $fail) use ($files, $allowedExts, $maxFileSize) {
                    foreach ($files as $file) {
                        if ($file->getSize() > ($maxFileSize * 1048576)) {
                            return $fail(str(lang('Max file size is {max}MB', 'tickets'))->replace('{max}', $maxFileSize));
                        }
                        $ext = strtolower($file->getClientOriginalExtension());
                        if (!in_array($ext, $allowedExts)) {
                            return $fail(lang('Some uploaded files are not supported', 'tickets'));
                        }
                    }
                    if (count($files) > settings('tickets')->max_files) {
                        return $fail(lang('Max {max} files can be uploaded', 'tickets'));
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        $department = Department::where('id', $request->department)->active()->firstOrFail();

        try {
            $user = Auth::user();
            $ticket = Ticket::create([
                'subject' => $request->subject,
                'priority' => $request->priority,
                'user_id' => $user->id,
                'department_id' => $department->id,
            ]);
            if ($ticket) {
                $ticketReply = TicketReply::create([
                    'body' => $request->description,
                    'ticket_id' => $ticket->id,
                    'user_id' => $user->id,
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
                event(new UserTicketCreated($ticket));
                toastr()->success(lang('Ticket Created Successfully', 'tickets'));
                return redirect()->route('user.tickets.show', $ticket->id);
            }
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    public function show($id)
    {
        $ticket = Ticket::where('user_id', Auth::user()->id)->where('id', $id)->with(['replies', 'department'])->withAttachments()->firstOrFail();
        return view('user.tickets.show', ['ticket' => $ticket]);
    }

    public function reply(Request $request, $id)
    {
        $user = Auth::user();
        $ticket = Ticket::where('user_id', $user->id)->where('id', $id)->firstOrFail();

        $files = $request->file('attachments');
        $allowedExts = explode(',', settings('tickets')->file_types);
        $maxFileSize = settings('tickets')->max_file_size;

        $validator = Validator::make($request->all(), [
            'message' => ['required', 'string'],
            'attachments' => [
                'max:' . ($maxFileSize * 1024),
                function ($attribute, $value, $fail) use ($files, $allowedExts, $maxFileSize) {
                    foreach ($files as $file) {
                        if ($file->getSize() > ($maxFileSize * 1048576)) {
                            return $fail(str(lang('Max file size is {max}MB', 'tickets'))->replace('{max}', $maxFileSize));
                        }
                        $ext = strtolower($file->getClientOriginalExtension());
                        if (!in_array($ext, $allowedExts)) {
                            return $fail(lang('Some uploaded files are not supported', 'tickets'));
                        }
                    }
                    if (count($files) > settings('tickets')->max_files) {
                        return $fail(lang('Max {max} files can be uploaded', 'tickets'));
                    }
                },
            ],
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
                'user_id' => $user->id,
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
            event(new UserNewReplyCreated($ticketReply));
            toastr()->success(lang('Reply Sent Successfully', 'tickets'));
            return back();
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    public function download($id, $attachment_id)
    {
        $user = Auth::user();

        $ticket = Ticket::where('user_id', $user->id)->where('id', $id)->firstOrFail();
        $ticketReplyAttachment = TicketReplyAttachment::where('id', $attachment_id)->firstOrFail();

        $filePath = $ticketReplyAttachment->path;
        $fileName = $ticketReplyAttachment->name;

        try {
            $disk = Storage::disk('public');
            if (!$disk->exists($filePath)) {
                toastr()->error(lang('The requested file are not exists', 'tickets'));
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
}
