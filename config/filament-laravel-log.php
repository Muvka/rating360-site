<?php

return [
    /**
     * Secure the page behind a custom policy.
     */
    'authorization' => false,

    /**
     * The directory(ies) containing the log files.
     */
    'logsDir' => [
        storage_path('logs'),
    ],

    /**
     * Files to ignore when searching for log files.
     * Accepts wildcards eg: *.log
     */
    'exclude' => [
        //
    ],

    /**
     * Navigation group.
     */
    'navigationGroup' => 'Система',

    /**
     * Navigation sort.
     */
    'navigationSort' => 10,

    /**
     * Navigation icon.
     */
    'navigationIcon' => 'heroicon-o-document-text',

    /**
     * Navigation label.
     */
    'navigationLabel' => 'Логи',

    /**
     * Navigation slug.
     */
    'slug' => 'system-logs',

    /**
     * Maximum amount of lines that editor will render.
     */
    'maxLines' => 50,

    /**
     * Minimum amount of lines that editor will render.
     */
    'minLines' => 10,

    /**
     * Editor font size.
     */
    'fontSize' => 12,
];
