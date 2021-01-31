<?php

/**
 * Basic config of upload file
 * as well as manipulate and manipulate a file uploaded to the server
 * Created by PhpStorm.

 * Date: 05/7/2020
 * Time: 11:49 AM
 * Neotis framework
 */


namespace Neotis\Core\Upload\Config;


trait Basic
{
    /**
     * Define filed name of uploaded file and defined by user
     * With this item we can detect the user intend to upload which of the file to server
     * You can define a string
     * This string equal to html input form name: "<input name="field">"
     * @var string
     */
    public $field = [];

    /**
     * All extensions in this array will have permission to upload to the server
     * You must specify the extensions like this [png,jpg,mp4,...]
     * @var array
     */
    public $extensions = [];

    /**
     * Define final path of file after upload on the server
     * With this property you can define relative path start from "public/files/" directory for store the uploaded file
     * A string like this: "directory/"
     * @var string
     */
    public $uploadPath = '';

    /**
     * Define name of uploaded file in selected directory
     * You can use a string for define file name or allow to this class for define random name
     * Example string: auto|self|string name
     * @var string
     */
    public $name = 'self';

    /**
     * With this property you can allow to script for put every 1000 file in single directory
     * So if you want to make this process define true or false for cancel process
     * Example value: true|false
     * @var bool
     */
    public $classification = true;

    /**
     * Define maximum size of file for upload
     * This property make limit for upload file base on file size
     * Just define an integer as a kilobyte unit for size of file
     * If define value equal to "0", This item will be infinite.
     * @var int
     */
    public $maxSize = 1000;//Kilobyte

    /**
     * Define extension for uploaded file and store it
     * false value for generate auto extension from mimetype and string for define extension
     * @var bool
     */
    public $extension = false;

    /**
     * Store final uploaded file path
     * After the file upload process is complete, the uploaded file path will be saved in this variable
     * Obviously, the variable will have a blank array until the process is completed.
     * @var array
     */
    public $finalFile = [];

    /**
     * Store name of uploaded file from "$_FILES"
     * @var string
     */
    protected $fileName = '';

    /**
     * Store file size of uploaded file from "$_FILES"
     * @var string
     */
    protected $fileSize = '';

    /**
     * Store type of file from uploaded file with "$_FILES"
     * @var string
     */
    protected $fileType = '';

    /**
     * Store file extension from uploaded file
     * This value prepare from uploaded file "$_FILES"
     * @var string
     */
    protected $fileExtension = '';

    /**
     * temp name and path of uploaded file
     * with this property you can store temp name for process before move to final store path
     * @var string
     */
    protected $tempName = '';

    /**
     * This item store error information as an array for display to user and developer
     * For access to this information please invoke "errorInfo" method
     * @var array
     */
    protected $error = [];
}
