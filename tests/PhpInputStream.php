<?php
namespace Tests;

class PhpInputStream {
    public static string $mock;

    public $context;

    public function stream_open($path, $mode, $options, &$opened_path): bool {
        return true;
    }

    public function stream_read($count): string {
        $ret = substr(self::$mock, 0, $count);
        self::$mock = substr(self::$mock, $count);
        return $ret;
    }

    public function stream_eof(): bool {
        return strlen(self::$mock) === 0;
    }

    public function stream_stat(): array {
        return [];
    }

    public function stream_seek($offset, $whence): bool {
        return false;
    }
}

