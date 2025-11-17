<!DOCTYPE html>
<html lang="en">
@include('layouts.header')
@vite(['resources/css/item.css'])

<body>
  @include('layouts.bar')

  {{-- NEW PAGE CONTAINER --}}
  <div class="item-detail-container">

    {{-- BACK BUTTON --}}
    <a href="{{ route('lostItems') }}" class="btn btn-ghost">
      <i class="fa fa-arrow-left"></i>
      Back to Lost Items
    </a>

    {{-- ** NEW: Show error if redirected from map ** --}}
    @if(session('error'))
        <div class="card" style="background-color: #ffcccc; color: #a00; border: 1px solid #a00; margin-bottom: 1rem; padding: 1rem;">
            {{ session('error') }}
        </div>
    @endif


    {{-- MAIN 2-COLUMN GRID --}}
    <div class="grid-container">

      {{-- COLUMN 1: IMAGE --}}
      <div class="image-section">
        <div class="card image-card">
          <img
            src="{{ asset('uploads/' . $item['ImageName']) }}"
            alt="{{ $item['ItemType'] ?? 'Found Item' }}"
          >
          {{-- This assumes items in this view are 'Found'.
               You can replace 'Found' with a variable if you have one (e.g., $item['status']) --}}
          <span class="badge">Found</span>
        </div>
      </div>

      {{-- COLUMN 2: DETAILS --}}
      <div class="details-section">
        {{-- Item Type as Title --}}
        <h1 class="item-title">{{ $item['ItemType'] ?? 'Item Details' }}</h1>

        {{-- AI Description --}}
        @if(!empty($item['Description']))
          <p class="text-muted">{{ $item['Description'] }}</p>
        @endif

        <div class="separator"></div>

        {{-- INFO CARD --}}
        <div class="card info-card">
          <div class="card-header">
            <h3 class="card-title">Item Information</h3>
          </div>
          <div class="card-content">
            {{-- Date Info Block --}}
            <div class="info-block">
              <i class="fa fa-calendar fa-fw info-icon"></i>
              <div class="info-text">
                <p class="info-label">Date & Time Found</p>
                <p>{{ $item['DateTime'] ?? 'Unknown' }}</p>
              </div>
            </div>

            {{-- *** MODIFIED LOCATION BLOCK *** --}}
            {{-- Check if text location OR map location exists --}}
            @if(!empty($item['Location']) || (!empty($item['Latitude']) && !empty($item['Longitude'])))
              <div class="separator-small"></div>
              <div class="info-block">
                <i class="fa fa-map-marker fa-fw info-icon"></i>
                <div class="info-text">
                  <p class="info-label">Found Location</p>

                  {{-- Check if latitude and longitude exist (using JSON keys) --}}
                  @if(!empty($item['Latitude']) && !empty($item['Longitude']))
                    {{-- Create a link to the new map route, using the $id (JSON key) --}}
                    <a href="{{ route('item.map', ['id' => $id]) }}" class="btn btn-secondary btn-sm" style="margin-top: 5px;">
                        <i class="fa fa-map-location-dot"></i> View on Map
                    </a>
                  @else
                    {{-- Fallback if no map data but text location exists --}}
                    <p>{{ $item['Location'] }}</p>
                  @endif
                </div>
              </div>
            @endif


            {{-- Finder Info Block (from your logic) --}}
            <div class="separator-small"></div>
            <div class="info-block">
              <i class="fa fa-user fa-fw info-icon"></i>
              <div class="info-text">
                <p class="info-label">Posted By</p>
                <p class="text-muted"><i>Please use the chat to connect with the finder.</i></p>
              </div>
            </div>

          </div>
        </div>

        {{-- CONTACT BUTTON LOGIC (FIXED) --}}
        <div class="contact-buttons">
            {{-- Note: Your JSON doesn't seem to save 'FinderId'. You'll need to add that
                 in HomeController for this chat button to work.
                 Assuming you add 'FinderId' to the JSON save... --}}
          @if(isset($item['FinderId']) && $item['FinderId'] != auth()->id())
            <a href="{{ route('chat.with', ['user' => $item['FinderId']]) }}" class="btn btn-primary btn-full">
                <i class="fa fa-comment"></i> Chat with Finder
            </a>
          @elseif(isset($item['FinderId']) && $item['FinderId'] == auth()->id())
            <button disabled class="btn btn-secondary btn-full">
                <i class="fa fa-user-circle"></i> (You Posted This Item)
            </button>
          @else
            <button disabled class="btn btn-secondary btn-full">
                <i class="fa fa-exclamation-triangle"></i> Cannot Contact Finder
            </button>
          @endif
        </div>
      </div>
    </div> {{-- End Grid --}}

    {{-- "WHAT TO DO NEXT" CARD --}}
    <div class="card steps-card">
      <div class="card-header">
        <h3 class="card-title">What to do next?</h3>
        <p class="text-muted card-description">Steps to safely recover this item</p>
      </div>
      <div class="card-content">
        {{-- Step 1 --}}
        <div class="step-block">
          <div class="step-number">1</div>
          <div class="step-text">
            <h4>Contact the finder</h4>
            <p class="text-muted">Use the messaging feature to discuss details and arrange a meetup.</p>
          </div>
        </div>
        {{-- Step 2 --}}
        <div class="step-block">
          <div class="step-number">2</div>
          <div class="step-text">
            <h4>Verify ownership</h4>
            <p class="text-muted">Be prepared to answer specific questions to confirm the item is yours.</p>
          </div>
        </div>
        {{-- Step 3 --}}
        <div class="step-block">
          <div class="step-number">3</div>
          <div class="step-text">
            <h4>Arrange a safe meetup</h4>
            <p class="text-muted">Meet in a public place during the daytime for a safe exchange.</p>
          </div>
        </div>
      </div>
    </div>

  </div> {{-- End Container --}}

  <footer>
    &copy; {{ now()->year }} Help-Me-Find | Designed with ❤ by Bethelhem
  </footer>

</body>
</html>
