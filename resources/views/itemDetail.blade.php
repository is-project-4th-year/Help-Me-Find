<!DOCTYPE html>
<html lang="en">
@include('layouts.header')

<body>
  @include('layouts.bar')

  <div class="container">
    {{-- UPDATED --}}
    <h2><i class="fa fa-info-circle"></i> Item Details</h2>

    <div class="uploaded-image" style="margin-top: 20px;">
      <img
        src="{{ asset('uploads/' . $item['ImageName']) }}"
        alt="Found Item Image"
        style="max-width: 400px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);"
      >
    </div>

    <div class="result" style="margin-top: 25px;">
      {{-- UPDATED --}}
      <p><i class="fa fa-tag fa-fw"></i> <strong>Item Type:</strong> {{ $item['ItemType'] ?? 'Unspecified' }}</p>
      {{-- UPDATED --}}
      <p><i class="fa fa-calendar fa-fw"></i> <strong>Date & Time Found:</strong> {{ $item['DateTime'] ?? 'Unknown' }}</p>

      @if(!empty($item['Location']))
        {{-- UPDATED --}}
        <p><i class="fa fa-map-marker fa-fw"></i> <strong>Found Location:</strong> {{ $item['Location'] }}</p>
      @endif
    </div>

    @if(!empty($item['Description']))
    <div class="result" style="margin-top: 25px;">
      {{-- UPDATED --}}
      <h3><i class="fa fa-commenting-o"></i> AI-Generated Description</h3>
      <p style="font-size: 16px; line-height: 1.5; color: #4e342e;">
        {{ $item['Description'] }}
      </p>
    </div>
    @endif

    <div style="margin-top: 30px;">
        {{-- Check if item has a FinderId associated before showing chat option --}}
        @if(isset($item['FinderId']) && $item['FinderId'] != auth()->id())
            {{-- <a href="{{ route('chat.show', ['item_id' => $id]) }}" class="btn" style="margin-right: 15px;">
                <i class="fa fa-comment"></i> Chat with Finder
            </a> --}}
        @elseif(isset($item['FinderId']) && $item['FinderId'] == auth()->id())
            {{-- UPDATED --}}
            <button disabled class="btn btn-secondary" style="margin-right: 15px; cursor: not-allowed;">
                <i class="fa fa-user-circle"></i> (You Posted This Item)
            </button>
        @else
            {{-- UPDATED --}}
            <button disabled class="btn btn-secondary" style="margin-right: 15px; cursor: not-allowed;">
                <i class="fa fa-exclamation-triangle"></i> Cannot Contact Finder
            </button>
        @endif

        {{-- UPDATED --}}
        <a href="{{ route('lostItems') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back to Lost Items</a>
        <a href="{{ route('home') }}" class="btn"><i class="fa fa-home"></i> Home</a>
    </div>
  </div>

  <footer>
    &copy; {{ now()->year }} Help-Me-Find | Designed with ❤ by Bethelhem
  </footer>

</body>
</html>
