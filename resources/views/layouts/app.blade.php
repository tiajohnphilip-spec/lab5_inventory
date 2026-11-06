
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Inventory Management System') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>

<body>

<nav class="navbar navbar-dark">
    <div class="container">

        <a class="navbar-brand" href="{{ route('dashboard') }}">
            Inventory Management System
        </a>

        @auth
            <div class="d-flex align-items-center flex-wrap gap-2">

                <a href="{{ route('dashboard') }}"
                   class="btn btn-outline-light btn-sm">
                    Dashboard
                </a>

                <a href="{{ route('products.index') }}"
                   class="btn btn-outline-light btn-sm">
                    Products
                </a>

                <a href="{{ route('transactions.index') }}"
                   class="btn btn-outline-light btn-sm">
                    Transactions
                </a>

                <!-- Notification Bell -->
                <div class="dropdown">
                    <button class="btn btn-outline-light btn-sm position-relative"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                        🔔 Notifications

                        @if(auth()->user()->unreadNotifications()->count() > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ auth()->user()->unreadNotifications()->count() }}
                            </span>
                        @endif
                    </button>

                    <div class="dropdown-menu dropdown-menu-end p-2"
                         style="width: 320px; max-height: 400px; overflow-y: auto;">

                        <h6 class="dropdown-header">
                            Recent Notifications
                        </h6>

                        @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)

                            <div class="border-bottom p-2">

                                <div class="small">
                                    <strong>
                                        {{ $notification->data['product_name'] ?? 'Product' }}
                                    </strong>

                                    <p class="mb-1">
                                        {{ $notification->data['message'] ?? 'Low stock alert.' }}
                                    </p>

                                    <span class="text-muted">
                                        Quantity:
                                        {{ $notification->data['quantity'] ?? 0 }}
                                    </span>

                                    @if(!$notification->read_at)
                                        <span class="badge bg-warning text-dark">
                                            Unread
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            Read
                                        </span>
                                    @endif
                                </div>

                                <div class="d-flex gap-2 mt-2">

                                    @if(isset($notification->data['product_id']))
                                        <a href="{{ route('products.show', $notification->data['product_id']) }}"
                                           class="btn btn-primary btn-sm">
                                            View Product
                                        </a>
                                    @endif

                                    @if(!$notification->read_at)
                                        <form method="POST"
                                              action="{{ route('notifications.read', $notification->id) }}">
                                            @csrf

                                            <button type="submit"
                                                    class="btn btn-outline-success btn-sm">
                                                Mark as Read
                                            </button>
                                        </form>
                                    @endif

                                </div>

                            </div>

                        @empty
                            <p class="text-muted text-center small p-3 mb-0">
                                No notifications yet.
                            </p>
                        @endforelse

                    </div>
                </div>

                <span class="text-white">
                    {{ Auth::user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="btn btn-danger btn-sm">
                        Logout
                    </button>
                </form>

            </div>
        @endauth

    </div>
</nav>

<main class="container py-4">

    @if(isset($slot))
        {{ $slot }}
    @else
        @yield('content')
    @endif

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
