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


class SplittedDocument implements MediaDocument
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $media;
    private $startTimeStamp;
    private $endTimeStamp;


    public function __construct($media, $startTimeStamp, $endTimeStamp)
    {
        $this->media = $media;
        $this->startTimeStamp = $startTimeStamp;
        $this->endTimeStamp = $endTimeStamp;
    }

    function handle()
    {
        try {
            $this->runCommand();
        } catch (EncodingException $exception) {
//            $command = $exception->getCommand();
            $errorLog = $exception->getErrorOutput();
            //TODO
            /**
             * Add logger for processes error
             */
            var_dump($errorLog);
        }
    }

    private function runCommand()
    {


    }
}
