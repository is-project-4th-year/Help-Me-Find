<!DOCTYPE html>
<html lang="en">
@include('layouts.header')
@vite(['resources/css/message.css'])
@vite(['resources/js/message.js'])

<body style="background-color: var(--background);">
    @include('layouts.bar')

    <div class="chat-container">

        <div class="chat-header">
            <div class="chat-header-content">
                <div class="chat-header-user">
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

                        {{-- ** CHANGED: Render Body as HTML ** --}}
                        <div>{!! $m->body !!}</div>

                        <p class="message-timestamp {{ $isMe ? 'sender-me' : 'sender-other' }}">
                            {{ $m->created_at->format('h:i A') }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="chat-input-form">

            {{-- ** Image Preview Area (Hidden by JS after send) ** --}}
            @if(!empty($defaultImage))
                <div id="image-preview-area" style="padding: 10px; background: #f0f0f0; border-top: 1px solid #ddd; display: flex; align-items: center; gap: 10px;">
                    <img src="{{ asset($defaultImage) }}" style="height: 50px; width: 50px; object-fit: cover; border-radius: 5px;">
                    <span style="font-size: 0.8rem; color: #666;">Image will be embedded in message...</span>
                </div>
            @endif

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

                {{-- ** Hidden Image Path Input ** --}}
                <input type="hidden" id="image_path" value="{{ $defaultImage ?? '' }}">

                <input id="body"
                       class="input-field"
                       placeholder="Type a message..."
                       autocomplete="off"
                       value="{{ $defaultMessage ?? '' }}"
                />

                <button id="sendBtn" class="btn-icon-send">
                    <i class="fa fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</body>
</html>
