<?php

namespace App\Support;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

/**
 * Builds SEO-friendly metadata (title, description, canonical URL,
 * Open Graph, Twitter cards and JSON-LD structured data) for the
 * customer-facing pages.
 */
class Seo
{
    protected array $data = [
        'title' => 'GODEVI - Authentic Village Experiences',
        'description' => 'GODEVI (Go Destination Village) is a socially pro-active tourism business dedicated to uplifting local communities in developing villages across Bali, Indonesia through sustainable and responsible village tourism.',
        'keywords' => [],
        'image' => null,
        'canonical' => null,
        'robots' => 'index,follow',
        'type' => 'website',
        'schema' => [],
    ];

    public static function make(array $data = []): self
    {
        $instance = new self;

        foreach ($data as $key => $value) {
            if (method_exists($instance, $key)) {
                $instance->{$key}($value);
            } else {
                $instance->data[$key] = $value;
            }
        }

        return $instance;
    }

    public function title(string $title): self
    {
        $title = trim($title);

        $this->data['title'] = $title !== '' ? $title.' | GODEVI' : $this->data['title'];

        return $this;
    }

    public function description(string $description): self
    {
        $this->data['description'] = Str::limit(strip_tags($description), 158, '...');

        return $this;
    }

    public function keywords(array $keywords): self
    {
        $this->data['keywords'] = $keywords;

        return $this;
    }

    public function image(?string $image): self
    {
        $this->data['image'] = $image ? URL::to($image) : null;

        return $this;
    }

    public function canonical(?string $canonical = null): self
    {
        $this->data['canonical'] = $canonical ? URL::to($canonical) : URL::current();

        return $this;
    }

    public function robots(string $robots): self
    {
        $this->data['robots'] = $robots;

        return $this;
    }

    public function noindex(): self
    {
        $this->data['robots'] = 'noindex,nofollow';

        return $this;
    }

    public function type(string $type): self
    {
        $this->data['type'] = $type;

        return $this;
    }

    public function schema(array $schema): self
    {
        $this->data['schema'] = array_merge($this->data['schema'], $schema);

        return $this;
    }

    public function organizationSchema(): self
    {
        $this->data['schema']['organization'] = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            '@id' => URL::to('#organization'),
            'name' => 'GODEVI',
            'legalName' => 'PT Banua Wisata Lestari',
            'alternateName' => 'Go Destination Village',
            'url' => URL::to('/'),
            'logo' => URL::to('assets/customer/img/logo.png'),
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => '+62-819-9767-4778',
                'contactType' => 'customer service',
                'email' => 'hello@godestinationvillage.com',
                'areaServed' => 'ID',
            ],
            'sameAs' => [
                'https://www.facebook.com/godestinationvillage/',
                'https://www.instagram.com/godestinationvillage/',
                'https://www.youtube.com/channel/UCule1cMKmK4RKh_n-Rrx81A',
            ],
        ];

        return $this;
    }

    public function websiteSchema(): self
    {
        $this->data['schema']['website'] = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => URL::to('#website'),
            'name' => 'GODEVI - Authentic Village Experiences',
            'url' => URL::to('/'),
            'publisher' => ['@id' => URL::to('#organization')],
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => URL::to('/search?search={search_term_string}'),
                'query-input' => 'required name=search_term_string',
            ],
        ];

        return $this;
    }

    public function webPageSchema(?string $name = null): self
    {
        $this->data['schema']['webpage'] = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $name ?? $this->data['title'],
            'description' => $this->data['description'],
            'url' => $this->data['canonical'],
            'inLanguage' => app()->getLocale() === 'id' ? 'id-ID' : 'en-US',
            'isPartOf' => ['@id' => URL::to('#website')],
            'publisher' => ['@id' => URL::to('#organization')],
        ];

        return $this;
    }

    public function breadcrumbSchema(array $items): self
    {
        $list = [];
        $position = 1;

        foreach ($items as $name => $url) {
            $list[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'name' => $name,
                'item' => URL::to($url),
            ];
            $position++;
        }

        $this->data['schema']['breadcrumb'] = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $list,
        ];

        return $this;
    }

    public function articleSchema(array $article): self
    {
        $this->data['schema']['article'] = array_merge([
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $article['headline'] ?? $this->data['title'],
            'description' => $this->data['description'],
            'image' => $this->data['image'],
            'url' => $this->data['canonical'],
            'inLanguage' => app()->getLocale() === 'id' ? 'id-ID' : 'en-US',
            'publisher' => ['@id' => URL::to('#organization')],
            'mainEntityOfPage' => $this->data['canonical'],
        ], array_filter($article));

        return $this;
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
