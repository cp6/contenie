<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Uploads') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden sm:rounded-lg">
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                SID
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Mime
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Size MB
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Uploaded
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($uploads as $u)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="px-6 py-4">
                                    {{$u->sid}}
                                </td>
                                <td class="px-6 py-4">
                                    {{$u->media->mime}}
                                </td>
                                <td class="px-6 py-4">
                                    {{number_format($u->media->size_kb / 1024)}}
                                </td>
                                <td class="px-6 py-4">
                                    {{$u->created_at}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
