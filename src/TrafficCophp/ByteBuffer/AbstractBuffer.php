<?php

namespace TrafficCophp\ByteBuffer;

use TrafficCophp\ByteBuffer\ReadableBuffer;
use TrafficCophp\ByteBuffer\WriteableBuffer;

abstract class AbstractBuffer implements ReadableBuffer, WriteableBuffer
{
    abstract public function __construct($argument);

    abstract public function __toString(): string;

    abstract public function length(): int;
}
