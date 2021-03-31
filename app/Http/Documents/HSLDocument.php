<?php

namespace App\Http\Documents;

use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Storage;
use ProtoneMedia\LaravelFFMpeg\Exporters\EncodingException;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
Use Exception;


class HSLDocument implements MediaDocument
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
            Log::debug((array) $errorLog);
            var_dump($errorLog);
        }

    }

    private function runCommand()
    {

        $lowBitrate = (new X264('aac'))->setKiloBitrate(250);
        $midBitrate = (new X264('aac'))->setKiloBitrate(500);
        $highBitrate = (new X264('aac'))->setKiloBitrate(1000);

        FFMpeg::
            open($this->media->getFile())
            ->exportForHLS()
//            ->toDisk()
            ->setSegmentLength(10) // optional
            ->setKeyFrameInterval(48) // optional
            ->addFormat($lowBitrate)
            ->addFormat($midBitrate)
            ->addFormat($highBitrate)
            ->save('hsl/master.m3u8');
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
