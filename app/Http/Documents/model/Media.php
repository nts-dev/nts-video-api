<?php


namespace App\Http\Documents\model;


class Media
{

    private  $file;
    private $primaryPath;
    public function __construct($file, $primaryPath)
    {
        $this->file = $file;
        $this->primaryPath = $primaryPath;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return mixed
     */
    public function getPrimaryPath()
    {
        return $this->primaryPath;
    }


}
