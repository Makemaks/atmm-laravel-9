<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class StreamFile
{
    private $filePath = "";
    
    function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    private function getFileName($filePath)
    {
        $fileArray = explode('/', $filePath);
        return $fileArray[count($fileArray) - 1];
    }

    private function stream()
    {
        /*************************************************************************
         * Get File Information
         */
        // Assuming these come from some data source in your app
        $s3FileKey = $this->filePath;
        $fileName = $this->getFileName($this->filePath);
        // Create temporary download link and redirect
        $adapter = Storage::disk('s3')->getAdapter();
        $client = $adapter->getClient();
        $client->registerStreamWrapper();
        $object = $client->headObject([
            'Bucket' => $adapter->getBucket(),
            'Key' => /*$adapter->getPathPrefix() . */$s3FileKey,
        ]);
        /*************************************************************************
         * Set headers to allow browser to force a download
         */
        header('Last-Modified: '.$object['LastModified']);
        // header('Etag: '.$object['ETag']); # We are not implementing validation caching here, but we could!
        header('Accept-Ranges: '.$object['AcceptRanges']);
        header('Content-Length: '.$object['ContentLength']);
        header('Content-Type: '.$object['ContentType']);
        header('Content-Disposition: attachment; filename='.$fileName);
        /*************************************************************************
         * Stream file to the browser
         */
        // Open a stream in read-only mode
        if (!($stream = fopen("s3://{$adapter->getBucket()}/{$s3FileKey}", 'r'))) {
            throw new \Exception('Could not open stream for reading file: ['.$s3FileKey.']');
        }
        // Check if the stream has more data to read
        while (!feof($stream)) {
            // Read 1024 bytes from the stream
            echo fread($stream, 1024);
        }
        // Be sure to close the stream resource when you're done with it
        fclose($stream);
    }

    function streamFile()
    {   
        $this->stream();
    }
}