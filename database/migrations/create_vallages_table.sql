CREATE TABLE IF NOT EXISTS villages (
    `id` SERIAL PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `heading` TEXT,
    `image_url` VARCHAR(512),
    `description_sections` JSON,
    `gallery_urls` JSON,
    `location` VARCHAR(255),
    `google_map_embed_code` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);