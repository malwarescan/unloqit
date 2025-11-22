<?php

namespace App\Services\Schema;

class WebPageSchema
{
    public static function generate(string $name, string $url, string $description = null): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $name,
            'url' => $url,
        ];

        if ($description) {
            $schema['description'] = $description;
        }

        return $schema;
    }
}

