<?php

namespace App\Util;

use App\Mapper\Cryptic;

class CrypticService
{

    public function decrypt(Cryptic $cryptic): string
    {
        $decrypted = null;
        $key = str_split($this->strtobin(base64_decode($cryptic->getKey())));
        $text = str_split($this->strtobin(base64_decode($cryptic->getText())));
        foreach ($text as $map => $bit) {
            if ($key[$map] === "1") {
                if ($bit === "1") {
                    $decrypted .= 0;
                } else {
                    $decrypted .= 1;
                }
            } else {
                $decrypted .= $bit;
            }
        }
        return $this->bintostr($decrypted);
    }

    private function strtobin($input): string
    {
        $chars = str_split($input);
        $binary = [];

        foreach ($chars as $char) {
            if ($char === "\x00") {
                continue;
            }
            $binary[] = base_convert(unpack('H*', $char)[1], 16, 2);
        }

        foreach ($binary as $key => $bin) {
            while (strlen($binary[$key]) !== 8) {
                $binary[$key] = '0' . $binary[$key];
            }
        }

        return implode('', $binary);
    }

    private function bintostr($binary)
    {
        $bytes = str_split($binary, 8);

        $return = null;
        foreach($bytes as $byte){
            $return .= pack('H*', dechex(bindec($byte)));
        }

        return $return;
    }
}