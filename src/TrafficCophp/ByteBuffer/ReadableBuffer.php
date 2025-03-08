<?php

namespace TrafficCophp\ByteBuffer;

interface ReadableBuffer
{
    public function read(int $offset, int $length);

    public function readInt8(int $offset);

    public function readInt16BE(int $offset);

    public function readInt16LE(int $offset);

    public function readInt32BE(int $offset);

    public function readInt32LE(int $offset);

}
