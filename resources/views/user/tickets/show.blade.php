@extends('user.layouts.app')
@section('title', str(lang('Ticket #{number}', 'tickets'))->replace('{number}', $ticket->id))
@section('back', route('user.tickets.index'))
@section('content')
    <div class="row g-3">
        <div class="col-12 col-xl-8">
            <div class="row row-cols-1 g-3">
                @foreach ($ticket->replies as $reply)
                    <div class="col">
                        <div class="card-v p-4">
                            <div class="p-2">
                                <div class="mb-4">
                                    <div class="row row-cols-auto justify-content-between align-items-center g-3">
                                        <div class="col">
                                            <div class="tickets-user">
                                                <img src="{{ $reply->user->getAvatar() }}"
                                                    alt="{{ $reply->user->getName() }}" />
                                                <span class="h6 mb-0">{{ $reply->user->getName() }}</span>
                                                @if (!$reply->user->isUser())
                                                    <div class="badge bg-primary ms-2">{{ lang('Support', 'tickets') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        @if (!$loop->first)
                                            <div class="col">
                                                <time class="text-muted small">{{ dateFormat($reply->created_at) }}</time>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="ticket-content">
                                    @if ($loop->first)
                                        <h5 class="mb-3">{{ $ticket->subject }}</h5>
                                    @endif
                                    <div class="tickets-paragrah">
                                        @php
                                            $purifier = new \HTMLPurifier();
                                            $safeReplyBody = $purifier->purify($reply->body);
                                        @endphp
                                        {!! nl2br(e($safeReplyBody)) !!}
                                        @if ($reply->attachments->count() > 0)
                                            <div class="mt-4">
                                                <h6 class="text-dark mb-3">{{ lang('Attached files', 'tickets') }}:</h6>
                                                <div class="row g-2">
                                                    @foreach ($reply->attachments as $attachment)
                                                        <div class="col-lg-6">
                                                            <a href="{{ route('user.tickets.download', [$ticket->id, $attachment->id]) }}"
                                                                class="ticket-file text-muted d-block bg-light p-3 border rounded-2 h-100">
                                                                <div class="row align-items-center g-2">
                                                                    <div class="col-auto">
                                                                        <div class="ticket-file-icon">
                                                                            <i class="fa fa-file-alt"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col">
                                                                        <div class="ticket-file-meta">
                                                                            <h6 class="mb-0">
                                                                                {{ shorterText($attachment->name, 40) }}
                                                                            </h6>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <i class="fa fa-download"></i>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col">
                    <div class="card-v p-4">
                        <div class="p-2">
                            <h5 class="mb-3">{{ lang('Reply', 'tickets') }}</h5>
                            <form action="{{ route('user.tickets.reply', $ticket->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row g-3 mb-3">
                                    <div class="col-lg-12">
                                        <textarea name="message" class="form-control" rows="5">{{ old('message') }}</textarea>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="attachments">
                                            <div class="attachment-box-1">
                                                <label class="form-label">{{ lang('Attachments', 'tickets') }}
                                                    ({{ $settings->tickets->file_types }}) </label>
                                                <div class="input-group">
                                                    <input type="file" name="attachments[]"
                                                        class="form-control form-control-md">
                                                    <button id="addAttachment" class="btn btn-outline-secondary"
                                                        type="button">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary btn-md">{{ lang('Send', 'tickets') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="col">
                <div class="card-v p-4">
                    <div class="p-2">
                        <div class="border-bottom pb-3 mb-3">
                            <div class="row row-cols-auto align-items-center justify-content-between g-2">
                                <div class="col">
                                    <h6 class="text-primary mb-0">
                                        {{ str(lang('Ticket #{number}', 'tickets'))->replace('{number}', $ticket->id) }}
                                    </h6>
                                </div>
                                <div class="col">
                                    @if ($ticket->isOpened())
                                        <span class="badge bg-primary">{{ lang('Opened', 'tickets') }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ lang('Closed', 'tickets') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row row-cols-1 justify-content-between align-items-center g-3">
                            <div class="col">
                                <div class="row row-cols-auto justify-content-between">
                                    <div class="col">
                                        {{ lang('Priority', 'tickets') }}:
                                    </div>
                                    <div class="col">
                                        <time class="text-muted small">{{ $ticket->getPriority() }}</time>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row row-cols-auto justify-content-between">
                                    <div class="col">
                                        {{ lang('Department', 'tickets') }}:
                                    </div>
                                    <div class="col">
                                        <time class="text-muted small">{{ $ticket->department->name }}</time>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row row-cols-auto justify-content-between">
                                    <div class="col">
                                        {{ lang('Created Date', 'tickets') }}:
                                    </div>
                                    <div class="col">
                                        <time class="text-muted small">{{ dateFormat($ticket->created_at) }}</time>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row row-cols-auto justify-content-between">
                                    <div class="col">
                                        {{ lang('Last Activity', 'tickets') }}:
                                    </div>
                                    <div class="col">
                                        <time class="text-muted small">{{ dateFormat($ticket->updated_at) }}</time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('top_scripts')
        <script>
            "use strict";
            const ticketsConfig = {!! json_encode([
                'max_file' => $settings->tickets->max_files,
                'max_files_error' => str(lang('Max {max} files can be uploaded', 'tickets'))->replace(
                    '{max}',
                    $settings->tickets->max_files,
                ),
            ]) !!}
        </script>
    @endpush
@endsection
