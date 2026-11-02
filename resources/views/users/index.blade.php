@extends('layouts.app')

@section('content')
<!-- Page Banner Start -->
<div class="relative bg-sky-700 text-white h-56 flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('assets/images/page-banner.jpg') }}');">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative z-10 text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">My Account</h1>
        <ul class="flex justify-center items-center space-x-2 text-sm text-gray-200">
            <li><a href="{{ route('home.index') }}" class="hover:text-white transition">Home</a></li>
            <li>/</li>
            <li class="text-sky-300 font-medium">My Account</li>
        </ul>
    </div>
</div>
<!-- Page Banner End -->

<section class="py-12 md:py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Left Sidebar Navigation -->
            <div class="w-full lg:w-1/4">
                @include('users.account-nav')
            </div>

            <!-- Right Main Content -->
            <div class="w-full lg:w-3/4">
                <div class="bg-white border rounded-lg p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b">Dashboard</h2>
                    
                    <div class="bg-blue-50 border-l-4 border-primary p-4 mb-6 rounded-r">
                        <p class="text-sm text-gray-700">
                            Hello, <strong class="text-gray-900">{{ Auth::user()->name }}</strong> 
                            (Bukan <strong>{{ Auth::user()->name }}</strong>? 
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('user-logout-form').submit();" class="text-primary hover:underline font-semibold">
                                Logout
                            </a>)
                        </p>
                    </div>

                    <p class="text-gray-600 leading-relaxed text-sm mb-6">
                        Dari halaman ringkasan akun Anda, Anda dapat dengan mudah melihat pesanan terbaru, mengelola alamat pengiriman dan penagihan, serta memperbarui password dan detail profil Anda.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 border-t border-gray-100 mb-8">
                        <a href="{{ route('user.orders') }}" class="p-4 border rounded-lg hover:border-primary transition group text-center">
                            <i class="fa fa-shopping-cart text-2xl text-primary group-hover:scale-110 transition-transform mb-2 inline-block"></i>
                            <h4 class="font-bold text-gray-800 text-sm">Pesanan Saya</h4>
                            <p class="text-xs text-gray-500 mt-1">Cek status & riwayat pesanan</p>
                        </a>
                        <a href="{{ Route::has('user.address') ? route('user.address') : '#' }}" class="p-4 border rounded-lg hover:border-primary transition group text-center">
                            <i class="fa fa-map-marker text-2xl text-primary group-hover:scale-110 transition-transform mb-2 inline-block"></i>
                            <h4 class="font-bold text-gray-800 text-sm">Alamat</h4>
                            <p class="text-xs text-gray-500 mt-1">Kelola alamat pengiriman</p>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="p-4 border rounded-lg hover:border-primary transition group text-center">
                            <i class="fa fa-user text-2xl text-primary group-hover:scale-110 transition-transform mb-2 inline-block"></i>
                            <h4 class="font-bold text-gray-800 text-sm">Detail Akun</h4>
                            <p class="text-xs text-gray-500 mt-1">Ubah nama & password</p>
                        </a>
                    </div>

                    <!-- Recent Orders -->
                    @if(isset($orders) && $orders->count() > 0)
                        <div class="border-t pt-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-bold text-gray-800 text-base">Pesanan Terbaru</h3>
                                <a href="{{ route('user.orders') }}" class="text-xs text-primary hover:underline font-semibold">
                                    Lihat Semua &rarr;
                                </a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left whitespace-nowrap text-sm">
                                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                                        <tr>
                                            <th class="px-4 py-2.5">No</th>
                                            <th class="px-4 py-2.5">Tanggal</th>
                                            <th class="px-4 py-2.5">Total</th>
                                            <th class="px-4 py-2.5">Status</th>
                                            <th class="px-4 py-2.5 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($orders as $ord)
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="px-4 py-2.5 font-bold text-gray-700">#ORD-{{ str_pad($ord->id, 5, '0', STR_PAD_LEFT) }}</td>
                                                <td class="px-4 py-2.5 text-gray-600">{{ $ord->created_at->format('M d, Y') }}</td>
                                                <td class="px-4 py-2.5 font-bold text-primary">${{ number_format($ord->total, 2) }}</td>
                                                <td class="px-4 py-2.5">
                                                    @if ($ord->status == 'delivered')
                                                        <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-semibold">Delivered</span>
                                                    @elseif ($ord->status == 'canceled')
                                                        <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-xs font-semibold">Canceled</span>
                                                    @else
                                                        <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs font-semibold">Ordered</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2.5 text-center">
                                                    <a href="{{ route('user.order.details', ['order_id' => $ord->id]) }}" class="text-primary hover:underline font-medium text-xs">
                                                        Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
