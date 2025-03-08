<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\VarDumper\VarDumper;
use TrafficCophp\ByteBuffer\Buffer;

class BufferTest extends TestCase
{
    public function testTrailingEmptyByte()
    {
        $buffer = new Buffer(5);
        $buffer->writeInt32LE(0xfeedface);
        $this->assertSame(pack('Vx', 0xfeedface), $buffer->__toString());
    }

    public function testSurroundedEmptyByte()
    {
        $buffer = new Buffer(9);
        $buffer->writeInt32BE(0xfeedface);
        $buffer->writeInt32BE(0xcafebabe, 5);
        $this->assertSame(pack('NxN', 0xfeedface, 0xcafebabe), $buffer->__toString());
    }

    public function testTooSmallBuffer()
    {
        $buffer = new Buffer(4);
        $buffer->writeInt32BE(0xfeedface, 0);
        $this->expectException('RuntimeException');
        $buffer->writeInt32LE(0xfeedface, 4);
    }

    public function testTwo4ByteIntegers()
    {
        $buffer = new Buffer(8);
        $buffer->writeInt32BE(0xfeedface);
        $buffer->writeInt32LE(0xfeedface, 4);
        $this->assertSame(pack('NV', 0xfeedface, 0xfeedface), $buffer->__toString());
    }

    public function testWritingString()
    {
        $buffer = new Buffer(10);
        $buffer->writeInt32BE(0xcafebabe);
        $buffer->write('please', 4);

        $this->assertSame(pack('Na6', 0xcafebabe, 'please'), $buffer->__toString());
    }

    public function testTooLongIntegers()
    {
        $buffer = new Buffer(12);
        $this->expectException('InvalidArgumentException');
        $buffer->writeInt32BE(0xfeedfacefeed, 0);
    }

    public function testLength()
    {
        $buffer = new Buffer(8);
        $this->assertEquals(8, $buffer->length());
    }

    public function testWriteInt8()
    {
        $buffer = new Buffer(1);
        $buffer->writeInt8(0xfe, 0);
        $this->assertSame(pack('C', 0xfe), $buffer->__toString());
    }

    public function testWriteInt16BE()
    {
        $buffer = new Buffer(2);
        $buffer->writeInt16BE(0xbabe, 0);
        $this->assertSame(pack('n', 0xbabe), $buffer->__toString());
    }

    public function testWriteInt16LE()
    {
        $buffer = new Buffer(2);
        $buffer->writeInt16LE(0xabeb, 0);
        $this->assertSame(pack('v', 0xabeb), $buffer->__toString());
    }

    public function testWriteInt32BE()
    {
        $buffer = new Buffer(4);
        $buffer->writeInt32BE(0xfeedface, 0);
        $this->assertSame(pack('N', 0xfeedface), $buffer->__toString());
    }

    public function testWriteInt32LE()
    {
        $buffer = new Buffer(4);
        $buffer->writeInt32LE(0xfeedface, 0);
        $this->assertSame(pack('V', 0xfeedface), $buffer->__toString());
    }

    public function testReaderBufferInitializeLenght()
    {
        $buffer = new Buffer(pack('V', 0xfeedface));
        $this->assertEquals(4, $buffer->length());
    }

    public function testReadInt8()
    {
        $buffer = new Buffer(pack('C', 0xfe));
        $this->assertSame(0xfe, $buffer->readInt8(0));
    }

    public function testReadInt16BE()
    {
        $buffer = new Buffer(pack('n', 0xbabe));
        $this->assertSame(0xbabe, $buffer->readInt16BE(0));
    }

    public function testReadInt16LE()
    {
        $buffer = new Buffer(pack('v', 0xabeb));
        $this->assertSame(0xabeb, $buffer->readInt16LE(0));
    }

    public function testReadInt32BE()
    {
        $buffer = new Buffer(pack('N', 0xfeedface));
        $this->assertSame(0xfeedface, $buffer->readInt32BE(0));
    }

    public function testReadInt32LE()
    {
        $buffer = new Buffer(pack('V', 0xfeedface));
        $this->assertSame(0xfeedface, $buffer->readInt32LE(0));
    }

    public function testRead()
    {
        $buffer = new Buffer(pack('a7', 'message'));
        $this->assertSame('message', $buffer->read(0, 7));
    }

    public function testComplexRead()
    {
        $buffer = new Buffer(pack('Na7', 0xfeedface, 'message'));
        $this->assertSame(0xfeedface, $buffer->readInt32BE(0));
        $this->assertSame('message', $buffer->read(4, 7));
    }

    public function testWritingAndReadingOnTheSameBuffer()
    {
        $buffer = new Buffer(10);
        $int32be = 0xfeedface;
        $string = 'hello!';
        $buffer->writeInt32BE($int32be, 0);
        $buffer->write($string, 4);
        $this->assertSame($string, $buffer->read(4, 6));
        $this->assertSame($int32be, $buffer->readInt32BE(0));
    }

    public function testInvalidConstructorWithArray()
    {
        $this->expectException('InvalidArgumentException');
        @new Buffer(['asdf']);
    }

    public function testInvalidConstructorWithFloat()
    {
		$this->expectException('InvalidArgumentException');
        @new Buffer((float)324.23);
    }
}
