<?php
/**
 * A class to manage file uploads
 * Created by PhpStorm.

 * Date: 05/29/2019
 * Time: 11:49 AM
 * Neotis framework
 */

namespace Neotis\Core\Upload;

use \Neotis\Core\Neotis;
use Neotis\Core\Services\Methods;
use Neotis\Core\Upload\Config\Basic;
use Neotis\Core\Upload\Config\MimeTypes;
use Neotis\Core\Upload\Image\Image;

class Upload extends Neotis
{
    /**
     * Import MimeTypes trait for working with extensions
     * This trait have list of mime types and translator of file extensions
     */
    use MimeTypes;

    /**
     * Import basic config of upload engine
     */
    use Basic;

    /**
     * Fetch and access to uploaded file information and store in specific variables
     * for manipulation in different method in current class
     */
    protected function uploadedInfo()
    {
        if (!empty($_FILES)) {
            foreach ($_FILES as $key => $value) {
                if ($value['size'] > '0') {
                    $path = pathinfo($value['name']);
                    $this->fileExtension = $path['extension'];
                    $this->fileName = $value['name'];
                    $this->fileSize = $value['size'];
                    $this->fileType = $value['type'];
                    $this->tempName = $value['tmp_name'];
                    return true;
                }
            }
        }
        $this->error[] = [
            'code' => '2',
            'result' => false,
            'message' => 'Your request for upload file is empty'
        ];
        return false;
    }

    /**
     * Display error information when upload file to server as a json format
     */
    public function errorInfo()
    {
        return $this->error;
    }

    /**
     * Check and comparison size of uploaded file and defined limit
     * @return bool
     */
    protected function checkSize()
    {
        if ($this->fileSize > ($this->maxSize * 1024)) {
            $this->error[] = [
                'code' => '1',
                'result' => false,
                'message' => 'The size of the uploaded file is greater than the specified limit'
            ];
            return false;
        }
        return true;
    }

    /**
     * With this method, application check extension of uploaded file in first level and comparison with defined limit
     * In this level you can check $_FILES type with defined limit
     * @return bool
     */
    protected function checkExtension()
    {
        $ext = $this->mime2ext(mime_content_type($this->tempName));
        foreach ($this->extensions as $key => $value) {
            if ($value === $ext) {
                return true;
            }
        }

        if (!$this->extensions) {
            return true;
        }

        $this->error[] = [
            'code' => '3',
            'result' => false,
            'message' => 'The uploaded file extensions in the specified extensions list could not be found'
        ];
        return false;
    }

    /**
     * Generate name of uploaded file for store in selected directory
     * @return string
     */
    protected function generateName()
    {
        if ($this->name === 'auto') {
            $name = Methods::unique();
        } elseif ($this->name === 'self') {
            $name = $this->fileName;
            $name = str_ireplace('.' . $this->fileExtension, '', $name);
        } else {
            $name = $this->name;
        }
        return $name;
    }

    /**
     * Generate directory path for upload file based on settings
     * @return string
     */
    protected function generateDirectory()
    {
        $dir = APP_PATH . 'public' . DS . 'files' . DS . $this->uploadPath;

        if (!file_exists($dir)) {
            @mkdir($dir, 755);
        }

        if ($this->classification) {
            $count = Methods::fileCount($dir);
            if ($count == 0) {
                $dir = $dir . DS . '1';
                @mkdir($dir, 755);
            } else {
                $count++;
                if(is_dir($dir . DS . ($count-1))){
                    if (Methods::fileCount($dir . DS . ($count-1)) > 999) {
                        $dir = $dir . DS . ($count + 1);
                        @mkdir($dir, 755);
                    } else {
                        $dir = $dir . DS . ($count-1);
                    }
                }else{
                    @mkdir($dir . DS . $count, 755);
                    $dir = $dir . DS . $count;;
                }
            }
            return $dir;
        }
        return $dir;
    }

    /**
     * Generate and define extension for uploaded file
     * Width this method all options consider and settings calculate for prepare extension
     */
    protected function generateExtension()
    {
        if (!$this->extension) {
            return $this->fileExtension;
        }
        return $this->extension;
    }

    /**
     * Move uploaded file from temp and store to final path
     */
    protected function storeFile()
    {
        $name = $this->generateName();

        $directory = $this->generateDirectory();
        $extension = $this->generateExtension();
        $uploadPath = $directory . DS . $name . '.' . $extension;
        $this->finalFile[0] = $uploadPath;

        $upload = move_uploaded_file($this->tempName, $uploadPath);

        if ($upload) {
            return true;
        }

        $this->error[] = [
            'code' => '4',
            'result' => false,
            'message' => 'Upload was aborted! try again later.'
        ];
        return false;
    }

    /**
     * Generate link of uploaded files as url address
     */
    public function generateLink()
    {
        $links = [];
        foreach ($this->finalFile as $key => $value) {
            $up = str_ireplace($dir = APP_PATH . 'public', '', $value);
            $up = str_ireplace([DS, '/\\', '//'], ['/', '/', '/'], $up);
            $links[] = $up;
        }
        return $links;
    }

    /**
     * return image class for upload image file for upload and manipulate
     * @return Image
     */
    public function image()
    {
        return (new Image());
    }
}
