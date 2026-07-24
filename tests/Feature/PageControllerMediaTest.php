<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PageControllerMediaTest extends TestCase
{
    public function test_media_route_serves_an_existing_file_with_long_lived_cache_headers(): void
    {
        Storage::fake('videosyfotos');
        Storage::disk('videosyfotos')->put('galeria/imagen.jpg', 'imagen de prueba');

        $response = $this->get('/media/galeria/imagen.jpg');

        $response->assertOk();
        $cacheControl = $response->headers->get('Cache-Control');
        $this->assertStringContainsString('public', $cacheControl);
        $this->assertStringContainsString('max-age=31536000', $cacheControl);
        $this->assertStringContainsString('immutable', $cacheControl);
        $this->assertSame('imagen de prueba', $response->streamedContent());
    }

    public function test_media_route_returns_not_found_for_a_missing_file(): void
    {
        Storage::fake('videosyfotos');

        $this->get('/media/galeria/inexistente.jpg')->assertNotFound();
    }

    public function test_media_route_rejects_directory_traversal(): void
    {
        Storage::fake('videosyfotos');

        $this->get('/media/%2E%2E/%2Eenv')->assertNotFound();
    }
}
