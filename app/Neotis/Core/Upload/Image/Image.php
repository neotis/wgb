<?php
/**
 * Upload and manipulate image files
 * Created by PhpStorm.

 * Date: 05/07/2020
 * Time: 11:49 AM
 * Neotis framework
 */

namespace Neotis\Core\Upload\Image;

use Neotis\Core\Services\Methods;
use Neotis\Core\Upload\Upload;
use Neotis\Interfaces\Core\Upload\Compress;

class Image extends Upload
{
    /**
     * Compress uploaded file
     * @param Compress $compress
     * @param bool $destiny
     */
    public function compress(Compress $compress, $destiny = false)
    {
        $source = $this->finalFile[0];
        $final = $this->finalFile[0];

        if ($destiny) {
            $final = $destiny;
        }

        $compress->uploaded($source, $final);
    }

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
