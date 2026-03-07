<x-admin-layout>
    <x-slot name="header">
        Create New User
    </x-slot>

    <div>
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-yemdat-brown flex items-center gap-1">
                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Users
            </a>
        </div>

        <div class="max-w-2xl mx-auto bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
            <div class="p-8 bg-white border-b border-gray-200">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full rounded-lg border-gray-300 focus:border-yemdat-brown focus:ring focus:ring-yemdat-brown/20 shadow-sm" required>
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full rounded-lg border-gray-300 focus:border-yemdat-brown focus:ring focus:ring-yemdat-brown/20 shadow-sm" required>
                            @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Role -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <select name="role" id="role" class="w-full rounded-lg border-gray-300 focus:border-yemdat-brown focus:ring focus:ring-yemdat-brown/20 shadow-sm" required onchange="togglePermissions()">
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Super Admins have full access to everything.</p>
                            @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Permissions -->
                        <div id="permissions-container">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Allowed Menus (Permissions)</label>
                            <div class="space-y-2 border border-gray-200 p-4 rounded-lg bg-gray-50">
                                <label class="flex items-center">
                                    <input type="checkbox" name="permissions[]" value="events" class="rounded border-gray-300 text-yemdat-brown focus:ring-yemdat-brown" {{ is_array(old('permissions')) && in_array('events', old('permissions')) ? 'checked' : '' }}>
                                    <span class="ml-2 text-gray-700">Events Management</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="permissions[]" value="members" class="rounded border-gray-300 text-yemdat-brown focus:ring-yemdat-brown" {{ is_array(old('permissions')) && in_array('members', old('permissions')) ? 'checked' : '' }}>
                                    <span class="ml-2 text-gray-700">Members Database</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="permissions[]" value="messages" class="rounded border-gray-300 text-yemdat-brown focus:ring-yemdat-brown" {{ is_array(old('permissions')) && in_array('messages', old('permissions')) ? 'checked' : '' }}>
                                    <span class="ml-2 text-gray-700">Messages/Inbox</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="permissions[]" value="posts" class="rounded border-gray-300 text-yemdat-brown focus:ring-yemdat-brown" {{ is_array(old('permissions')) && in_array('posts', old('permissions')) ? 'checked' : '' }}>
                                    <span class="ml-2 text-gray-700">News/Posts Management</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="permissions[]" value="trainers" class="rounded border-gray-300 text-yemdat-brown focus:ring-yemdat-brown" {{ is_array(old('permissions')) && in_array('trainers', old('permissions')) ? 'checked' : '' }}>
                                    <span class="ml-2 text-gray-700">Trainer Requests</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="permissions[]" value="settings" class="rounded border-gray-300 text-yemdat-brown focus:ring-yemdat-brown" {{ is_array(old('permissions')) && in_array('settings', old('permissions')) ? 'checked' : '' }}>
                                    <span class="ml-2 text-gray-700">Global Settings</span>
                                </label>
                                <label class="flex items-center mt-2 pt-2 border-t border-gray-100">
                                    <input type="checkbox" name="permissions[]" value="analytics" class="rounded border-gray-300 text-blue-600 focus:ring-blue-600" {{ is_array(old('permissions')) && in_array('analytics', old('permissions')) ? 'checked' : '' }}>
                                    <span class="ml-2 text-gray-700 font-bold">Analytics & Reports</span>
                                </label>
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" name="password" id="password" class="w-full rounded-lg border-gray-300 focus:border-yemdat-brown focus:ring focus:ring-yemdat-brown/20 shadow-sm" required>
                            @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full rounded-lg border-gray-300 focus:border-yemdat-brown focus:ring focus:ring-yemdat-brown/20 shadow-sm" required>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="bg-yemdat-brown hover:bg-yemdat-gold text-white font-bold py-2 px-6 rounded-lg shadow-md transition-colors">
                            Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePermissions() {
            var role = document.getElementById('role').value;
            var container = document.getElementById('permissions-container');
            if (role === 'super_admin') {
                container.style.display = 'none';
            } else {
                container.style.display = 'block';
            }
        }
        // Run on load
        togglePermissions();
    </script>
</x-admin-layout>
