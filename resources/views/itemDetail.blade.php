@extends('layouts.app')

@section('content')
    <div class="bg-card p-6 md:p-8 rounded-lg shadow-md border border-border">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
            <!-- Image Column -->
            <div class="md:col-span-1">
                <img src="{{ $item->image_url ? asset('storage/' . $item->image_url) : asset('images/lost-and-found.png') }}"
                     alt="{{ $item->item_name }}"
                     class="w-full h-auto object-cover rounded-lg border border-border">
            </div>

            <!-- Details Column -->
            <div class="md:col-span-2 space-y-4">
                <h1 class="text-3xl font-bold text-foreground">{{ $item->item_name }}</h1>

                <p class="text-base text-muted-foreground">
                    {{ $item->description }}
                </p>

                <div class="border-t border-border pt-4 space-y-2">
                    <div class="flex">
                        <span class="font-medium text-foreground w-32">Status:</span>
                        <span class="text-muted-foreground">{{ $item->status == 'lost' ? 'Reported Lost' : 'Reported Found' }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium text-foreground w-32">Category:</span>
                        <span class="text-muted-foreground">{{ $item->category }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium text-foreground w-32">
                            {{ $item->status == 'lost' ? 'Date Lost:' : 'Date Found:' }}
                        </span>
                        <span class="text-muted-foreground">{{ \Carbon\Carbon::parse($item->date_found)->format('F j, Y') }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium text-foreground w-32">
                             {{ $item->status == 'lost' ? 'Last Seen:' : 'Location Found:' }}
                        </span>
                        <span class="text-muted-foreground">{{ $item->location_found }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium text-foreground w-32">Reported By:</span>
                        <span class="text-muted-foreground">{{ $item->user->name }}</span>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="pt-4">
                    @if (auth()->id() == $item->user_id)
                        <p class="text-sm text-muted-foreground italic">This is your item.</p>
                        <!-- Maybe add an "Edit" or "Mark as Found" button here in the future -->
                    @else
                        <a href="{{ route('chat.start', $item->user->id) }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
                            <!-- Message-Circle Icon SVG -->
                            <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            Start Chat with {{ $item->user->name }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
