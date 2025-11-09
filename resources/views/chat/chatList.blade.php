@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-semibold text-foreground mb-6">Chats</h2>

    <div class="bg-card rounded-lg shadow-md border border-border">
        <div class="space-y-2 p-4">
            @forelse ($chats as $chat)
                <a href="{{ route('chat.show', $chat->id) }}"
                   class="block p-4 rounded-lg hover:bg-accent hover:text-accent-foreground transition-colors">
                    <div class="flex items-center space-x-4">
                        <!-- Placeholder Avatar -->
                        <div class="w-12 h-12 rounded-full bg-secondary flex items-center justify-center">
                            <span class="text-xl font-medium text-secondary-foreground">
                                {{-- Get initials of the other user --}}
                                {{ $chat->otherUser->name[0] }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-foreground truncate">
                                {{ $chat->otherUser->name }}
                            </p>
                            <p class="text-sm text-muted-foreground truncate">
                                {{ $chat->messages->last()->message ?? 'No messages yet' }}
                            </p>
                        </div>
                        <span class="text-xs text-muted-foreground">
                            {{ $chat->messages->last() ? $chat->messages->last()->created_at->diffForHumans() : '' }}
                        </span>
                    </div>
                </a>
            @empty
                <p class="text-muted-foreground p-4 text-center">You have no chats.</p>
            @endforelse
        </div>
    </div>
@endsection
