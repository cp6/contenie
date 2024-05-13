<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit '.$media->upload->original_name) }}
        </h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid sm:grid-cols-12 grid-cols-1 gap-2">
            <div
                class="col-span-4 p-2 bg-white border border-gray-200 rounded-lg shadow sm:p-4 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white">Details</h5>
                </div>
                <div class="flow-root">
                    <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                        <li class="py-3 sm:py-4">
                            <div class="flex items-center">
                                <div class="flex-1 min-w-0 ms-4">
                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                        Size
                                    </p>
                                </div>
                                <div
                                    class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                    {{$media->size_kb / 1000}} MB
                                </div>
                            </div>
                        </li>
                        <li class="py-3 sm:py-4">
                            <div class="flex items-center ">
                                <div class="flex-1 min-w-0 ms-4">
                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                        Dimensions
                                    </p>
                                </div>
                                <div
                                    class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                    {{$media->height}} x {{$media->width}}
                                </div>
                            </div>
                        </li>
                        <li class="py-3 sm:py-4">
                            <div class="flex items-center">
                                <div class="flex-1 min-w-0 ms-4">
                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                        Bitrate
                                    </p>
                                </div>
                                <div
                                    class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                    {{$media->bitrate_kbs}} Kbps
                                </div>
                            </div>
                        </li>
                        <li class="py-3 sm:py-4">
                            <div class="flex items-center">
                                <div class="flex-1 min-w-0 ms-4">
                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                        Duration
                                    </p>
                                </div>
                                <div
                                    class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                    {{date('H:i:s', \round($media->duration))}}
                                </div>
                            </div>
                        </li>
                        <li class="py-3 sm:py-4">
                            <div class="flex items-center">
                                <div class="flex-1 min-w-0 ms-4">
                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                        Codec
                                    </p>
                                </div>
                                <div
                                    class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                    {{$media->codec}}
                                </div>
                            </div>
                        </li>
                        <li class="py-3 sm:py-4">
                            <div class="flex items-center">
                                <div class="flex-1 min-w-0 ms-4">
                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                        Framerate
                                    </p>
                                </div>
                                <div
                                    class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                    {{$media->framerate}} fps
                                </div>
                            </div>
                        </li>
                        <li class="py-3 sm:py-4">
                            <div class="flex items-center">
                                <div class="flex-1 min-w-0 ms-4">
                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                        Ratio
                                    </p>
                                </div>
                                <div
                                    class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                    {{$media->aspect_ratio}}
                                </div>
                            </div>
                        </li>
                        <li class="py-3 sm:py-4">
                            <div class="flex items-center">
                                <div class="flex-1 min-w-0 ms-4">
                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                        Audio streams
                                    </p>
                                </div>
                                <div
                                    class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                    {{$media->audio_streams}}
                                </div>
                            </div>
                        </li>
                        <li class="py-3 sm:py-4">
                            <div class="flex items-center">
                                <div class="flex-1 min-w-0 ms-4">
                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                        Directory
                                    </p>
                                </div>
                                <div
                                    class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                    {{$media->directory->name}}
                                </div>
                            </div>
                        </li>
                        <li class="py-3 sm:py-4">
                            <div class="flex items-center">
                                <div class="flex-1 min-w-0 ms-4">
                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                        SID
                                    </p>
                                </div>
                                <div
                                    class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                    {{$media->sid}}
                                </div>
                            </div>
                        </li>
                    </ul>
                    <img class="rounded object-scale-down"
                         src="{{ asset("storage/{$media->directory->name}/{$media->sid}.jpg") }}" alt="test">
                </div>
            </div>
            <div
                class="col-span-6 p-2 bg-white border border-gray-200 rounded-lg shadow sm:p-4 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white">Process</h5>
                    <a href="{{route('upload.meta', $media->sid)}}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Next</a>
                </div>
                <div class="flow-root">
                    <form class="space-y-6" action="{{route('process.store', $media->sid)}}" method="POST">
                        @csrf
                        <div>
                            <input type="hidden" name="media_id" value="{{$media->id}}">
                            <label for="main_bitrate" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Main
                                version bitrate Kbps</label>
                            <input type="number" step="1" id="main_bitrate" name="main_bitrate" value="{{(int)$media->bitrate_kbs}}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                   placeholder="" min="200" max="{{$media->bitrate_kbs}}" required/>
                        </div>
                        <div>
                            <label for="second_ratio"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Second
                                version size</label>
                            <select id="second_ratio" name="second_ratio"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value=""></option>
                                @foreach ($ratios as $ratio)
                                    @if($ratio['width'] > $media->width)
                                        @continue
                                    @endif
                                    <option value="{{$ratio['width']}}:{{$ratio['height']}}" @if($loop->index === 2) selected @endif>{{$ratio['width']}}
                                        x {{$ratio['height']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="second_bitrate"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Second version
                                bitrate Kbps</label>
                            <input type="number" name="second_bitrate" step="1" id="second_bitrate"
                                   value="{{(int)($media->bitrate_kbs / 2)}}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                   placeholder="" min="200" max="{{$media->bitrate_kbs}}" required/>
                        </div>
                        <div>
                            <label for="third_ratio"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Third
                                version size</label>
                            <select id="third_ratio" name="third_ratio"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value=""></option>
                                @foreach ($ratios as $ratio)
                                    @if($ratio['width'] > $media->width)
                                        @continue
                                    @endif
                                    <option value="{{$ratio['width']}}:{{$ratio['height']}}" @if($loop->index === 4) selected @endif>{{$ratio['width']}}
                                        x {{$ratio['height']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="third_bitrate"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Third version
                                bitrate Kbps</label>
                            <input type="number" name="third_bitrate" step="1" id="third_bitrate"
                                   value="{{(int)($media->bitrate_kbs / 3)}}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                   placeholder="" min="200" max="{{$media->bitrate_kbs}}" required/>
                        </div>
                        <div>
                            <label for="fourth_ratio"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fourth
                                version size</label>
                            <select id="fourth_ratio" name="fourth_ratio"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value=""></option>
                                @foreach ($ratios as $ratio)
                                    @if($ratio['width'] > $media->width)
                                        @continue
                                    @endif
                                    <option value="{{$ratio['width']}}:{{$ratio['height']}}" @if($loop->index === 5) selected @endif>{{$ratio['width']}}
                                        x {{$ratio['height']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="fourth_bitrate"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fourth version
                                Bitrate Kbps</label>
                            <input type="number" name="fourth_bitrate" step="1" id="fourth_bitrate"
                                   value="{{(int)($media->bitrate_kbs / 4)}}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                   placeholder="" min="200" max="{{$media->bitrate_kbs}}" required/>
                        </div>
                        <button type="submit"
                                class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Process
                        </button>
                    </form>
                </div>
            </div>
        </div>
</x-app-layout>
