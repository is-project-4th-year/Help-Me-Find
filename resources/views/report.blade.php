@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-semibold text-foreground mb-6">Report a Found Item</h2>

    <div class="max-w-2xl mx-auto bg-card p-8 rounded-lg shadow-md border border-border">
        <!--
          IMPORTANT: Added enctype="multipart/form-data"
          This is required for file uploads to work.
        -->
        <form action="{{ route('report.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Item Name -->
            <div class="space-y-2">
                <label for="item_name" class="block text-sm font-medium text-foreground">Item Name</label>
                <input
                    type="text"
                    id="item_name"
                    name="item_name"
                    placeholder="e.g., Black Wallet"
                    class="flex h-9 w-full rounded-md border border-border bg-input px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                    value="{{ old('item_name') }}"
                    required
                >
                @error('item_name')
                    <p class="text-sm text-destructive">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div class="space-y-2">
                <label for="category" class="block text-sm font-medium text-foreground">Category</label>
                <select
                    id="category"
                    name="category"
                    class="flex h-9 w-full rounded-md border border-border bg-input px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                    required
                >
                    <option value="" disabled {{ old('category') ? '' : 'selected' }}>Select a category</option>
                    <option value="Electronics" {{ old('category') == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                    <option value="Clothing" {{ old('category') == 'Clothing' ? 'selected' : '' }}>Clothing</option>
                    <option value="Keys" {{ old('category') == 'Keys' ? 'selected' : '' }}>Keys</option>
                    <option value="Wallet" {{ old('category') == 'Wallet' ? 'selected' : '' }}>Wallet</option>
                    <option value="Bags" {{ old('category') == 'Bags' ? 'selected' : '' }}>Bags</option>
                    <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('category')
                    <p class="text-sm text-destructive">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date Found -->
            <div class="space-y-2">
                <label for="date_found" class="block text-sm font-medium text-foreground">Date Found</label>
                <input
                    type="date"
                    id="date_found"
                    name="date_found"
                    class="flex h-9 w-full rounded-md border border-border bg-input px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                    value="{{ old('date_found') }}"
                    required
                >
                @error('date_found')
                    <p class="text-sm text-destructive">{{ $message }}</p>
                @enderror
            </div>

            <!-- Location Found -->
            <div class="space-y-2">
                <label for="location_found" class="block text-sm font-medium text-foreground">Location Found</label>
                <input
                    type="text"
                    id="location_found"
                    name="location_found"
                    placeholder="e.g., Library 3rd Floor"
                    class="flex h-9 w-full rounded-md border border-border bg-input px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                    value="{{ old('location_found') }}"
                    required
                >
                @error('location_found')
                    <p class="text-sm text-destructive">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="space-y-2">
                <label for="description" class="block text-sm font-medium text-foreground">Description</label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    placeholder="Describe the item in detail..."
                    class="flex min-h-[80px] w-full rounded-md border border-border bg-input px-3 py-2 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-sm text-destructive">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image Upload -->
            <div class="space-y-2">
                <label for="image" class="block text-sm font-medium text-foreground">Upload Image</label>
                <input
                    type="file"
                    id="image"
                    name="image"
                    class="flex h-9 w-full rounded-md border border-border bg-input px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                >
                @error('image')
                    <p class="text-sm text-destructive">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
                    Submit Report
                </button>
            </div>
        </form>
    </div>
@endsection
