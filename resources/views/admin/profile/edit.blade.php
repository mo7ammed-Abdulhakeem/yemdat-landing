<x-admin-layout>
    <x-slot name="header">
        My Profile
    </x-slot>

    <div>
        <div class="mb-6">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-yemdat-brown flex items-center gap-1">
                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Dashboard
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="max-w-2xl mx-auto bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
            <div class="p-8 bg-white border-b border-gray-200">
                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 gap-6">
                         <!-- Details -->
                         <div class="bg-gray-50 p-4 rounded-lg">
                             <h3 class="text-lg font-bold text-gray-800 mb-2">Account Details</h3>
                             <p class="text-sm text-gray-500 mb-4">Update your basic account information.</p>
                             
                             <!-- Name -->
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full rounded-lg border-gray-300 focus:border-yemdat-brown focus:ring focus:ring-yemdat-brown/20 shadow-sm" required>
                                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="w-full rounded-lg border-gray-300 focus:border-yemdat-brown focus:ring focus:ring-yemdat-brown/20 shadow-sm" required>
                                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                         </div>
                        
                         <!-- Security -->
                         <div class="bg-gray-50 p-4 rounded-lg">
                             <h3 class="text-lg font-bold text-gray-800 mb-2">Security</h3>
                             <p class="text-sm text-gray-500 mb-4">Ensure your account is using a long, random password to stay secure.</p>

                            <!-- Password -->
                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password (Optional)</label>
                                <input type="password" name="password" id="password" class="w-full rounded-lg border-gray-300 focus:border-yemdat-brown focus:ring focus:ring-yemdat-brown/20 shadow-sm">
                                <p class="text-xs text-gray-500 mt-1">Leave blank to keep current password.</p>
                                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full rounded-lg border-gray-300 focus:border-yemdat-brown focus:ring focus:ring-yemdat-brown/20 shadow-sm">
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="bg-yemdat-brown hover:bg-yemdat-gold text-white font-bold py-2 px-6 rounded-lg shadow-md transition-colors">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
