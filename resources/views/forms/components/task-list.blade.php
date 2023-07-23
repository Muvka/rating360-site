<x-dynamic-component
    :component="$getFieldWrapperView()"
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :state-path="$getStatePath()"
>
    <div @class(['space-y-6 relative'])>
        <div @class([
            'rounded-xl bg-gray-50 relative dark:bg-gray-500/10 border border-gray-300 dark:border-gray-700',
        ])>
            <table class="w-full">
                <thead @class([
                    'rounded-t-xl overflow-hidden border-b border-gray-300 dark:border-gray-700'
                ])>
                <tr class="md:divide-x md:rtl:divide-x-reverse md:divide-gray-300 dark:md:divide-gray-700 text-sm">
                    @foreach ($getHeaders() as $header)
                        <th
                            @class([
                                'px-3 py-2 font-medium text-left text-gray-600 dark:text-gray-300 bg-gray-200/50 dark:bg-gray-900/60 text-center',
                                'ltr:rounded-tl-xl rtl:rounded-tr-xl' => $loop->first,
                                'ltr:rounded-tr-xl rtl:rounded-tl-xl' => $loop->last,
                            ])
                        >
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
                </thead>
                <tbody
                    class="divide-y divide-gray-300 dark:divide-gray-700"
                >
                @foreach($getItems() as $row)
                    <tr
                        @class([
                            'md:divide-x md:rtl:divide-x-reverse md:divide-gray-300',
                            'dark:md:divide-gray-700' => config('forms.dark_mode'),
                            'bg-danger-500/10' => $row[2] >= 10
                        ])
                    >
                        @foreach($row as $cell)
                            <td
                                @class([
                                    'filament-table-repeater-column p-2',
                                    'flex justify-center' => $loop->last,
                                    'text-center' => $loop->index === 2,
                                ])
                            >
                                @if($loop->last)
                                    <x-dynamic-component
                                        :component="$cell ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle'"
                                        @class([
                                            'h-6 w-6',
                                            'text-danger-500' => !$cell,
                                            'text-success-500' => $cell
                                        ])
                                    />
                                @else
                                    {{ $cell }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-dynamic-component>
