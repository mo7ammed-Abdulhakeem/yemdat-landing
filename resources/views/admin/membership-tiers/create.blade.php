<x-admin-layout>
    <x-slot name="header">
        Create New Plan
    </x-slot>

    <div>
        <div class="mb-6 flex justify-between items-center">
            <a href="{{ route('admin.membership-tiers.index') }}" class="text-gray-600 hover:text-yemdat-brown flex items-center gap-1">
                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Plans
            </a>
        </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="p-8">
                    <form method="POST" action="{{ route('admin.membership-tiers.store') }}" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name EN -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Display Name (English)</label>
                                <input type="text" name="name_en" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold">
                            </div>

                            <!-- Name AR -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Display Name (Arabic)</label>
                                <input type="text" name="name_ar" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold text-right">
                            </div>
                        </div>

                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Desc EN -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description (English)</label>
                                <textarea name="description_en" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold"></textarea>
                            </div>

                            <!-- Desc AR -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description (Arabic)</label>
                                <textarea name="description_ar" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold text-right"></textarea>
                            </div>
                        </div>

                        <!-- Sort Order -->
                        <div>
                             <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                             <input type="number" name="sort_order" value="0" class="mt-1 block w-24 rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold">
                        </div>

                        <!-- Features (Dynamic List) -->
                        <div x-data="{ 
                            featuresEn: [''], 
                            featuresAr: [''],
                            addFeature() { this.featuresEn.push(''); this.featuresAr.push(''); },
                            removeFeature(index) { this.featuresEn.splice(index, 1); this.featuresAr.splice(index, 1); }
                        }" class="space-y-4 border-t pt-4">
                            <h3 class="text-lg font-medium text-gray-900">Features List</h3>
                            <p class="text-sm text-gray-500">Add features that appear on the card.</p>
                            
                            <template x-for="(feature, index) in featuresEn" :key="index">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center bg-gray-50 p-3 rounded-lg">
                                    <input type="text" :name="'features_en[' + index + ']'" x-model="featuresEn[index]" placeholder="Feature in English" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold text-sm">
                                    <div class="flex gap-2">
                                        <input type="text" :name="'features_ar[' + index + ']'" x-model="featuresAr[index]" placeholder="الميزة بالعربي" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold text-sm text-right">
                                        <button type="button" @click="removeFeature(index)" class="text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <button type="button" @click="addFeature()" class="text-sm text-yemdat-brown hover:text-yemdat-gold font-medium flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Add Feature
                            </button>
                        </div>


                        <div class="flex justify-end pt-6 border-t border-gray-100">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.membership-tiers.index') }}" class="px-6 py-2 bg-white border border-gray-300 rounded shadow text-gray-700 hover:bg-gray-50 transition font-bold">Cancel</a>
                                <button type="submit" class="bg-yemdat-brown hover:bg-yemdat-gold text-white font-bold py-2 px-6 rounded shadow transition">
                                    Create Plan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    </div>
</x-admin-layout>
