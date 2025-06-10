@extends('layouts.myapp')

@section('title', 'Notifications')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-gray-50 min-h-screen">
    <h1 class="text-3xl font-bold text-blue-800 mb-6">🔔 Notifications</h1>

    @if(auth()->user()->notifications->isEmpty())
        <p class="text-gray-600 text-center">You have no notifications.</p>
    @else
        <div class="space-y-6">
            @foreach(auth()->user()->notifications as $notification)
                <div class="p-5 rounded-xl shadow-md {{ $notification->read_at ? 'bg-white' : 'bg-blue-50 border border-blue-300' }}">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                        @if(!$notification->read_at)
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">Unread</span>
                        @else
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full">Read</span>
                        @endif
                    </div>

                    {{-- Notification Content --}}
                    @php $data = $notification->data; @endphp

                    @if($notification->type === 'App\Notifications\NewCarNotification')
                        <h2 class="text-lg font-semibold text-blue-900 mb-1">🚗 {{ $data['type'] ?? 'New Car' }}</h2>
                        <p class="text-gray-700">{{ $data['message'] ?? 'A new car was added!' }}</p>

                        <ul class="mt-3 text-sm text-gray-600 space-y-1">
                            <li><strong>Brand:</strong> {{ $data['brand'] ?? 'N/A' }}</li>
                            <li><strong>Model:</strong> {{ $data['model'] ?? 'N/A' }}</li>
                           
                            <li><strong>Price/Day:</strong> ${{ $data['price_per_day'] ?? 'N/A' }}</li>
                        </ul>
                    @elseif($notification->type === 'App\Notifications\NewDiscountNotification')
                        <h2 class="text-lg font-semibold text-purple-900 mb-1">🎉 {{ $data['type'] ?? 'Discount Alert' }}</h2>
                        <p class="text-gray-700 mb-2">{{ $data['message'] ?? 'A new discount is available!' }}</p>

                        <ul class="text-sm text-gray-700 space-y-1">
                            <li><strong>Code:</strong> {{ $data['code'] ?? 'N/A' }}</li>
                            <li><strong>Amount:</strong> {{ $data['amount'] ?? 'N/A' }}% off</li>
                            <li><strong>Valid Until:</strong> {{ \Carbon\Carbon::parse($data['valid_until'])->format('d M Y') ?? 'N/A' }}</li>
                        </ul>

                    @elseif($notification->type === 'App\Notifications\ReservationMadeNotification')
    <h2 class="text-lg font-semibold text-green-900 mb-1">📅 Reservation Confirmed</h2>
    <p class="text-gray-700 mb-2">{{ $data['message'] ?? 'You have a new reservation.' }}</p>

    <ul class="text-sm text-gray-700 space-y-1">
       
        <li><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($data['start_date'])->format('d M Y') ?? 'N/A' }}</li>
        <li><strong>End Date:</strong> {{ \Carbon\Carbon::parse($data['end_date'])->format('d M Y') ?? 'N/A' }}</li>
        <li><strong>Days:</strong> {{ $data['days'] ?? 'N/A' }}</li>
        <li><strong>Price/Day:</strong> ${{ $data['price_per_day'] ?? 'N/A' }}</li>

        <li><strong>Pickup Location:</strong> {{ $data['pickup_location'] ?? 'N/A' }}</li>
        <li><strong>Dropoff Location:</strong> {{ $data['dropoff_location'] ?? 'N/A' }}</li>
        <li><strong>Status:</strong> 
            <span class="px-2 py-1 rounded text-xs font-semibold
                @if($data['status'] === 'Active') bg-green-100 text-green-800
                @elseif($data['status'] === 'Pending') bg-yellow-100 text-yellow-800
                @elseif($data['status'] === 'Cancelled') bg-red-100 text-red-800
                @elseif($data['status'] === 'Completed') bg-gray-100 text-gray-800
                @endif">
                {{ $data['status'] }}
            </span>
        </li>
    </ul>

                       
                    @endif

                    {{-- Action --}}
                    <div class="mt-4 flex justify-end">
                        @if(!$notification->read_at)
                            <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-sm text-blue-600 hover:underline">✔️ Mark as read</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 hover:underline">🗑️ Delete</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
