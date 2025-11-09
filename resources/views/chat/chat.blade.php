@extends('layouts.app')

@section('content')
    <!--
      This layout uses flex-col and a fixed height to make the chat area scrollable
      while the header and footer are sticky.
      h-[calc(100vh-10rem)] = 100% viewport height - 4rem (navbar) - 6rem (padding)
    -->
    <div class="flex flex-col h-[calc(100vh-10rem)] bg-card rounded-lg shadow-md border border-border">
        <!-- Chat Header -->
        <div class="p-4 border-b border-border flex items-center space-x-3">
            <!-- Placeholder Avatar -->
            <div class="w-10 h-10 rounded-full bg-secondary flex items-center justify-center">
                <span class="text-lg font-medium text-secondary-foreground">
                    {{ $chat->otherUser->name[0] }}
                </span>
            </div>
            <div>
                <h3 class="text-base font-semibold text-foreground">{{ $chat->otherUser->name }}</h3>
                <!-- Add online/offline status here if available -->
            </div>
        </div>

        <!-- Chat Messages -->
        <div id="chat-box" class="flex-1 overflow-y-auto p-6 space-y-4">
            @foreach ($messages as $message)
                <div class="flex {{ $message->user_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class_name_to_replace="p-3 rounded-lg max-w-md {{ $message->user_id == auth()->id() ? 'bg-primary text-primary-foreground' : 'bg-muted text-foreground' }}">
                        <p class="text-sm">{{ $message->message }}</p>
                        <span class="text-xs opacity-70 block text-right mt-1">
                            {{ $message->created_at->format('h:i A') }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Message Input Form -->
        <div class="p-4 border-t border-border bg-background rounded-b-lg">
            <form id="message-form" action="{{ route('chat.send', $chat->id) }}" method="POST" class="flex space-x-3">
                @csrf
                <input type="hidden" name="chat_id" value="{{ $chat->id }}">
                <input
                    type="text"
                    id="message-input"
                    name="message"
                    placeholder="Type a message..."
                    autocomplete="off"
                    class="flex h-9 w-full rounded-md border border-border bg-input px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                >
                <button type_name_to_replace="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
                    Send
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chatBox = document.getElementById('chat-box');
        // Scroll to the bottom
        chatBox.scrollTop = chatBox.scrollHeight;

        const form = document.getElementById('message-form');
        const input = document.getElementById('message-input');

        // Pusher/Echo integration
        if (typeof Echo !== 'undefined') {
            Echo.private('chat.{{ $chat->id }}')
                .listen('MessageSent', (e) => {
                    const messageEl = document.createElement('div');
                    const isAuthUser = e.message.user_id == {{ auth()->id() }};

                    messageEl.classList.add('flex', isAuthUser ? 'justify-end' : 'justify-start');

                    const innerHtml = `
                        <div class="p-3 rounded-lg max-w-md ${isAuthUser ? 'bg-primary text-primary-foreground' : 'bg-muted text-foreground'}">
                            <p class="text-sm">${e.message.message}</p>
                            <span class="text-xs opacity-70 block text-right mt-1">
                                ${new Date(e.message.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}
                            </span>
                        </div>
                    `;
                    messageEl.innerHTML = innerHtml;

                    chatBox.appendChild(messageEl);
                    chatBox.scrollTop = chatBox.scrollHeight;
                });
        }

        // Prevent page reload on form submit
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    input.value = ''; // Clear input
                } else {
                    // Handle error (e.g., show a small error message)
                    console.error('Failed to send message');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>
@endpush
