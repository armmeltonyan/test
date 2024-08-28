<?php

namespace App\Jobs;

use App\Services\Image\ImageSqueezeService;
use App\Services\Image\ImageStoreService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public UploadedFile $file,
    )
    {
        $this->onQueue('upload-image');
    }

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle(ImageStoreService $imageStoreService,ImageSqueezeService $imageSqueezeService): void
    {
        $imageDto = $imageSqueezeService->squeeze($this->file->getRealPath());
        $image = $imageStoreService->store($imageDto);
//        $image === null
//            ? Notification::send($this->user, new ImageProcessingFailedNotification($imageUrl));//todo implement notification to user about upload failing
//            : Notification::send($this->user, new ImageProcessedNotification($imageUrl));//todo implement notification to user about upload success
    }
}
