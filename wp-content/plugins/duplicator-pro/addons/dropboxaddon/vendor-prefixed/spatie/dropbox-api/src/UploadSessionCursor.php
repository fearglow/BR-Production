<?php

namespace VendorDuplicator\Dropbox\Spatie\Dropbox;

class UploadSessionCursor
{
    /**
     * The upload session ID (returned by upload_session/start).
     *
     * @var string
     */
    public $session_id;
    /**
     * The amount of data that has been uploaded so far. We use this to make sure upload data isn't lost or duplicated in the event of a network error.
     *
     * @var int
     */
    public $offset;
    public function __construct($session_id, $offset = 0)
    {
        $this->session_id = $session_id;
        $this->offset = $offset;
    }
}
