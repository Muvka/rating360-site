<x-filament::page>
    <form wire:submit.prevent="submit" class="space-y-6">
        {{ $this->form }}
        <x-forms::button size="md" type="submit">
            Фильтровать
        </x-forms::button>
    </form>
    @if($data)
        <x-filament::hr/>
        <div @class([
                'p-2 space-y-2 bg-white rounded-xl shadow',
                'dark:border-gray-600 dark:bg-gray-800' => config('filament.dark_mode'),
            ])>
            <x-tables::table class="w-full overflow-hidden text-sm">
                <x-slot:header>
                    @foreach($columns as $column)
                        <x-tables::header-cell>
                            {{$column['label']}}
                        </x-tables::header-cell>
                    @endforeach
                </x-slot:header>
                @foreach($data as $row)
                    <x-tables::row @class(['bg-danger-500/10' => $row['quantity'] >= 10])>
                        @foreach($columns as $column)
                            <x-tables::cell class="px-4 py-2 align-top">
                                @if($loop->last)
                                    <x-dynamic-component
                                        :component="$row[$column['key']] ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle'"
                                        @class([
                                            'h-6 w-6',
                                            'text-danger-500' => !$row[$column['key']],
                                            'text-success-500' => $row[$column['key']]
                                        ])
                                    />
                                @else
                                    {{ $row[$column['key']] }}
                                @endif
                            </x-tables::cell>
                        @endforeach
                    </x-tables::row>
                @endforeach
            </x-tables::table>
        </div>
    @endif
</x-filament::page>
