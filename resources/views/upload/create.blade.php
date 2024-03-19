<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Upload media') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-8 pt-6 pb-8 mb-4">
                    <form id="uploadForm" action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-100 text-sm font-bold mb-2" for="file">
                                Choose your file
                            </label>
                            <input class="appearance-none rounded w-full py-2 px-3 text-gray-700 dark:text-gray-100 leading-tight focus:outline-none focus:shadow-outline" id="file" name="file" type="file" required>
                        </div>
                        <div class="flex items-center justify-between">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                                Upload
                            </button>
                        </div>
                    </form>
                    <div id="progress" class="mt-1">
                        <div id="progress-bar" class="bg-blue-500 text-xs leading-none py-1 text-center text-white" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('js')
    <script>
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();

            var progressBar = document.getElementById('progress-bar');
            progressBar.style.width = '0%';

            var formData = new FormData(document.getElementById('uploadForm'));
            var xhr = new XMLHttpRequest();

            xhr.upload.onprogress = function(event) {
                var percent = Math.round((event.loaded / event.total) * 100);
                progressBar.style.width = percent + '%';
                progressBar.innerText = percent + '%';
            };

            xhr.onload = function() {
                if (xhr.status === 200) {
                    progressBar.style.width = '100%';
                    progressBar.innerText = '100%';
                    setTimeout(() => {
                        progressBar.style.width = '0%';
                        progressBar.innerText = '';
                        window.location.replace('{{ route("dashboard") }}');
                    }, 1000); // Reset progress bar after 1 second
                }
            };

            xhr.open('POST', '{{ route("upload.store") }}', true);

            xhr.send(formData);
        });
    </script>
    @endsection
</x-app-layout>
