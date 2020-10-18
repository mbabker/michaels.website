<?php

return [
    'default_collection' => null,

    'collections' => [
        'blog' => [
            'disk' => 'blog',
            'sheet_class' => App\Sheets\BlogPost::class,
            'path_parser' => Spatie\Sheets\PathParsers\SlugWithDateParser::class,
            'content_parser' => Spatie\Sheets\ContentParsers\MarkdownWithFrontMatterParser::class,
            'extension' => 'md',
        ],
    ],
];
