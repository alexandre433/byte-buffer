<?php

namespace TrafficCophp\ByteBuffer;

use SplFixedArray;
use TrafficCophp\ByteBuffer\AbstractBuffer;
use TrafficCophp\ByteBuffer\FormatPackEnum;

class Buffer extends AbstractBuffer
{
    protected SplFixedArray $buffer;

    public function __construct($argument)
    {
        match (true) {
            is_string($argument) => $this->initializeStructs(strlen($argument), $argument),
            is_int($argument) => $this->initializeStructs($argument, pack(FormatPackEnum::x->value . "$argument")),
            default => throw new \InvalidArgumentException('Constructor argument must be an binary string or integer')
        };
    }

    public function __toString(): string
    {
        $buf = '';
        foreach ($this->buffer as $bytes) {
            $buf .= $bytes;
        }
        return $buf;
    }

    protected function initializeStructs(string $length, string $content): void
    {
        $this->buffer = new SplFixedArray($length);
        for ($i = 0; $i < $length; $i++) {
            $this->buffer[$i] = $content[$i];
        }
    }

    protected function insert(FormatPackEnum|string $format, $value, int $offset, ?int $length): void
    {
        $bytes = pack($format?->value ?? $format, $value);

        if (null === $length) {
            $length = strlen($bytes);
        }

        for ($i = 0; $i < strlen($bytes); $i++) {
            $this->buffer[$offset++] = $bytes[$i];
        }
    }

    protected function extract(FormatPackEnum|string $format, int $offset, int $length)
    {
        $encoded = '';
        for ($i = 0; $i < $length; $i++) {
            $encoded .= $this->buffer->offsetGet($offset + $i);
        }

        if ($format == FormatPackEnum::N && PHP_INT_SIZE <= 4) {
            [, $h, $l] = unpack('n*', $encoded);
            $result = $l + $h * 0x010000;
        } elseif ($format == FormatPackEnum::V && PHP_INT_SIZE <= 4) {
            [, $h, $l] = unpack('v*', $encoded);
            $result = $h + $l * 0x010000;
        } else {
            [, $result] = unpack($format?->value ?? $format, $encoded);
        }

        return $result;
    }

    protected function checkForOverSize($excpectedMax, string|int $actual): void
    {
        if ($actual > $excpectedMax) {
            throw new \InvalidArgumentException(sprintf('%d exceeded limit of %d', $actual, $excpectedMax));
        }
    }



    public function length(): int
    {
        return $this->buffer->getSize();
    }

    public function write($value, int $offset = 0): void
    {
        $length = strlen($value);
        $this->insert('a' . $length, $value, $offset, $length);
    }

    public function writeInt8($value, int $offset = 0): void
    {
        $format = FormatPackEnum::C;
        $this->checkForOverSize(0xff, $value);
        $this->insert($format, $value, $offset, $format->getLength());
    }

    public function writeInt16BE($value, int $offset = 0): void
    {
        $format = FormatPackEnum::n;
        $this->checkForOverSize(0xffff, $value);
        $this->insert($format, $value, $offset, $format->getLength());
    }

    public function writeInt16LE($value, int $offset = 0): void
    {
        $format = FormatPackEnum::v;
        $this->checkForOverSize(0xffff, $value);
        $this->insert($format, $value, $offset, $format->getLength());
    }

    public function writeInt32BE($value, int $offset = 0): void
    {
        $format = FormatPackEnum::N;
        $this->checkForOverSize(0xffffffff, $value);
        $this->insert($format, $value, $offset, $format->getLength());
    }

    public function writeInt32LE($value, int $offset = 0): void
    {
        $format = FormatPackEnum::V;
        $this->checkForOverSize(0xffffffff, $value);
        $this->insert($format, $value, $offset, $format->getLength());
    }

    public function read(int $offset, int $length)
    {
        return $this->extract('a' . $length, $offset, $length);
    }

    public function readInt8(int $offset)
    {
        $format = FormatPackEnum::C;
        return $this->extract($format, $offset, $format->getLength());
    }

    public function readInt16BE(int $offset)
    {
        $format = FormatPackEnum::n;
        return $this->extract($format, $offset, $format->getLength());
    }

    public function readInt16LE(int $offset)
    {
        $format = FormatPackEnum::v;
        return $this->extract($format, $offset, $format->getLength());
    }

    public function readInt32BE(int $offset)
    {
        $format = FormatPackEnum::N;
        return $this->extract($format, $offset, $format->getLength());
    }

    public function readInt32LE(int $offset)
    {
        $format = FormatPackEnum::V;
        return $this->extract($format, $offset, $format->getLength());
    }
}
