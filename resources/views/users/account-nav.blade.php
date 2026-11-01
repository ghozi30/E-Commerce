<div class="bg-gray-50 border rounded-lg overflow-hidden shadow-sm">
    <ul class="flex flex-col divide-y divide-gray-100 text-sm font-medium">
        <li>
            <a href="{{ route('user.index') }}" class="w-full text-left px-6 py-4 transition flex items-center gap-3 {{ request()->routeIs('user.index') ? 'bg-primary text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fa fa-tachometer w-5 text-center"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ Route::has('user.orders') ? route('user.orders') : '#' }}" class="w-full text-left px-6 py-4 transition flex items-center gap-3 {{ request()->routeIs('user.orders*') ? 'bg-primary text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fa fa-shopping-cart w-5 text-center"></i>
                <span>Orders</span>
            </a>
        </li>
        <li>
            <a href="{{ Route::has('user.address') ? route('user.address') : '#' }}" class="w-full text-left px-6 py-4 transition flex items-center gap-3 {{ request()->routeIs('user.address*') ? 'bg-primary text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fa fa-map-marker w-5 text-center"></i>
                <span>Address</span>
            </a>
        </li>
        <li>
            <a href="{{ route('profile.edit') }}" class="w-full text-left px-6 py-4 transition flex items-center gap-3 {{ request()->routeIs('profile.edit') ? 'bg-primary text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fa fa-user w-5 text-center"></i>
                <span>Account Details</span>
            </a>
        </li>
        <li>
            <a href="{{ Route::has('wishlist.index') ? route('wishlist.index') : '#' }}" class="w-full text-left px-6 py-4 transition flex items-center gap-3 {{ request()->routeIs('wishlist.index') ? 'bg-primary text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fa fa-heart w-5 text-center"></i>
                <span>Wishlist</span>
            </a>
        </li>
        <li>
            <form method="POST" action="{{ route('logout') }}" id="user-logout-form">
                @csrf
                <button type="submit" class="w-full text-left px-6 py-4 transition flex items-center gap-3 text-red-600 hover:bg-red-50">
                    <i class="fa fa-sign-out w-5 text-center"></i>
                    <span>Logout</span>
                </button>
            </form>
        </li>
    </ul>
</div>

