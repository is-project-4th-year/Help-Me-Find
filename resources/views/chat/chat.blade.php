<!DOCTYPE html>
<html lang="en">
@include('layouts.header')
@vite(['resources/css/chat.css'])
@vite(['resources/js/chat.js'])

<body style="background-color: var(--background);">
    @include('layouts.bar')

    <div class="chat-container">

        <div class="chat-header">
            <div class="chat-header-content">
                <div class="chat-header-user">
                    {{-- <a href="{{ route('chat.list') }}" class="btn-icon" style="text-decoration: none;">
                        <i class="fa fa-arrow-left"></i>
                    </a> --}}
                    <div class="chat-avatar-fallback">
                        {{ strtoupper(substr($other->firstName, 0, 1) . substr($other->lastName, 0, 1)) }}
                    </div>
                    <div class="chat-user-info">
                        <h4>{{ $other->firstName }} {{ $other->lastName }}</h4>
                        <p>Active now</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="messages" class="chat-messages">
            @foreach($messages as $m)
                @php
                    $isMe = $m->sender_id == auth()->id();
                @endphp
                <div class="message-bubble {{ $isMe ? 'sender-me' : 'sender-other' }}">
                    <div class="message-content {{ $isMe ? 'sender-me' : 'sender-other' }}">
                        <p>{{ $m->body }}</p>
                        <p class="message-timestamp {{ $isMe ? 'sender-me' : 'sender-other' }}">
                            {{ $m->created_at->format('h:i A') }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="chat-input-form">
            {{--
              UPDATED: Added data-* attributes to pass values to chat.js
            --}}
            <form id="message-form"
                  class="flex"
                  onsubmit="return false;"
                  data-send-url="{{ route('chat.send') }}"
                  data-csrf-token="{{ csrf_token() }}"
                  data-auth-id="{{ auth()->id() }}"
            >
                @csrf
                <input type="hidden" id="receiver_id" value="{{ $other->id }}">
                <input type="hidden" id="chatId" value="{{ $chatId }}">

                <input id="body" class="input-field" placeholder="Type a message..." autocomplete="off" />

                <button id="sendBtn" class="btn-icon-send">
                    <i class="fa fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- REMOVED: Inline script block is gone --}}

    {{--
      ADDED: Load the new external chat.js file.
      The 'defer' attribute ensures it runs after the HTML is parsed.
    --}}
    {{-- <script src="{{ asset('js/chat.js') }}" defer></script> --}}
</body>
</html>
