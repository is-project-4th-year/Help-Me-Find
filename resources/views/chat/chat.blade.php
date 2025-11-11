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

            <form id="message-form" class="flex" onsubmit="return false;">
                @csrf
                <input type="hidden" id="receiver_id" value="{{ $other->id }}">
                <input type="hidden" id="chatId" value="{{ $chatId }}">
                <input id="body" class="flex-1 border rounded p-2 mr-2" placeholder="Type a message..." autocomplete="off" />
                <button id="sendBtn" class="btn btn-primary p-2 rounded">Send</button>
            </form>
            </div>
        </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
        const userId = {{ auth()->id() }};
        const receiverId = document.getElementById('receiver_id').value;
        const chatId = document.getElementById('chatId').value;
        const messagesDiv = document.getElementById('messages');
        const bodyInput = document.getElementById('body');

        function appendMessage(data, me=false){
            const el = document.createElement('div');
            el.classList.add('mb-2');
            el.innerHTML = `<strong>${me ? 'You' : (data.sender.firstName || 'Other')}</strong>: ${data.body} <div><small class="text-gray-400">${data.created_at}</small></div>`;
            messagesDiv.appendChild(el);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        // Send message
        document.getElementById('sendBtn').addEventListener('click', async () => {
            const body = bodyInput.value.trim();
            if(!body) return;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
            const res = await axios.post("{{ route('chat.send') }}", {
                receiver_id: receiverId,
                body: body,
                chatId: chatId
            }, {
                headers: {'X-CSRF-TOKEN': token}
            });

            appendMessage({
                sender: {firstName: 'You'},
                body: body,
                created_at: res.data.message.created_at
            }, true);

            bodyInput.value = '';
            } catch (err) {
            console.error(err);
            alert('Message failed to send');
            }
        });

        // Setup Echo to listen for incoming messages for this chat
        // Echo is available via resources/js/bootstrap.js compiled into app.js
        window.Echo.private('chat.' + chatId)
            .listen('MessageSent', (e) => {
            // don't duplicate if this is our own message (you can check sender id)
            if(e.sender_id === userId) return;
            appendMessage({
                sender: e.sender,
                body: e.body,
                created_at: e.created_at
            }, false);
            });
        });
        </script>
    </body>
</html>
