<?php
/**
 * Upload and manipulate video file
 * Created by PhpStorm.

 * Date: 05/07/2020
 * Time: 11:49 AM
 * Neotis framework
 */

namespace Neotis\Core\Upload\Image;

use Neotis\Core\Upload\Upload;

class Video extends Upload
{
    /**
     * Start upload file and transfer requested file to server
     * @return $this
     */
    public function start()
    {
        if (!$this->uploadedInfo()) {//Fetch file info
            return $this;
        }

        if ($this->fileSize !== 0) {//Check status of file size and limitation
            if (!$this->checkSize()) {
                return $this;
            }
        }

        if (!$this->checkExtension()) {//Check extension of uploaded file with defined limit
            return $this;
        }

        $this->storeFile();

        return $this;
    }
}
