<?php

namespace Eschrade\AsyncPack;

class Codec
{

    /**
     * Encodes the data;
     *
     * @param $data mixed The data
     * @return string The encoded data
     */

    public function encode($data)
    {
        $data = base64_encode(serialize($data));
        return $data;
    }

    /**
     * Decodes the data.
     *
     * @param $data string The encoded data
     * @return mixed The decoded data;
     */

    public function decode($data)
    {
        $data = unserialize(base64_decode($data));
        return $data;
    }

}