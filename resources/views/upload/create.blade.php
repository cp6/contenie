<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Upload media') }}
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-8 pt-6 pb-8 mb-6">
                    <div class="flex items-center justify-center w-full">
                        <label for="fileInput"
                               class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">

                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span>
                                    or drag and drop</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Video, image, or audio file (Max 6GB)</p>
                            </div>

                            <form id="uploadForm" action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-6">
                                    <input id="fileInput" name="file" type="file" class="hidden" required/>
                                </div>
                                <div class="flex items-center justify-between">
                                    <button
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1.5 px-8 rounded focus:outline-none focus:shadow-outline"
                                        type="submit">
                                        Upload
                                    </button>
                                </div>
                            </form>

                        </label>

                    </div>
                    <div id="progressContainer" class="w-full bg-gray-200 mt-2 rounded hidden">
                        <div id="progressBar" class="bg-blue-500 h-2 rounded"></div>
                    </div>
                    <div id="message" class="mt-2 text-gray-700 dark:text-gray-100"></div>
                </div>
            </div>
        </div>
    </div>
    @section('js')
        <script>
            const form = document.getElementById('uploadForm');
            const fileInput = document.getElementById('fileInput');
            const progressBar = document.getElementById('progressBar');
            const progressContainer = document.getElementById('progressContainer');
            const message = document.getElementById('message');

            form.addEventListener('submit', (e) => {
                e.preventDefault();
                progressContainer.classList.remove('hidden'); // Remove the 'hidden' class

                const formData = new FormData(form);

                const xhr = new XMLHttpRequest();
                xhr.open('POST', form.action);

                xhr.upload.onprogress = (e) => {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        progressBar.style.width = `${percent}%`;
                    }
                };

                xhr.onload = () => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        const response = JSON.parse(xhr.responseText);
                        const sid = response.sid
                        message.textContent = 'File uploaded successfully!';
                        setTimeout(() => {
                            window.location.href = `{{ route('upload') }}/${sid}/trim`;
                        }, 2000); // Redirect after 2 seconds
                    } else {
                        message.textContent = 'An error occurred while uploading the file.';
                    }
                };

                xhr.onerror = () => {
                    message.textContent = 'An error occurred while uploading the file.';
                };

                xhr.send(formData);
            });

            fileInput.addEventListener('change', () => {
                progressContainer.classList.remove('hidden'); // Remove the 'hidden' class
            });
        </script>
    @endsection
</x-app-layout>
