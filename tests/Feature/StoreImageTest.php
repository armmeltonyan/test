<?php

namespace Tests\Feature;

use App\Dto\ImageDto;
use App\Services\Image\ImageSqueezeService;
use App\Services\Image\ImageStoreService;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\MockObject\Exception;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Tests\TestCase;

class StoreImageTest extends TestCase
{
    protected string $endpoint = '/api/image';

    /**
     * @throws UnknownProperties
     * @throws Exception
     */
    public function test_upload_success(): void
    {
        // Fake image file
        $fakeFile = UploadedFile::fake()->image('test-image.png');

        // Mock the ImageSqueezeService
        $imageSqueezeServiceMock = $this->createMock(ImageSqueezeService::class);
        $imageData = file_get_contents($fakeFile->getRealPath()); // Simulate the squeezed image data
        $imageDto = new ImageDto(
            image: $imageData,
            path: 'path/to/image.png' // Use a consistent path
        );

        $imageSqueezeServiceMock->expects($this->once())
            ->method('squeeze')
            ->with($this->equalTo($fakeFile->getRealPath()))
            ->willReturn($imageDto);

        $this->app->instance(ImageSqueezeService::class, $imageSqueezeServiceMock);

        // Mock the ImageStoreService
        $imageStoreServiceMock = $this->createMock(ImageStoreService::class);
        $expectedImageUrl = 'storage/path/to/image.png';

        $imageStoreServiceMock->expects($this->once())
            ->method('store')
            ->with($this->equalTo($imageDto))
            ->willReturn($expectedImageUrl);

        $this->app->instance(ImageStoreService::class, $imageStoreServiceMock);

        // Send a POST request to the endpoint with the fake image file
        $response = $this->postJson($this->endpoint, [
            'image' => $fakeFile,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => __('messages.image.upload'),
                'data' => [
                    'url' => $expectedImageUrl,
                ],
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    'url',
                ],
                'message',
                'status',
            ]);
    }

    /**
     * @throws UnknownProperties
     * @throws Exception
     */
    public function test_upload_failure(): void
    {
        // Fake image file
        $fakeFile = UploadedFile::fake()->image('test-image.png');

        // Mock the ImageSqueezeService
        $imageSqueezeServiceMock = $this->createMock(ImageSqueezeService::class);
        $imageData = file_get_contents($fakeFile->getRealPath()); // Simulate the squeezed image data
        $imageDto = new ImageDto(
            image: $imageData,
            path: 'path/to/image.png'
        );

        $imageSqueezeServiceMock->expects($this->once())
            ->method('squeeze')
            ->with($this->equalTo($fakeFile->getRealPath()))
            ->willReturn($imageDto);

        $this->app->instance(ImageSqueezeService::class, $imageSqueezeServiceMock);

        // Mock the ImageStoreService
        $imageStoreServiceMock = $this->createMock(ImageStoreService::class);

        $imageStoreServiceMock->expects($this->once())
            ->method('store')
            ->with($this->equalTo($imageDto))
            ->willReturn(null);

        $this->app->instance(ImageStoreService::class, $imageStoreServiceMock);

        // Send a POST request to the endpoint with the fake image file
        $response = $this->postJson($this->endpoint, [
            'image' => $fakeFile,
        ]);

        $response->assertStatus(500)
            ->assertJson([
                'success' => false,
                'message' => __('errors.image.upload'),
            ]);
    }

    public function test_upload_validation_fail(): void
    {
        // Send a POST request to the endpoint with the not valid image
        $response = $this->postJson($this->endpoint, [
            'image' => 'string file',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors',
            ]);
    }
}
