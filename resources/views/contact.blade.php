@extends('layouts.app')

@section('content')
<!-- Page Banner Start -->
<div class="relative bg-sky-700 text-white h-56 flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('assets/images/page-banner.jpg') }}');">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative z-10 text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">Contact Us</h1>
        <ul class="flex justify-center items-center space-x-2 text-sm text-gray-200">
            <li><a href="{{ route('home.index') }}" class="hover:text-white transition">Home</a></li>
            <li>/</li>
            <li class="text-sky-300 font-medium">Contact Us</li>
        </ul>
    </div>
</div>
<!-- Page Banner End -->

<section class="py-12 md:py-16 bg-white">
    <div class="container mx-auto px-4">
        
        @if (Session::has('status'))
            <div class="mb-8 max-w-5xl mx-auto p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-green-500 text-base"></i>
                    <span>{{ Session::get('status') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700 font-bold">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-8 max-w-5xl mx-auto p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8 items-stretch max-w-5xl mx-auto">
            
            <!-- Contact Info -->
            <div class="w-full lg:w-1/3 flex flex-col">
                <div class="bg-gray-50 p-8 rounded-2xl border border-gray-200 shadow-sm flex-grow">
                    <h2 class="text-2xl font-bold mb-3 text-gray-800">Contact Info</h2>
                    <p class="text-gray-500 text-sm mb-6 leading-relaxed">Have a question, feedback, or need assistance? Reach out to our dedicated support team.</p>

                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-11 h-11 flex-shrink-0 flex items-center justify-center bg-white rounded-full shadow-sm text-primary text-lg border border-gray-100">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">Phone</h4>
                                <p class="text-sm text-gray-500 mt-0.5">
                                    <a href="tel:+6281234567890" class="hover:text-primary transition">+62 812 3456 7890</a>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="w-11 h-11 flex-shrink-0 flex items-center justify-center bg-white rounded-full shadow-sm text-primary text-lg border border-gray-100">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">Email</h4>
                                <p class="text-sm text-gray-500 mt-0.5">
                                    <a href="mailto:contact@ecommerce.test" class="hover:text-primary transition">contact@ecommerce.test</a>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="w-11 h-11 flex-shrink-0 flex items-center justify-center bg-white rounded-full shadow-sm text-primary text-lg border border-gray-100">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">Address</h4>
                                <p class="text-sm text-gray-500 mt-0.5">Sudirman Central Business District, Jakarta, Indonesia</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex space-x-3 mt-8 pt-6 border-t border-gray-200">
                        <a href="#" class="w-9 h-9 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-primary hover:text-white hover:border-primary transition text-gray-600"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#" class="w-9 h-9 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-primary hover:text-white hover:border-primary transition text-gray-600"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="w-9 h-9 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-primary hover:text-white hover:border-primary transition text-gray-600"><i class="fa-brands fa-instagram"></i></a>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="w-full lg:w-2/3">
                <div class="bg-gray-50 p-8 rounded-2xl border border-gray-200 shadow-sm h-full">
                    <h2 class="text-2xl font-bold mb-2 text-gray-800">Get In Touch</h2>
                    <p class="text-sm text-gray-500 mb-6">Fill in the form below and we will get back to you within 24 hours.</p>

                    <form action="{{ route('home.contact.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Name *</label>
                                <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" placeholder="Your Full Name" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary bg-white text-sm" required />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Email *</label>
                                <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" placeholder="Your Email Address" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary bg-white text-sm" required />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone', auth()->user()->mobile ?? '') }}" placeholder="Phone (optional)" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary bg-white text-sm" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Subject</label>
                                <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Subject (optional)" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary bg-white text-sm" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Message *</label>
                            <textarea name="comment" placeholder="Write your message here..." class="w-full border border-gray-300 p-3 rounded-lg h-36 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary bg-white text-sm" required>{{ old('comment') }}</textarea>
                        </div>
                        
                        <button type="submit" class="bg-primary text-white px-8 py-3 rounded-lg hover:bg-blue-600 transition shadow-md font-semibold text-sm">
                            <i class="fa-solid fa-paper-plane mr-2"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
