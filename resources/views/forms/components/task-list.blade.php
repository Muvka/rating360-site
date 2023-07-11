<x-dynamic-component
    :component="$getFieldWrapperView()"
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :state-path="$getStatePath()"
>
    <div
        class="border border-gray-300 shadow-sm bg-white rounded-xl filament-tables-container dark:bg-gray-800 dark:border-gray-700">
        <div class="filament-tables-table-container overflow-x-auto relative dark:border-gray-700 rounded-t-xl">
            <table class="filament-tables-table w-full text-start divide-y table-auto dark:divide-gray-700">
                <thead>
                <tr class="bg-gray-500/5">
                    @foreach($getHeaders() as $header)
                        <th class="filament-tables-header-cell p-0 filament-table-header-cell-№">
                            <button type="button"
                                    class="flex items-center gap-x-1 w-full px-4 py-2 whitespace-nowrap font-medium text-sm text-gray-600 dark:text-gray-300 cursor-default ">
                        <span>
                            {{$header}}
                        </span>
                            </button>
                        </th>
                    @endforeach
                </tr>
                </thead>

                <tbody class="divide-y whitespace-nowrap dark:divide-gray-700">
                @foreach($getItems() as $item)
                    <tr class="filament-tables-row">
                        @foreach($item as $index => $subItem)
                            <td class="filament-tables-cell dark:text-white filament-table-cell-№">
                                <div class="filament-tables-column-wrapper">
                                    <div class="flex w-full justify-start text-start">
                                        <div class="filament-tables-text-column px-4 py-3">
                                            <div class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                                            <span class="">
                                                @if ($index +1 === count($item))
                                                    @if ($subItem)
                                                        <x-heroicon-o-check-circle
                                                            class="w-6 h-6 text-success-700"></x-heroicon-o-check-circle>
                                                    @else
                                                        <x-heroicon-o-x-circle
                                                            class="w-6 h-6 text-danger-700"></x-heroicon-o-x-circle>
                                                    @endif
                                                @else
                                                    {{$subItem}}
                                                @endif
                                            </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-dynamic-component>
