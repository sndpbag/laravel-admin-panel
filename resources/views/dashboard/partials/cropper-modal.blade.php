<div id="cropperModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-[100] p-4">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-xl max-w-lg w-full">
        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Crop & Resize Image</h3>
        <div class="max-h-[60vh] overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-700">
            <img id="imageToCrop" src="" alt="Image to crop" class="max-w-full">
        </div>
        <div class="flex justify-end gap-4 mt-6">
            <button id="cancelCrop" class="px-5 py-2.5 rounded-xl border-2 border-gray-300 dark:border-gray-600 font-semibold hover:bg-gray-100 dark:hover:bg-gray-700 transition text-gray-800 dark:text-gray-200">Cancel</button>
            <button id="cropImageBtn" class="px-5 py-2.5 rounded-xl text-white font-semibold hover:shadow-lg transition" style="background: var(--primary);">Crop & Save</button>
        </div>
    </div>
</div>