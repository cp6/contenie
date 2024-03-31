<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Trim '.$media->upload->original_name) }}
        </h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid sm:grid-cols-12 grid-cols-1 gap-2 mt-2">
            <div
                class="col-span-12 p-2 bg-white border border-gray-200 rounded-lg shadow sm:p-4 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white">Trim</h5>
                    <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Next</button>
                </div>
                <div class="max-w-full mx-auto grid sm:grid-cols-12 grid-cols-1 gap-2">
                    <div class="col-span-6">
                        <img id="thumbnail-start" class="rounded object-scale-down"
                             src="{{ asset("storage/{$media->directory->name}/{$media->sid}.jpg") }}" width="100%"
                             alt="start position">
                    </div>
                    <div class="col-span-6">
                        <img id="thumbnail-finish" class="rounded object-scale-down"
                             src="{{ asset("storage/{$media->directory->name}/{$media->sid}.jpg") }}" width="100%"
                             alt="finish position">
                    </div>
                </div>
                <div id="slider" class="mt-4"></div>

                <div class="max-w-full mx-auto grid sm:grid-cols-12 grid-cols-1 gap-2 text-center mt-2">
                    <div class="col-span-6">
                        <p class="text-gray-700 dark:text-gray-200">Start: <code><span
                                    class="start">00:00:01</span></code></p>
                    </div>
                    <div class="col-span-6">
                        <p class="text-gray-700 dark:text-gray-200">Finish: <code><span
                                    class="finish">{{date('H:i:s', \round($media->duration))}}</span></code></p>
                    </div>
                </div>
                <div class="max-w-full mx-auto grid sm:grid-cols-12 grid-cols-1 gap-2 text-center mt-2">
                    <div class="col-span-12">
                        <code class="text-pink-500">ffmpeg -ss <span class="start">00:00:00</span> -i IN.mp4 -t <span
                                class="finish">00:00:00</span>
                            -c copy OUT.mp4</code>
                    </div>
                </div>
                <div class="max-w-full mx-auto grid sm:grid-cols-12 grid-cols-1 gap-2 text-center mt-2">
                    <div class="sm:col-start-5 sm:col-end-9 col-span-4 mx-auto"> <!-- Centering the col-span-4 -->
                        <form method="POST" action="{{route('process.trim', request('process'))}}">
                            @csrf
                            <input hidden="" type="text" name="start_value" id="start_value" value="00:00:00">
                            <input hidden="" type="text" name="finish_value" id="finish_value"
                                   value="{{date('H:i:s', \round($media->duration))}}">
                            <button type="submit"
                                    class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Trim
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"
                integrity="sha512-UOJe4paV6hYWBnS0c9GnIRH8PLm2nFK22uhfAvsTIqd3uwnWsVri1OPn5fJYdLtGY3wB11LGHJ4yPU1WFJeBYQ=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
                integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        @section('js')
            <script>
                let slider = document.getElementById("slider");

                noUiSlider.create(slider, {
                    start: [0, {{(int)$media->duration}}],
                    connect: true,
                    step: 1,
                    range: {
                        min: 0,
                        max: {{(int)$media->duration}}
                    }
                });

                let start_element = document.getElementsByClassName("start");
                let finish_element = document.getElementsByClassName("finish");

                slider.noUiSlider.on("change", function (values, handle) {
                    let old_start = start_element[0].innerHTML;
                    let old_finish = finish_element[0].innerHTML;
                    let slider_values = slider.noUiSlider.get();
                    let start_string = new Date(slider_values[0] * 1000)
                        .toISOString()
                        .substr(11, 8);
                    let finish_string = new Date(slider_values[1] * 1000)
                        .toISOString()
                        .substr(11, 8);
                    let duration = new Date((slider_values[1] - slider_values[0]) * 1000)
                        .toISOString()
                        .substr(11, 8);
                    start_element[0].innerHTML = start_string;
                    start_element[1].innerHTML = start_string;
                    document.getElementById('start_value').value = start_string;
                    finish_element[0].innerHTML = finish_string;
                    document.getElementById('finish_value').value = finish_string;

                    finish_element[1].innerHTML = duration;
                    if (old_start !== start_string) {
                        createThumb(start_string, "start");
                    }
                    if (old_finish !== finish_string) {
                        createThumb(finish_string, "finish");
                    }
                });

                function createThumb(time, type) {
                    $.ajax({
                        type: "POST",
                        url: "{{route('process.thumbnail', $media->id)}}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            time_string: time,
                            type: type,
                            sid: '{{$media->sid}}',
                            ext: '{{$media->ext}}'
                        },
                        success: function (result) {
                            let d = new Date();
                            if (type === "start") {
                                $("#thumbnail-start").attr(
                                    "src",
                                    "{{ asset("storage/temp/{$media->sid}_start.jpg") }}?" + d.getTime()
                                );
                            } else {
                                $("#thumbnail-finish").attr(
                                    "src",
                                    "{{ asset("storage/temp/{$media->sid}_finish.jpg") }}?" + d.getTime()
                                );
                            }
                        }
                    });
                }
            </script>
    @endsection
</x-app-layout>
