<?php

namespace App\Http\Documents;

use App\Http\Documents\MediaDocument;
use App\Http\Documents\model\Media;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ProtoneMedia\LaravelFFMpeg\Exporters\EncodingException;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
Use Exception;


class HSLDocument implements MediaDocument, ShouldQueue
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

        $lowBitrate = (new X264('aac'))->setKiloBitrate(250);
        $midBitrate = (new X264('aac'))->setKiloBitrate(500);
        $highBitrate = (new X264('aac'))->setKiloBitrate(1000);

        FFMpeg::fromDisk('public')
            ->open($this->media->getFile())
            ->exportForHLS()
            ->setSegmentLength(10) // optional
            ->setKeyFrameInterval(48) // optional
            ->addFormat($lowBitrate)
            ->addFormat($midBitrate)
            ->addFormat($highBitrate)
            ->save($this->media->getPrimaryPath() . '/hsl/manifest.m3u8');
    }


//    /**
//     * The job failed
//     *
//     * @param Exception $exception
//     */
//    public function failed(Exception $exception)
//    {
//        \Log::channel('encoding')->error('Encoding job failed ' . $exception->getMessage() . ' - '. $this->video->getFilename());
//
//    }
}
