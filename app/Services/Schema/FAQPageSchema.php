<?php

namespace App\Services\Schema;

use App\Models\Faq;

class FAQPageSchema
{
    public static function fromFaqs(array $faqs): array
    {
        $mainEntity = [];
        
        foreach ($faqs as $faq) {
            $mainEntity[] = [
                '@type' => 'Question',
                'name' => $faq instanceof Faq ? $faq->question : $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq instanceof Faq ? $faq->answer : $faq['answer'],
                ],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $mainEntity,
        ];
    }

    public static function fromSingleFaq(Faq $faq): array
    {
        return self::fromFaqs([$faq]);
    }
}

