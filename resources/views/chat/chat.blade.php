<!DOCTYPE html>
<html lang="en">
@include('layouts.header')

    <body>
    @include('layouts.bar')

        <div class="container mx-auto p-4">
        <div class="grid grid-cols-4 gap-4">
            <div class="col-span-1 bg-white p-4 rounded shadow">
            {{-- UPDATED --}}
            <h3 class="font-bold mb-2"><i class="fa fa-comments"></i> Chatting with</h3>
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

            <form id="message-form" class="flex" onsubmit="return false;">
                @csrf
                <input type="hidden" id="receiver_id" value="{{ $other->id }}">
                <input type="hidden" id="chatId" value="{{ $chatId }}">
                <input id="body" class="flex-1 border rounded p-2 mr-2" placeholder="Type a message..." autocomplete="off" />
                {{-- UPDATED --}}
                <button id="sendBtn" class="btn btn-primary p-2 rounded"><i class="fa fa-paper-plane"></i> Send</button>
            </form>
            </div>
        </div>
        </div>

        <script>
        // ... (script contents unchanged) ...
        </script>
    </body>
</html>
