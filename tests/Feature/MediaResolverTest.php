<?php

namespace Tests\Feature;

use App\Services\MediaResolver;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class MediaResolverTest extends TestCase
{
    public function test_media_urls_encode_each_path_segment(): void
    {
        Storage::fake('videosyfotos');
        Storage::disk('videosyfotos')->put('Galería escolar/Foto uno.jpg', 'imagen');

        $url = app(MediaResolver::class)->urlIfExists('Galería escolar/Foto uno.jpg');

        $this->assertNotNull($url);
        $this->assertStringStartsWith('/media/Galer%C3%ADa%20escolar/Foto%20uno.jpg?v=', $url);
    }

    public function test_public_upload_url_requires_an_existing_file(): void
    {
        Storage::fake('public');
        $resolver = app(MediaResolver::class);

        $this->assertNull($resolver->publicUploadUrl('secciones/inexistente.jpg'));

        Storage::disk('public')->put('secciones/imagen principal.jpg', 'imagen');

        $this->assertSame(
            '/storage/secciones/imagen%20principal.jpg',
            $resolver->publicUploadUrl('secciones/imagen principal.jpg'),
        );
    }

    public function test_media_paths_reject_directory_traversal(): void
    {
        $this->expectException(NotFoundHttpException::class);

        app(MediaResolver::class)->normalizePath('../.env');
    }
}
