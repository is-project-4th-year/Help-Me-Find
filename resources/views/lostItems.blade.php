<!DOCTYPE html>
<html lang="en">
@include('layouts.header')

<body>
  @include('layouts.bar')

  <div class="container space-y-6">

    {{-- Page Header --}}
    <div>
      {{-- UPDATED --}}
      <h1><i class="fa fa-search"></i> Lost & Found Items</h1>
      <p class="text-muted-foreground">
        Browse through items reported by others. Click on any item for more details.
      </p>
    </div>

    {{-- Search Card --}}
    <div class="card card-search-content">
      <form action="{{ route('ragSearch') }}" method="GET" class="search-form-flex">
        <div class="search-wrapper">
            <i class="fa fa-search search-icon"></i>
            <input
                type="text"
                name="query"
                placeholder="Search lost items (e.g. 'silver keys', 'white Samsung phone')"
                value="{{ request('query') }}"
                class="search-input"
            >
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-search icon"></i> Search
        </button>
      </form>
    </div>

    {{-- Items Grid --}}
    @if(isset($items) && count($items) > 0)
      <div class="items-grid">
        {{-- UPDATED: Conditional Sorting --}}
        {{-- If a search query exists (RAG Search), use items as-is. Otherwise, sort by Date. --}}
        @php
            $gridItems = request('query')
                ? $items
                : collect($items)->sortByDesc(fn($item) => \Carbon\Carbon::parse(data_get($item, 'DateTime')));
        @endphp

        @foreach($gridItems as $id => $item)
          <a href="{{ route('itemDetail', ['id' => $id]) }}" class="card item-card">
            <div>
              {{-- Image Section --}}
              <div class="item-image-wrapper">
                <img src="{{ asset('uploads/' . data_get($item, 'ImageName')) }}"
                     alt="Item image"
                     class="item-image"
                     {{-- Fallback image --}}
                     onerror="this.src='https://source.unsplash.com/400x300/?object,lost&grayscale'; this.onerror=null;">
              </div>

              {{-- Card Header: Title & Description --}}
              <div class="card-header">
                @if(!empty(data_get($item, 'Description')))
                  {{-- Create a "title" from the description --}}
                  <p class="item-card-description">
                      {{ Str::limit(data_get($item, 'Description'), 160) }}
                  </p>
                @else
                  <h3 class="item-card-title">Item Reported</h3>
                  <p class="item-card-description" style="font-style: italic;">
                      Click for full details
                  </p>
                @endif
              </div>
            </div>

            {{-- Card Content: Date & Location --}}
            <div class="card-content" style="padding-top: 1rem;">
              {{-- Date --}}
              <div class="item-info-line">
                  <i class="fa fa-calendar icon"></i>
                  <span><strong>Found:</strong> {{ \Carbon\Carbon::parse(data_get($item, 'DateTime'))->format('F j, Y') }}</span>
              </div>

              {{-- Location (From previous request) --}}
              @if(!empty(data_get($item, 'Location')))
                  <div class="item-info-line" style="margin-top: 0.5rem;">
                      <i class="fa fa-map-marker icon"></i>
                      <span><strong>Location:</strong> {{ Str::limit(data_get($item, 'Location'), 40) }}</span>
                  </div>
              @endif
            </div>
          </a>
        @endforeach
      </div>
    @else
      {{-- No Items Found Card --}}
      <div class="card no-items-card">
          <i class="fa fa-search icon"></i>
          <h3>No items found</h3>
          <p class="text-muted-foreground" style="font-size: 1rem; max-width: 400px; margin: 0.5rem auto 0;">
              No items match your search at this time. Try different keywords or check back later.
          </p>
      </div>
    @endif

    {{-- Home Button --}}

  </div>
  @include('layouts.footer')

</body>
</html>
