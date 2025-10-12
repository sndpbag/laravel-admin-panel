<div id="importModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 transform transition-all">
        <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-8">
                <h3 class="text-2xl font-bold text-gray-800">Import Users</h3>
                <p class="mt-2 text-gray-600">Upload an Excel (.xlsx) or CSV file to bulk create users.</p>

               
                  {{-- /// START: ADD THIS ERROR SECTION /// --}}
                @if ($errors->import->any())
                    <div class="mt-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl">
                        <p class="font-bold mb-2">Please fix the following errors in your file:</p>
                        <ul class="list-disc pl-5">
                            @foreach ($errors->import->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{-- /// END: ADD THIS ERROR SECTION /// --}}
                
               
               
                <div class="mt-6">
                    <label for="file" class="block text-sm font-semibold text-gray-700">Upload File</label>
                    <input type="file" name="file" id="file" class="mt-1 block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-indigo-50 file:text-indigo-700
                        hover:file:bg-indigo-100" required>
                </div>

                <div class="mt-4 text-sm text-gray-500">
                    <p>Make sure your file has the following columns: <strong>name, email, password</strong>. Optional columns are: <strong>phone, role, status</strong>.</p>
                    <a href="{{ route('users.template') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">Download Template File</a>
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-b-2xl px-6 py-4 flex justify-end gap-3">
                <button type="button" id="cancelImportBtn" class="px-6 py-3 rounded-xl text-gray-700 bg-gray-200 hover:bg-gray-300 font-semibold transition">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3 rounded-xl text-white font-semibold hover:shadow-lg transition" style="background: var(--primary);">
                    Import Users
                </button>
            </div>
        </form>
    </div>
</div>