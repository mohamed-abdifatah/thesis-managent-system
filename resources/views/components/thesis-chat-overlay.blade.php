@props([
    'thesis',
    'chatItems' => collect(),
    'chatKey' => 'thesis-chat',
])

@php
    $items = $chatItems instanceof \Illuminate\Support\Collection ? $chatItems : collect($chatItems);
    $items = $items->sortBy('created_at');

    $shouldAutoOpen = session('open_chat') === $chatKey
        || $errors->has('comment')
        || $errors->has('topic')
        || $errors->has('thesis_version_id');

    $showComposeExtras = filled(old('topic'))
        || filled(old('thesis_version_id'))
        || $errors->has('topic')
        || $errors->has('thesis_version_id');

    $messageCount = $items->count();
    $participantCount = $items->pluck('user_id')->filter()->unique()->count();
    $chatThesisTitle = \Illuminate\Support\Str::limit((string) ($thesis->title ?? 'Thesis Discussion'), 44);
@endphp

<style>
    body.wa-chat-open {
        overflow: hidden;
    }

    .wa-chat-fab {
        position: fixed;
        right: 24px;
        bottom: 24px;
        z-index: 2050;
        border-radius: 999px;
        padding: 12px 18px;
        box-shadow: 0 14px 28px rgba(15, 20, 27, 0.22);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .wa-chat-overlay {
        position: fixed;
        inset: 0;
        z-index: 2060;
        background: rgba(6, 12, 22, 0.48);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .wa-chat-panel {
        width: min(100%, 1080px);
        height: min(86vh, 760px);
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid rgba(20, 31, 51, 0.08);
        background: #f4f7fb;
        box-shadow: 0 28px 65px rgba(8, 14, 24, 0.35);
        display: grid;
        grid-template-rows: auto 1fr auto;
    }

    .wa-chat-head {
        position: relative;
        overflow: hidden;
        background:
            linear-gradient(120deg, #0f4dbf 0%, #2a68d8 56%, #4e7ee3 100%);
        color: #f2f7ff;
        padding: 12px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .wa-chat-head::before,
    .wa-chat-head::after {
        content: "";
        position: absolute;
        border-radius: 999px;
        pointer-events: none;
    }

    .wa-chat-head::before {
        width: 180px;
        height: 180px;
        right: -70px;
        top: -92px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, transparent 72%);
    }

    .wa-chat-head::after {
        width: 140px;
        height: 140px;
        left: -68px;
        bottom: -90px;
        background: radial-gradient(circle, rgba(12, 32, 80, 0.22) 0%, transparent 74%);
    }

    .wa-head-main {
        position: relative;
        z-index: 1;
        min-width: 0;
        flex: 1;
        display: grid;
        gap: 6px;
    }

    .wa-head-brand {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 0;
    }

    .wa-head-icon {
        width: 30px;
        height: 30px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(255, 255, 255, 0.34);
        background: rgba(6, 22, 53, 0.2);
        color: #eaf2ff;
        flex: 0 0 auto;
    }

    .wa-head-copy {
        min-width: 0;
    }

    .wa-chat-title {
        margin: 0;
        color: #f6f9ff !important;
        font-size: 1.02rem;
        font-weight: 800;
        letter-spacing: 0.01em;
        line-height: 1.12;
    }

    .wa-chat-sub {
        color: rgba(235, 243, 255, 0.9);
        font-size: 0.74rem;
        line-height: 1.3;
        margin-top: 2px;
        display: block;
    }

    .wa-head-meta {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .wa-head-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.25);
        background: rgba(8, 24, 54, 0.26);
        color: #e9f1ff;
        font-size: 0.64rem;
        font-weight: 700;
        line-height: 1;
        padding: 4px 9px;
    }

    .wa-head-chip i {
        font-size: 0.68rem;
    }

    .wa-head-close {
        position: relative;
        z-index: 1;
        border: 1px solid rgba(255, 255, 255, 0.42);
        border-radius: 12px;
        background: rgba(6, 22, 53, 0.18);
        color: #f0f6ff;
        min-height: 34px;
        min-width: 34px;
        padding: 6px 11px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: 0.69rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        transition: all 0.18s ease;
    }

    .wa-head-close:hover {
        border-color: rgba(255, 255, 255, 0.62);
        background: rgba(6, 22, 53, 0.34);
        color: #ffffff;
    }

    .wa-chat-list {
        overflow-y: auto;
        padding: 14px 16px;
        background-color: #dbe5ef;
        background-image:
            radial-gradient(circle at 25px 25px, rgba(255, 255, 255, 0.25) 2px, transparent 0),
            radial-gradient(circle at 75px 75px, rgba(255, 255, 255, 0.2) 1.5px, transparent 0);
        background-size: 100px 100px;
    }

    .wa-msg {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 6px;
        margin-bottom: 12px;
    }

    .wa-msg.is-mine {
        align-items: flex-end;
    }

    .wa-bubble {
        max-width: min(84%, 700px);
        border-radius: 12px;
        padding: 9px 11px 8px;
        background: #ffffff;
        color: #12253f;
        border: 1px solid #dae6f8;
        box-shadow: 0 4px 12px rgba(15, 20, 27, 0.1);
        position: relative;
    }

    .wa-msg.is-mine .wa-bubble {
        background: #d9fdd3;
        border-color: #bbefb2;
    }

    .wa-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 3px;
    }

    .wa-meta-right {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .wa-author {
        font-size: 0.76rem;
        font-weight: 700;
        color: #1f3b64;
    }

    .wa-time {
        font-size: 0.68rem;
        color: #6f829d;
        white-space: nowrap;
    }

    .wa-menu {
        position: relative;
    }

    .wa-menu-btn {
        width: 24px;
        height: 24px;
        border-radius: 999px;
        border: 1px solid transparent;
        background: transparent;
        color: #58708e;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0;
        transition: all 0.16s ease;
    }

    .wa-msg.is-mine:hover .wa-menu-btn,
    .wa-menu-btn:focus-visible,
    .wa-menu-btn[aria-expanded="true"] {
        opacity: 1;
        background: rgba(20, 38, 62, 0.08);
        border-color: rgba(20, 38, 62, 0.14);
        color: #1c3556;
    }

    .wa-menu-panel {
        position: absolute;
        top: calc(100% + 6px);
        right: 0;
        min-width: 130px;
        padding: 6px;
        border-radius: 10px;
        border: 1px solid #d8e5f7;
        background: #ffffff;
        box-shadow: 0 10px 24px rgba(15, 20, 27, 0.14);
        z-index: 6;
    }

    .wa-menu-item {
        width: 100%;
        border: 0;
        background: transparent;
        color: #2f455f;
        border-radius: 8px;
        text-align: left;
        font-size: 0.74rem;
        font-weight: 700;
        line-height: 1;
        padding: 8px 9px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }

    .wa-menu-item:hover {
        background: #eef5ff;
        color: #1d4ed8;
    }

    .wa-menu-item.danger:hover {
        background: #fff2f0;
        color: #b42318;
    }

    .wa-tags {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 5px;
    }

    .wa-topic-wrap {
        margin-bottom: 6px;
    }

    .wa-topic-pill {
        display: inline-flex;
        align-items: flex-start;
        gap: 6px;
        max-width: 100%;
        border-radius: 999px;
        border: 1px solid #d4e3f8;
        background: #eef4ff;
        color: #224674;
        font-size: 0.73rem;
        font-weight: 700;
        line-height: 1.25;
        padding: 5px 10px;
    }

    .wa-topic-pill i {
        margin-top: 1px;
        font-size: 0.72rem;
        flex: 0 0 auto;
    }

    .wa-topic-pill span {
        display: block;
        word-break: break-word;
    }

    .wa-msg.is-mine .wa-topic-pill {
        border-color: #bde5bf;
        background: #e8f8e4;
        color: #255f34;
    }

    .wa-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border-radius: 999px;
        border: 1px solid #d6e3f4;
        background: #f6faff;
        color: #48617d;
        font-size: 0.68rem;
        font-weight: 700;
        line-height: 1;
        padding: 4px 9px;
    }

    .wa-text {
        margin: 0;
        font-size: 0.86rem;
        line-height: 1.45;
        color: #10233e;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .wa-foot {
        margin-top: 5px;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 0.66rem;
        color: #6f829d;
        text-transform: lowercase;
    }

    .wa-edited {
        font-weight: 700;
    }

    .wa-edit-form {
        width: min(84%, 700px);
        border: 1px solid #cde0ff;
        border-radius: 10px;
        background: #f5f9ff;
        padding: 10px;
    }

    .wa-edit-grid {
        display: grid;
        gap: 8px;
    }

    .wa-edit-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    .wa-compose {
        border-top: 1px solid rgba(17, 31, 51, 0.09);
        background: #f9fbff;
        padding: 12px 14px;
    }

    .wa-compose-main {
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 8px;
        align-items: center;
        margin-bottom: 8px;
    }

    .wa-compose-toggle {
        width: 40px;
        height: 40px;
        border-radius: 999px;
        border: 1px solid #d1e1f5;
        background: #ffffff;
        color: #2b4f7c;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.18s ease;
    }

    .wa-compose-toggle:hover,
    .wa-compose-toggle[aria-expanded="true"] {
        border-color: #bcd2f1;
        background: #ecf4ff;
        color: #1d4ed8;
    }

    .wa-compose-toggle i {
        transition: transform 0.18s ease;
    }

    .wa-compose-toggle[aria-expanded="true"] i {
        transform: rotate(45deg);
    }

    .wa-compose-field {
        border-radius: 999px;
        border: 1px solid #ccddf3;
        background: #ffffff;
        min-height: 40px;
        padding: 0 14px;
        font-size: 0.86rem;
    }

    .wa-compose-send {
        width: 40px;
        height: 40px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .wa-compose-extra {
        border: 1px solid #d6e4f7;
        border-radius: 10px;
        background: #f3f8ff;
        padding: 10px;
    }

    .wa-compose-label {
        display: block;
        margin-bottom: 5px;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #5b7391;
    }

    .wa-empty {
        text-align: center;
        color: #5d7592;
        padding: 36px 12px;
    }

    .wa-empty i {
        font-size: 1.5rem;
        color: #8aa1bc;
        display: inline-block;
        margin-bottom: 8px;
    }

    html.app-skin-dark .wa-chat-panel {
        border-color: rgba(255, 255, 255, 0.12);
        background: #131d2b;
    }

    html.app-skin-dark .wa-chat-head {
        background:
            linear-gradient(120deg, #173865 0%, #24518f 56%, #3363a8 100%);
        color: #ebf2ff;
    }

    html.app-skin-dark .wa-head-icon,
    html.app-skin-dark .wa-head-chip,
    html.app-skin-dark .wa-head-close {
        border-color: rgba(195, 215, 246, 0.35);
        background: rgba(6, 19, 43, 0.36);
    }

    html.app-skin-dark .wa-head-chip,
    html.app-skin-dark .wa-head-close {
        color: #e6efff;
    }

    html.app-skin-dark .wa-chat-sub {
        color: rgba(223, 236, 255, 0.92);
    }

    html.app-skin-dark .wa-chat-list {
        background-color: #0f1a29;
        background-image:
            radial-gradient(circle at 25px 25px, rgba(255, 255, 255, 0.06) 2px, transparent 0),
            radial-gradient(circle at 75px 75px, rgba(255, 255, 255, 0.05) 1.5px, transparent 0);
    }

    html.app-skin-dark .wa-bubble {
        background: #182538;
        border-color: rgba(255, 255, 255, 0.12);
        color: #dbe7f5;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.24);
    }

    html.app-skin-dark .wa-msg.is-mine .wa-bubble {
        background: #23462e;
        border-color: rgba(138, 222, 152, 0.4);
    }

    html.app-skin-dark .wa-author,
    html.app-skin-dark .wa-text {
        color: #dbe7f5;
    }

    html.app-skin-dark .wa-time,
    html.app-skin-dark .wa-foot {
        color: #9fb2c9;
    }

    html.app-skin-dark .wa-chip {
        color: #c6d3e5;
        background: #1f2c3f;
        border-color: rgba(255, 255, 255, 0.14);
    }

    html.app-skin-dark .wa-topic-pill {
        border-color: rgba(136, 171, 224, 0.38);
        background: #1f3047;
        color: #d1e2f8;
    }

    html.app-skin-dark .wa-msg.is-mine .wa-topic-pill {
        border-color: rgba(103, 214, 155, 0.42);
        background: #1e4b32;
        color: #d3f6df;
    }

    html.app-skin-dark .wa-menu-btn {
        color: #9fb2c9;
    }

    html.app-skin-dark .wa-msg.is-mine:hover .wa-menu-btn,
    html.app-skin-dark .wa-menu-btn:focus-visible,
    html.app-skin-dark .wa-menu-btn[aria-expanded="true"] {
        color: #dce6f4;
        background: rgba(255, 255, 255, 0.11);
        border-color: rgba(255, 255, 255, 0.18);
    }

    html.app-skin-dark .wa-menu-panel {
        border-color: rgba(255, 255, 255, 0.14);
        background: #1a2a40;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.35);
    }

    html.app-skin-dark .wa-menu-item {
        color: #d0dced;
    }

    html.app-skin-dark .wa-menu-item:hover {
        background: rgba(48, 88, 168, 0.35);
        color: #9fc1ff;
    }

    html.app-skin-dark .wa-menu-item.danger:hover {
        color: #ffb4ac;
        background: rgba(170, 40, 40, 0.34);
    }

    html.app-skin-dark .wa-edit-form {
        background: #1a2a40;
        border-color: rgba(110, 154, 242, 0.4);
    }

    html.app-skin-dark .wa-compose {
        border-top-color: rgba(255, 255, 255, 0.12);
        background: #151f2e;
    }

    html.app-skin-dark .wa-compose-toggle {
        color: #c5d3e4;
        background: #1c2a3d;
        border-color: rgba(255, 255, 255, 0.14);
    }

    html.app-skin-dark .wa-compose-toggle:hover,
    html.app-skin-dark .wa-compose-toggle[aria-expanded="true"] {
        color: #9fc1ff;
        background: rgba(48, 88, 168, 0.35);
        border-color: rgba(110, 154, 242, 0.45);
    }

    html.app-skin-dark .wa-compose-field {
        color: #dce6f4;
        background: #1c2a3d;
        border-color: rgba(255, 255, 255, 0.14);
    }

    html.app-skin-dark .wa-compose-extra {
        background: #172436;
        border-color: rgba(255, 255, 255, 0.14);
    }

    html.app-skin-dark .wa-compose-label {
        color: #9fb2c9;
    }

    html.app-skin-dark .wa-empty {
        color: #9fb2c9;
    }

    @media (max-width: 767px) {
        .wa-chat-fab {
            right: 12px;
            bottom: 12px;
            padding: 10px 14px;
        }

        .wa-chat-overlay {
            padding: 0;
            align-items: stretch;
        }

        .wa-chat-panel {
            width: 100%;
            height: 100dvh;
            max-height: 100dvh;
            border-radius: 0;
            border: 0;
        }

        .wa-bubble,
        .wa-edit-form {
            width: min(100%, 100%);
            max-width: 95%;
        }

        .wa-menu-btn {
            opacity: 1;
        }

        .wa-compose-main {
            grid-template-columns: auto 1fr auto;
            gap: 6px;
        }

        .wa-chat-head {
            padding: 10px;
            align-items: flex-start;
        }

        .wa-chat-sub {
            display: none;
        }

        .wa-head-chip.hide-mobile {
            display: none;
        }

        .wa-head-close {
            padding: 6px 8px;
            border-radius: 10px;
        }

        .wa-head-close span {
            display: none;
        }

        .wa-compose-extra .row > [class*="col-"] {
            margin-bottom: 8px;
        }
    }
</style>

<button type="button" class="btn btn-primary wa-chat-fab" data-wa-chat-toggle="{{ $chatKey }}" data-wa-chat-button aria-expanded="false">
    <i class="feather-message-circle me-1"></i>
    Chat
</button>

<div class="wa-chat-overlay d-none" data-wa-chatbox="{{ $chatKey }}" role="dialog" aria-modal="true" aria-label="Thesis chat dialog">
    <div class="wa-chat-panel">
        <header class="wa-chat-head">
            <div class="wa-head-main">
                <div class="wa-head-brand">
                    <span class="wa-head-icon"><i class="feather-message-circle"></i></span>
                    <div class="wa-head-copy">
                        <h5 class="wa-chat-title">Thesis Chat</h5>
                        <span class="wa-chat-sub">Focused discussion for faster reviews and decisions</span>
                    </div>
                </div>

                <div class="wa-head-meta">
                    <span class="wa-head-chip">
                        <i class="feather-book-open"></i>
                        {{ $chatThesisTitle }}
                    </span>
                    <span class="wa-head-chip hide-mobile">
                        <i class="feather-users"></i>
                        {{ $participantCount }} {{ \Illuminate\Support\Str::plural('member', $participantCount) }}
                    </span>
                    <span class="wa-head-chip hide-mobile">
                        <i class="feather-message-square"></i>
                        {{ $messageCount }} {{ \Illuminate\Support\Str::plural('message', $messageCount) }}
                    </span>
                </div>
            </div>

            <button type="button" class="wa-head-close" data-wa-chat-toggle="{{ $chatKey }}" aria-label="Close chat">
                <i class="feather-x"></i>
                <span>Close</span>
            </button>
        </header>

        <section class="wa-chat-list" data-wa-chat-list>
            @forelse($items as $feedback)
                @php
                    $isMine = $feedback->user_id === auth()->id();
                    $isEdited = $feedback->updated_at && $feedback->updated_at->gt($feedback->created_at);
                    $messageTitle = trim((string) ($feedback->topic ?? ''));
                    $hasMessageTitle = $messageTitle !== '' && $messageTitle !== '-';
                @endphp
                <article class="wa-msg {{ $isMine ? 'is-mine' : '' }}" data-wa-message="{{ $feedback->id }}">
                    <div class="wa-bubble">
                        <div class="wa-meta">
                            <span class="wa-author">{{ $feedback->user->name ?? 'User' }}</span>
                            <span class="wa-meta-right">
                                <span class="wa-time">{{ $feedback->created_at?->format('h:i A') }}</span>
                                @if($isMine)
                                    <div class="wa-menu">
                                        <button type="button" class="wa-menu-btn" data-wa-menu-toggle="{{ $feedback->id }}" aria-expanded="false" aria-label="Message actions">
                                            <i class="feather-more-vertical"></i>
                                        </button>
                                        <div class="wa-menu-panel d-none" data-wa-menu-panel="{{ $feedback->id }}">
                                            <button type="button" class="wa-menu-item" data-wa-edit-open="{{ $feedback->id }}">
                                                <i class="feather-edit-3"></i>
                                                Edit message
                                            </button>
                                            <form method="POST" action="{{ route('feedback.destroy', $feedback) }}" onsubmit="return confirm('Delete this message?');" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="wa-menu-item danger">
                                                    <i class="feather-trash-2"></i>
                                                    Delete message
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </span>
                        </div>

                        @if($hasMessageTitle)
                            <div class="wa-topic-wrap">
                                <div class="wa-topic-pill" title="{{ $messageTitle }}">
                                    <i class="feather-tag"></i>
                                    <span>{{ $messageTitle }}</span>
                                </div>
                            </div>
                        @endif

                        @if($feedback->thesisVersion)
                            <div class="wa-tags">
                                <span class="wa-chip">
                                    <i class="feather-file-text"></i>
                                    {{ $feedback->thesisVersion->unit?->name ?? 'Unit' }} {{ $feedback->thesisVersion->unit_number ?? '' }}
                                </span>
                            </div>
                        @endif

                        <p class="wa-text" data-wa-message-text>{{ $feedback->comment }}</p>

                        <div class="wa-foot">
                            @if($isEdited)
                                <span class="wa-edited">edited</span>
                            @endif
                            <span>{{ $feedback->created_at?->diffForHumans() }}</span>
                        </div>
                    </div>

                    @if($isMine)
                        <form method="POST" action="{{ route('feedback.update', $feedback) }}" class="wa-edit-form d-none" data-wa-edit-form="{{ $feedback->id }}">
                            @csrf
                            @method('PATCH')
                            <div class="wa-edit-grid">
                                <input type="text" name="topic" class="form-control" value="{{ $feedback->topic }}" placeholder="Title (optional)">
                                <textarea name="comment" rows="3" class="form-control" required>{{ $feedback->comment }}</textarea>
                                <div class="wa-edit-actions">
                                    <button type="button" class="btn btn-light btn-sm" data-wa-edit-cancel="{{ $feedback->id }}">Cancel</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                </div>
                            </div>
                        </form>
                    @endif
                </article>
            @empty
                <div class="wa-empty">
                    <i class="feather-message-square"></i>
                    <div>No messages yet. Start the conversation.</div>
                </div>
            @endforelse
        </section>

        <footer class="wa-compose">
            @if($errors->has('comment') || $errors->has('topic') || $errors->has('thesis_version_id'))
                <div class="alert alert-danger py-2 mb-2">Please fix the chat message fields and try again.</div>
            @endif

            <form method="POST" action="{{ route('thesis.feedback.store', $thesis) }}">
                @csrf
                <div class="wa-compose-main">
                    <button type="button" class="wa-compose-toggle" data-wa-compose-toggle aria-expanded="{{ $showComposeExtras ? 'true' : 'false' }}" aria-label="More options">
                        <i class="feather-plus"></i>
                    </button>

                    <input type="text" name="comment" class="form-control wa-compose-field" placeholder="Type a message..." value="{{ old('comment') }}" required data-wa-focus-input>

                    <button type="submit" class="btn btn-primary wa-compose-send" aria-label="Send message">
                        <i class="feather-send"></i>
                    </button>
                </div>

                <div class="wa-compose-extra {{ $showComposeExtras ? '' : 'd-none' }}" data-wa-compose-extra>
                    <div class="row g-2">
                        <div class="col-12 col-md-4">
                            <label class="wa-compose-label" for="wa-topic-{{ $chatKey }}">Title</label>
                            <input id="wa-topic-{{ $chatKey }}" type="text" name="topic" class="form-control" placeholder="Optional title" value="{{ old('topic') }}">
                        </div>
                        <div class="col-12 col-md-8">
                            <label class="wa-compose-label" for="wa-version-{{ $chatKey }}">Link to Unit</label>
                            <select id="wa-version-{{ $chatKey }}" name="thesis_version_id" class="form-select">
                                <option value="">General</option>
                                @foreach($thesis->versions->sortBy('version_number') as $version)
                                    <option value="{{ $version->id }}" @selected((string) old('thesis_version_id') === (string) $version->id)>
                                        {{ $version->unit_label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </footer>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const chatKey = @json($chatKey);
        const overlay = document.querySelector(`[data-wa-chatbox="${chatKey}"]`);

        if (!overlay) {
            return;
        }

        const body = document.body;
        const toggles = document.querySelectorAll(`[data-wa-chat-toggle="${chatKey}"]`);
        const list = overlay.querySelector('[data-wa-chat-list]');
        const shouldAutoOpen = @json($shouldAutoOpen);
        const composeToggle = overlay.querySelector('[data-wa-compose-toggle]');
        const composeExtra = overlay.querySelector('[data-wa-compose-extra]');

        const setComposeOpen = (open) => {
            if (!composeToggle || !composeExtra) {
                return;
            }

            composeExtra.classList.toggle('d-none', !open);
            composeToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        };

        const setOpen = (open) => {
            overlay.classList.toggle('d-none', !open);
            body.classList.toggle('wa-chat-open', open);

            toggles.forEach((button) => {
                button.setAttribute('aria-expanded', open ? 'true' : 'false');
                if (button.hasAttribute('data-wa-chat-button')) {
                    button.innerHTML = open
                        ? '<i class="feather-x me-1"></i> Close'
                        : '<i class="feather-message-circle me-1"></i> Chat';
                }
            });

            if (open && list) {
                requestAnimationFrame(() => {
                    list.scrollTop = list.scrollHeight;
                });

                const input = overlay.querySelector('[data-wa-focus-input]');
                if (input) {
                    input.focus();
                }
            }

            if (!open) {
                hideAllEditForms();
                closeAllMenus();
            }
        };

        const closeAllMenus = () => {
            overlay.querySelectorAll('[data-wa-menu-panel]').forEach((menu) => {
                menu.classList.add('d-none');
            });

            overlay.querySelectorAll('[data-wa-menu-toggle]').forEach((toggle) => {
                toggle.setAttribute('aria-expanded', 'false');
            });
        };

        const hideAllEditForms = () => {
            overlay.querySelectorAll('[data-wa-edit-form]').forEach((form) => {
                form.classList.add('d-none');
            });

            overlay.querySelectorAll('[data-wa-message-text]').forEach((text) => {
                text.classList.remove('d-none');
            });
        };

        const openEditForm = (id) => {
            hideAllEditForms();

            const form = overlay.querySelector(`[data-wa-edit-form="${id}"]`);
            const message = overlay.querySelector(`[data-wa-message="${id}"] [data-wa-message-text]`);
            if (!form || !message) {
                return;
            }

            closeAllMenus();

            message.classList.add('d-none');
            form.classList.remove('d-none');
            const textarea = form.querySelector('textarea[name="comment"]');
            if (textarea) {
                textarea.focus();
                textarea.setSelectionRange(textarea.value.length, textarea.value.length);
            }
        };

        toggles.forEach((button) => {
            button.addEventListener('click', () => {
                const isOpening = overlay.classList.contains('d-none');
                setOpen(isOpening);
            });
        });

        overlay.addEventListener('click', (event) => {
            if (event.target === overlay) {
                setOpen(false);
                return;
            }

            const composeToggleButton = event.target.closest('[data-wa-compose-toggle]');
            if (composeToggleButton) {
                const isOpening = composeExtra ? composeExtra.classList.contains('d-none') : false;
                setComposeOpen(isOpening);
                return;
            }

            const menuToggleButton = event.target.closest('[data-wa-menu-toggle]');
            if (menuToggleButton) {
                const id = menuToggleButton.getAttribute('data-wa-menu-toggle');
                const menu = overlay.querySelector(`[data-wa-menu-panel="${id}"]`);
                if (!menu) {
                    return;
                }

                const willOpen = menu.classList.contains('d-none');
                closeAllMenus();
                menu.classList.toggle('d-none', !willOpen);
                menuToggleButton.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
                return;
            }

            const editOpenButton = event.target.closest('[data-wa-edit-open]');
            if (editOpenButton) {
                const id = editOpenButton.getAttribute('data-wa-edit-open');
                openEditForm(id);
                return;
            }

            const editCancelButton = event.target.closest('[data-wa-edit-cancel]');
            if (editCancelButton) {
                hideAllEditForms();
                return;
            }

            if (!event.target.closest('.wa-menu')) {
                closeAllMenus();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !overlay.classList.contains('d-none')) {
                const hasMenuOpen = [...overlay.querySelectorAll('[data-wa-menu-panel]')]
                    .some((menu) => !menu.classList.contains('d-none'));

                if (hasMenuOpen) {
                    closeAllMenus();
                    return;
                }

                setOpen(false);
            }
        });

        if (shouldAutoOpen) {
            setOpen(true);
        }
    });
</script>
