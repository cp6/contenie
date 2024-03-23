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
                    <form id="uploadForm" action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                    <div class="mb-6">
                        <label class="block text-gray-700 dark:text-gray-100 text-sm font-bold mb-2" for="file">
                            Choose your file
                        </label>
                        <input class="appearance-none rounded w-full py-2 px-1 text-gray-700 dark:text-gray-100 leading-tight focus:outline-none focus:shadow-outline" id="fileInput" name="file" type="file" required>
                    </div>
                    <div class="flex items-center justify-between">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1.5 px-8 rounded focus:outline-none focus:shadow-outline" type="submit">
                            Upload
                        </button>
                    </div>
                    </form>
                    <div id="progressContainer" class="w-full bg-gray-200 mt-4 rounded hidden">
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
                    const id = response.id
                    message.textContent = 'File uploaded successfully!';
                    setTimeout(() => {
                        window.location.href = `{{ route('process.index') }}/${id}/edit`;
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
