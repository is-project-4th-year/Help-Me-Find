@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-foreground">Lost Items</h2>
        <a href="{{ route('report') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
            Report an Item
        </a>
    </div>

    <!-- Item Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($lostItems as $item)
            <div class="bg-card rounded-lg shadow-md overflow-hidden border border-border">
                <!-- Item Image -->
                <img src="{{ $item->image_url ? asset('storage/' . $item->image_url) : asset('images/lost-and-found.png') }}"
                     alt="{{ $item->item_name }}"
                     class="w-full h-48 object-cover">

                <!-- Card Content -->
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-card-foreground mb-1">{{ $item->item_name }}</h3>
                    <p class="text-sm text-muted-foreground mb-3 truncate">
                        {{ $item->description }}
                    </p>
                    <a href="{{ route('item.detail', $item->id) }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors bg-secondary text-secondary-foreground shadow-sm hover:bg-secondary/80 h-9 px-4 py-2 w-full">
                        View Details
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-muted-foreground">No lost items have been reported yet.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination Links -->
    <div class="mt-8">
        {{-- This will use Laravel's default pagination.
             You can publish and edit the pagination views for full Tailwind styling if needed:
             `php artisan vendor:publish --tag=laravel-pagination`
        --}}
        {{ $lostItems->links() }}
    </div>
@endsection
