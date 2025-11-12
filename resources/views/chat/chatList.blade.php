<!DOCTYPE html>
<html lang="en">
@include('layouts.header')
@vite(['resources/css/chatList.css'])

<body style="background-color: var(--background);">
    @include('layouts.bar')

    <div class="chat-page-container">

        <div class="chat-list-header">
            <h1>Messages</h1>
            <p>Communicate with item finders and owners.</p>
        </div>

        {{-- <div class="chat-list-search-card">
            <div class="chat-list-search">
                <div class="chat-list-search-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" placeholder="Search conversations...">
            </div>
        </div> --}}

        <div class="chat-list-items-wrapper">
            @forelse($users as $u)
                <a href="{{ route('chat.with', $u->id) }}" class="chat-list-card">
                    <div class="chat-list-avatar">
                        {{ substr($u->firstName, 0, 1) . substr($u->lastName, 0, 1) }}
                    </div>

                    <div class="chat-list-content">
                        <div class="chat-list-header-row">
                            <h4>{{ $u->firstName }} {{ $u->lastName }}</h4>
                            <span class="timestamp">
                                {{-- $u->lastMessageTime ?? '1d ago' --}}
                                1d ago
                            </span>
                        </div>
                        <p class="chat-list-message">
                            {{-- $u->lastMessageSnippet ?? $u->email --}}
                            This is a placeholder for the last message...
                        </p>
                    </div>

                    @if(false)
                    {{-- @if($u->unread_count > 0) --}}
                        <span class="chat-list-badge">
                            {{-- $u->unread_count --}}
                            1
                        </span>
                    @endif
                </a>

            @empty
                <div class="chat-list-empty-card">
                    <div class="chat-list-empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-3.86 8.25-8.625 8.25-4.764 0-8.625-3.694-8.625-8.25s3.86-8.25 8.625-8.25C17.14 3.75 21 7.444 21 12z" />
                        </svg>
                    </div>
                    <h3>No conversations found</h3>
                    <p>You haven't started any chats yet.</p>
                </div>
            @endforelse
        </div>

    </div>
</body>
</html>
