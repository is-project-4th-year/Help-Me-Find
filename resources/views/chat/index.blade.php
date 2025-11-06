<!DOCTYPE html>
<html lang="en">
@include('layouts.header')

<body>
  @include('layouts.bar')

<body>
<div class="container mx-auto p-4">
  <div class="grid grid-cols-4 gap-4">
    <div class="col-span-1 bg-white p-4 rounded shadow">
      <h3 class="font-bold mb-2">Users</h3>
      <ul>
      @foreach($users as $u)
        <li class="py-2 border-b">
          <a href="{{ route('chat.with', $u->id) }}" class="block">
            {{ $u->firstName }} {{ $u->lastName }}<br>
            <small class="text-gray-500">{{ $u->email }}</small>
          </a>
        </li>
      @endforeach
      </ul>
    </div>

    <div class="col-span-3 bg-white p-4 rounded shadow">
      <p>Select a user to start chat.</p>
    </div>
  </div>
</div>
</body>
</html>
