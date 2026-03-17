<?php

namespace App\Services;

class MetaTagsService
{
    private array $tags = [];
    private string $siteName = "Твоят Бранд";

    public function __construct(array $data = [])
    {
        $currentUrl = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $this->tags = [
            'title'       => $data['title'] ?? 'Начало',
            'description' => $data['description'] ?? 'Професионални решения за вашия бизнес.',
            'url'         => $data['url'] ?? $currentUrl,
            'image'       => $data['image'] ?? 'https://yourdomain.com/default-og.jpg',
            'canonical'   => $data['canonical'] ?? $currentUrl,
            'author'      => $data['author'] ?? 'Твоят Бранд ЕООД',
            'type'        => $data['type'] ?? 'website',
            'robots'      => $data['robots'] ?? 'index, follow',
        ];
    }

    public function render(): string
    {
        ob_start();
?>

        <title><?= htmlspecialchars($this->tags['title']) ?> | <?= $this->siteName ?></title>
        <meta name="description" content="<?= htmlspecialchars($this->tags['description']) ?>">
        <meta name="robots" content="<?= $this->tags['robots'] ?>">
        <link rel="canonical" href="<?= $this->tags['canonical'] ?>">
        <meta name="author" content="<?= $this->tags['author'] ?>">

        <meta property="og:site_name" content="<?= $this->siteName ?>">
        <meta property="og:title" content="<?= htmlspecialchars($this->tags['title']) ?>">
        <meta property="og:description" content="<?= htmlspecialchars($this->tags['description']) ?>">
        <meta property="og:url" content="<?= $this->tags['url'] ?>">
        <meta property="og:image" content="<?= $this->tags['image'] ?>">
        <meta property="og:type" content="<?= $this->tags['type'] ?>">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="<?= htmlspecialchars($this->tags['title']) ?>">
        <meta name="twitter:description" content="<?= htmlspecialchars($this->tags['description']) ?>">
        <meta name="twitter:image" content="<?= $this->tags['image'] ?>">

        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "WebSite",
                "name": "Аз Мигрантът",
                "url": "https://azmigrantat.com",
                "potentialAction": {
                    "@type": "SearchAction",
                    "target": "https://azmigrantat.com/search?q={search_term_string}",
                    "query-input": "required name=search_term_string"
                },
                "description": "Портал за информация и услуги за мигранти - работа, застраховки, преводи.",
                "hasOfferCatalog": {
                    "@type": "OfferCatalog",
                    "name": "Мигрантски услуги",
                    "itemListElement": [{
                            "@type": "Offer",
                            "itemOffered": {
                                "@type": "Service",
                                "name": "Застраховки"
                            }
                        },
                        {
                            "@type": "Offer",
                            "itemOffered": {
                                "@type": "Service",
                                "name": "Работа в чужбина"
                            }
                        },
                        {
                            "@type": "Offer",
                            "itemOffered": {
                                "@type": "Service",
                                "name": "Преводи и легализация"
                            }
                        }
                    ]
                }
            }
        </script>

<?php
        return ob_get_clean();
    }
}
