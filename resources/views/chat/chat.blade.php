<!DOCTYPE html>
<html lang="en">
@include('layouts.header')

    <body>
    @include('layouts.bar')

        <div class="container mx-auto p-4">
        <div class="grid grid-cols-4 gap-4">
            <div class="col-span-1 bg-white p-4 rounded shadow">
            <h3 class="font-bold mb-2">Chatting with</h3>
            <p>{{ $other->firstName }} {{ $other->lastName }}</p>
            </div>

            <div class="col-span-3 bg-white p-4 rounded shadow flex flex-col h-[70vh]">
            <div id="messages" class="flex-1 overflow-auto mb-4 p-3" style="background:#f9fafb;">
                @foreach($messages as $m)
                <div class="mb-2">
                    <strong>{{ $m->sender_id == auth()->id() ? 'You' : $other->firstName }}</strong>:
                    <span>{{ $m->body }}</span>
                    <div><small class="text-gray-400">{{ $m->created_at }}</small></div>
                </div>
                @endforeach
            </div>

            {{--
              - Add data-* attributes to pass server-side data to our JS file.
              - Change onsubmit to return true to allow Enter key submission (handled in JS).
            --}}
            <form id="message-form" class="flex"
                  onsubmit="return false;"
                  data-user-id="{{ auth()->id() }}"
                  data-chat-id="{{ $chatId }}"
                  data-send-route="{{ route('chat.send') }}">
                @csrf
                <input type="hidden" id="receiver_id" value="{{ $other->id }}">
                <input id="body" class="flex-1 border rounded p-2 mr-2" placeholder="Type a message..." autocomplete="off" />
                <button id="sendBtn" type="button" class="btn btn-primary p-2 rounded">Send</button> {{-- Changed type to "button" --}}
            </form>
            </div>
        </div>
        </div>

        {{--
          - Load app.js (for Bootstrap, Echo, Axios) and our new chat.js
          - This replaces the old inline <script> block
        --}}
        @vite(['resources/js/app.js', 'resources/js/chat.js'])
    </body>
</html>
