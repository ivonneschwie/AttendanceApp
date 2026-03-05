<!-- Student Details Modal -->
<div id="student-details-modal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center md:block md:p-0">
        <div id="student-modal-overlay" class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden md:inline-block md:align-middle md:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all md:my-8 md:align-middle md:max-w-2xl md:w-full">
            <div class="bg-white px-4 pt-5 pb-4 md:p-6 md:pb-4">
                <h3 id="student-name" class="text-lg leading-6 font-medium text-gray-900"></h3>
                <div class="mt-4">
                    <p class="text-sm text-gray-500">ID: <span id="student-id"></span></p>
                    <div id="qrcode-container" class="mt-4 flex justify-center"></div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 md:px-6 md:flex md:flex-row-reverse">
                <button type="button" id="close-student-modal-button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 md:w-auto md:text-sm">Close</button>
            </div>
        </div>
    </div>
</div>