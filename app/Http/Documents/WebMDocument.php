<?php

namespace App\Http\Documents;

use App\Http\Documents\MediaDocument;
use App\Http\Documents\model\Media;
use FFMpeg\Format\Video\WebM;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ProtoneMedia\LaravelFFMpeg\Exporters\EncodingException;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;


class WebMDocument implements MediaDocument, ShouldQueue
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
            $errorLog = $exception->getErrorOutput();
            var_dump($errorLog);
        }

    }

    private function runCommand()
    {

        $format = (new WebM());
        FFMpeg::fromDisk('public')
            ->open($this->media->getFile())
            ->export()
            ->inFormat($format)
            ->save($this->media->getPrimaryPath() . '/web.webm');


    }
}
