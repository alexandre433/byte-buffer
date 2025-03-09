<?php

namespace TrafficCophp\ByteBuffer;

interface WriteableBuffer
{
    public function write($value, ?int $offset = null): self;

    /**
     * Write an int8 to the buffer
     *
     * @param mixed $value
     * @param null|int $offset The offset to write the int8, if not provided the length of the buffer will be used
     * @return self
     */
    public function writeInt8($value, ?int $offset = null): self;

    /**
     * Write an int16 to the buffer in big-endian format
     *
     * @param mixed $value
     * @param null|int $offset The offset to write the int8, if not provided the length of the buffer will be used
     * @return self
     */
    public function writeInt16BE($value, ?int $offset = null): self;

    /**
     * Write an int16 to the buffer in little-endian format
     *
     * @param mixed $value
     * @param null|int $offset The offset to write the int8, if not provided the length of the buffer will be used
     * @return self
     */
    public function writeInt16LE($value, ?int $offset = null): self;

    /**
     * Write an int32 to the buffer in big-endian format
     *
     * @param mixed $value
     * @param null|int $offset The offset to write the int8, if not provided the length of the buffer will be used
     * @return self
     */
    public function writeInt32BE($value, ?int $offset = null): self;

    /**
     * Write an int32 to the buffer in little-endian format
     *
     * @param mixed $value
     * @param null|int $offset The offset to write the int8, if not provided the length of the buffer will be used
     * @return self
     */
    public function writeInt32LE($value, ?int $offset = null): self;

}
