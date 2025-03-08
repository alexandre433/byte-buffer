<?php

namespace TrafficCophp\ByteBuffer;

interface WriteableBuffer
{
    public function write($value, int $offset = 0): void;

    public function writeInt8($value, int $offset = 0): void;

    public function writeInt16BE($value, int $offset = 0): void;

    public function writeInt16LE($value, int $offset = 0): void;

    public function writeInt32BE($value, int $offset = 0): void;

    public function writeInt32LE($value, int $offset = 0): void;

}
