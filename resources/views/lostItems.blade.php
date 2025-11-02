<!DOCTYPE html>
<html lang="en">
@include('layouts.header')

<body>
  @include('layouts.bar')

  <div class="container">
    <h1>List of Reported Items</h1>
    <p style="font-size: 16px; color: #5d4037; max-width: 600px; margin: 0 auto 25px;">
      Browse through items reported by others. Click on any image for more details about the item.
    </p>

    <form action="{{ route('ragSearch') }}" method="GET" class="search-form" style="margin-bottom: 30px; text-align: center; display: flex; justify-content: center; gap: 10px;">
    <input
        type="text"
        name="query"
        placeholder="Search lost items (e.g. 'silver keys', 'white Samsung phone')"
        value="{{ request('query') }}"
        style="width: 70%; max-width: 500px; padding: 12px 15px; border-radius: 8px; border: 1px solid #d7ccc8;"
    >
    <button type="submit" class="btn" style="margin: 0; min-width: 100px;">
        <i class="fa fa-search"></i> Search
    </button>
    </form>

    {{-- Check if items exist --}}
    @if(isset($items) && count($items) > 0)
      <div class="grid">
        @foreach($items as $id => $item)
          <a href="{{ route('itemDetail', ['id' => $id]) }}" class="grid-item">
            <img src="{{ asset('uploads/' . data_get($item, 'ImageName')) }}" alt="Found item image">
            <div class="item-details" style="padding: 10px;">
              <p style="font-size: 14px; color: #4e342e; margin-bottom: 8px;">
                <strong>Found:</strong> {{ data_get($item, 'DateTime') }}
              </p>

              @if(!empty(data_get($item, 'Description')))
                <p class="item-description" style="color: #5d4037; font-size: 14px;">
                  {{ Str::limit(data_get($item, 'Description'), 60) }}
                </p>
              @else
                <p class="item-description" style="color: #8d6e63; font-style: italic;">Click for full details</p>
              @endif
            </div>
          </a>
        @endforeach
      </div>
    @else
      <p>No items found yet.</p>
    @endif

    <div style="margin-top: 40px;">
      <a href="{{ route('home') }}" class="btn">🏠 Home</a>
    </div>
  </div>

  <footer>
    &copy; {{ date('Y') }} Help-Me-Find | Designed with ❤ by Bethelhem
  </footer>

</body>
</html>
