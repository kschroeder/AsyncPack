<?php

namespace Eschrade\AsyncPack;

use FuseSource\Stomp\Exception\StompException;

class Stomp extends \FuseSource\Stomp\Stomp
{
    /**
     * Check if there is a frame to read
     *
     * @return boolean
     */
    public function hasFrameToRead()
    {
        $read = array($this->_socket);
        $write = null;
        $except = null;

        $has_frame_to_read = @stream_select($read, $write, $except, $this->_read_timeout_seconds, $this->_read_timeout_milliseconds);

        // This is commented out because it always returns false on connect with ActiveMQ.
//        if ($has_frame_to_read !== false)
        $has_frame_to_read = count($read);


        if ($has_frame_to_read === false) {
            throw new StompException('Check failed to determine if the socket is readable');
        } else if ($has_frame_to_read > 0) {
            return true;
        } else {
            return false;
        }
    }

}