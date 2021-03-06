<?php


use datagutten\dreambox\web\common;
use datagutten\dreambox\web\exceptions\DreamboxException;
use datagutten\dreambox\web\exceptions\DreamboxHTTPException;
use PHPUnit\Framework\TestCase;

class commonTest extends TestCase
{
    public function testEmptyAddress()
    {
        $this->expectException(InvalidArgumentException::class);
        new common('');
    }

    public function testInvalidAddress()
    {
        $this->expectException(DreamboxHTTPException::class);
        new common('httpbin.org/status/404');
    }

    public function testInvalidResponse()
    {
        $this->expectException(DreamboxException::class);
        $this->expectExceptionMessage('Dreambox not found at');
        new common('httpbin.org/anything/not_dreambox');
    }

    public function testLoadChannelListInvalid()
    {
        $this->expectException(FileNotFoundException::class);
        $common = new common('127.0.0.1');
        $common->load_channel_list('bad');
    }

    public function testLoadChannelListEmpty()
    {
        $this->expectException(DreamboxException::class);
        $this->expectExceptionMessage('Channel list empty');
        $common = new common('127.0.0.1');
        $fp = tmpfile();
        fwrite($fp, json_encode([]));
        $path = stream_get_meta_data($fp)['uri'];
        $common->load_channel_list($path);
    }
}
