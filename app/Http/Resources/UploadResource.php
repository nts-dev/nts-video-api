<?php

namespace App\Http\Resources;

use App\Subject;
use Illuminate\Http\Resources\Json\JsonResource;

class UploadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $ROOT = "http://".$_SERVER['HTTP_HOST']. "/nts-programs/video/";
        $RESOURCE = $ROOT."/mediaresources/"
            . $this->generateProjectId($this->subject_id) . "/"
            . $this->module_id . "/video/" . $this->id . "/";

        $hsl_url = "https://video.nts.nl/media/f_" . $this->upload_folder_index . "/" . $this->videoLink;
        $hsl_url_2 = $RESOURCE . "/hsl/master.m3u8";

        $raw_media_link = $RESOURCE . "/media.mp4";

        $thumb_url = $RESOURCE . "/thumbnails/0_thumbnail.png";

        $low_res_thumb_url = "https://video.nts.nl/uploads/thumbnails/f_" . $this->upload_folder_index . "/thumbnail_main.jpg";

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'thumbnailLink' => $thumb_url,
            'low_res_thumbnailLink' => $low_res_thumb_url,

            'videoLink' => $hsl_url_2,
            'videoLink_raw' => $raw_media_link,
            'publish' => $this->publish,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
            'user_first_name' => isset($this->user->profile) ? $this->user->profile->first_name : null,
            'user_last_name' => isset($this->user->profile) ? $this->user->profile->last_name : null,
            'user_picture' => isset($this->user->profile) ? $this->user->profile->profile_pic_url : null,
            'total_views' => isset($this->views) ? $this->views->count() : 0,
            'subtitles' => $this->subtitle,
            'module_name' => $this->module->title,
            'module_description' => $this->module->description,
            'subject_id' => $this->subject_id,
            'user_id' => $this->user_id,
            'module_id' => $this->module_id,

        ];
    }


    private function generateProjectId($itemId)
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

}
