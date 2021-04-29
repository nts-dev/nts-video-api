<?php


namespace App\Http\Commons;


class Util
{

    static public function generateProjectId($itemId)
    {
        if (strlen($itemId) == 1) {
            $projectId = "P00000" . $itemId . "";
        } else if (strlen($itemId) == 2) {
            $projectId = "P0000" . $itemId . "";
        } else if (strlen($itemId) == 3) {
            $projectId = "P000" . $itemId . "";
        } else if (strlen($itemId) == 4) {
            $projectId = "P00" . $itemId . "";
        } else if (strlen($itemId) == 5) {
            $projectId = "P0" . $itemId . "";
        } else {
            $projectId = $itemId;
        }

        return $projectId;
    }


    static public function ofFileType($extension)
    {
        $video = array("mp4", "MOV", "WMV", "FLV", "AVI", "MKV", "WebM");
//        $audio = array("mp3", "WAV");
        if (in_array($extension, $video)) return "video";
        return "audio";
    }


}
