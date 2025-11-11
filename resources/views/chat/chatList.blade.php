<!DOCTYPE html>
<html lang="en">
@include('layouts.header')

<body>
  @include('layouts.bar')

<body>
<div class="container mx-auto p-4">
  <div class="grid grid-cols-4 gap-4">
    <div class="col-span-1 bg-white p-4 rounded shadow">
      {{-- UPDATED --}}
      <h3 class="font-bold mb-2"><i class="fa fa-users"></i> Users</h3>
      <ul>
      @foreach($users as $u)
        <li class="py-2 border-b">
          {{-- UPDATED --}}
          <a href="{{ route('chat.with', $u->id) }}" class="block">
            <i class="fa fa-user-circle-o fa-fw"></i> {{ $u->firstName }} {{ $u->lastName }}<br>
            <small class="text-gray-500" style="margin-left: 26px;">{{ $u->email }}</small>
          </a>
        </li>
      @endforeach
      </ul>
    </div>

    <div class="col-span-3 bg-white p-4 rounded shadow">
      {{-- UPDATED --}}
      <p><i class="fa fa-arrow-left"></i> Select a user to start chat.</p>
    </div>
  </div>
</div>
</body>
</html>
