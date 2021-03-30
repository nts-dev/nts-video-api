<?php


namespace App\Http\Documents\model;


class Media
{

    private  $file;
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }


}
