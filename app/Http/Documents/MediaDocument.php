<?php

namespace App\Http\Documents;


use Illuminate\Contracts\Queue\ShouldQueue;

interface MediaDocument extends ShouldQueue {
    const DISK = 'media';
    function handle();

}
