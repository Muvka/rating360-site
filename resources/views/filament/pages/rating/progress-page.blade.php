<x-filament::page>
    <form wire:submit.prevent="submit" class="space-y-6">
        {{ $this->form }}
        <x-filament::button size="md" type="submit">
            Фильтровать
        </x-filament::button>
    </form>
    @if($data)
        <div @class([
                'space-y-2 bg-white rounded-xl shadow overflow-x-auto',
                'dark:border-gray-600 dark:bg-gray-800' => config('filament.dark_mode'),
            ])>
            <x-filament-tables::table class="w-full overflow-hidden text-sm">
                <x-slot name="header">
                    @foreach($columns as $column)
                        <x-filament-tables::header-cell>
                            {{$column['label']}}
                        </x-filament-tables::header-cell>
                    @endforeach
                </x-slot>
                @foreach($data as $row)
                    <x-filament-tables::row @style(['--c-100: var(--danger-100)']) @class(['bg-custom-100' => $row['quantity'] >= 10])>
                        @foreach($columns as $column)
                            <x-filament-tables::cell class="px-4 py-2 align-top">
                                @if($loop->last)
                                    <x-dynamic-component
                                        :component="$row[$column['key']] ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle'"
                                        @style(['--c-600: var(--success-600)'])
                                        @class([
                                            'h-6 w-6',
                                            'text-danger-600' => !$row[$column['key']],
                                            'text-custom-600' => $row[$column['key']]
                                        ])
                                    />
                                @else
                                    {{ $row[$column['key']] }}
                                @endif
                            </x-filament-tables::cell>
                        @endforeach
                    </x-filament-tables::row>
                @endforeach
            </x-filament-tables::table>
        </div>
    @endif
</x-filament::page>
