{{-- The Modal --}}
<div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 transform transition-all">
        <div class="p-8">
            <div class="text-center">
                {{-- Icon --}}
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100">
                    <svg class="h-8 w-8 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                {{-- Content --}}
                <h3 class="mt-5 text-2xl font-bold text-gray-900" id="modal-title">Confirm Deletion</h3>
                <div class="mt-2">
                    <p class="text-gray-600">Are you sure you want to delete this user? This action cannot be undone.</p>
                </div>
            </div>
        </div>
        {{-- Action Buttons --}}
        <div class="bg-gray-50 rounded-b-2xl px-6 py-4 flex justify-end gap-3">
            <button type="button" id="cancelModalBtn" class="px-6 py-3 rounded-xl text-gray-700 bg-gray-200 hover:bg-gray-300 font-semibold transition">
                Cancel
            </button>
            {{-- This button will trigger the form submission via JavaScript --}}
            <button type="button" id="confirmDeleteBtn" class="px-6 py-3 rounded-xl text-white font-semibold hover:shadow-lg transition bg-red-600 hover:bg-red-700">
                Yes, Delete
            </button>
        </div>
    </div>
</div>