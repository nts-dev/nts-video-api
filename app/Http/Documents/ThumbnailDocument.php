<?php

namespace App\Http\Documents;

use App\Http\Documents\MediaDocument;
use App\Http\Documents\model\Media;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ProtoneMedia\LaravelFFMpeg\Exporters\EncodingException;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;


class ThumbnailDocument implements MediaDocument
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $media;


    public function __construct($media)
    {
        $this->media = $media;
    }

    public function handle()
    {
        try {
            $this->runCommand();
        } catch (EncodingException $exception) {
            $command = $exception->getCommand();
            $errorLog = $exception->getErrorOutput();
            var_dump($errorLog);
        }
    }

    private function runCommand()
    {

        FFMpeg::fromDisk(self::DISK)
            ->open($this->media->getFile())
            ->each([5, 15, 25, 35], function ($ffmpeg, $seconds, $key) {
                $ffmpeg->getFrameFromSeconds($seconds)
                    ->export()
                    ->toDisk($this->media->getPrimaryPath() . "/thumbnails")
                    ->save("thumb_{$key}.png");
            });
    }
}
