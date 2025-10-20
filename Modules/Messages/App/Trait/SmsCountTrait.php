<?php

namespace Modules\Messages\App\Trait;

use Illuminate\Support\Facades\Log;

trait SmsCountTrait
{
  public function countSms($text): object|false
  {

    //Log::channel('countsms')->info("SmsCountTrait: text before \\r replace: ". mb_strlen($text). ": $text");

    $text = str_replace("\r\n", "\n", $text);

    //Log::channel('countsms')->info("SmsCountTrait: text after \\r replace: ". mb_strlen($text). ": $text");

    $textlen = mb_strlen(str_replace("\n", "", $text), 'UTF-8'); // Get the length of the text in UTF-8 encoding
    if ($textlen == 0) {
      return false;
    } //I can see most mobile devices will not allow you to send empty sms, with this check we make sure we don't allow empty SMS

    if ($this->isUnicode($text)) {
      $SingleMax = 70;
      $ConcatMax = 67;
      $smsType = 'unicode';
    } else {
      if ($this->isGsm7bit($text)) { //7-bit
        $SingleMax = 140;
        $ConcatMax = 134;
        $smsType = 'gsm_extend';
      } else {
        //UCS-2 Encoding (16-bit)
        $SingleMax = 160;
        $ConcatMax = 153;
        $smsType = 'plain';
      }
    }


    if ($textlen <= $SingleMax) {
      $TotalSegment = 1;
    } else {
      $TotalSegment = ceil($textlen / $ConcatMax);
    }

    $parts = [];
    if ($textlen <= $SingleMax) {
      $parts[] = $text;
    } else {
      for ($i = 0; $i < $textlen; $i += $ConcatMax) {
        $parts[] = mb_substr($text, $i, $ConcatMax);
      }
    }

    $data = [
      'smsType' => $smsType,
      'count' => $TotalSegment,
      'parts' => $parts
    ];

    return (object)$data;
  }

  public function isUnicode($text): bool
  {
    if (strlen($text) != strlen(utf8_decode($text))) {
      return true;
    }

    return false;
  }

  public function isGsm7bit($text): bool
  {
    $gsm7bitChars = "~^{}[]\|€";
    $textlen = mb_strlen($text);
    for ($i = 0; $i < $textlen; $i++) {
      if (((strpos($gsm7bitChars, $text[$i]) !== false && $text[$i] != '\\')) || $text[$i] == '\\') {
        return true;
      } //strpos not able to detect \ in string
    }
    return false;
  }

  public function getPriority($numberOfRecipients): int
  {
    if (count($numberOfRecipients) > 800) {
      return 4;
    } elseif (count($numberOfRecipients) > 600) {
      return 5;
    } elseif (count($numberOfRecipients) > 400) {
      return 6;
    } elseif (count($numberOfRecipients) > 200) {
      return 7;
    } elseif (count($numberOfRecipients) > 100) {
      return 8;
    } else {
      return 9;
    }
  }

  public function generateUniqNumber(): int
  {
    return mt_rand(10000000, 99999999);
  }

  public function getPrefix($destmn): string
  {
    try {
      $pattern = '/^\+?88/';
      $mobileNumber = preg_replace($pattern, '', $destmn);

      $prefixes = array(
        '017' => '17',
        '018' => '18',
        '019' => '19',
        '015' => '15',
        '016' => '18',
        '013' => '17',
        '014' => '19'
      );
      $prefix = substr($mobileNumber, 0, 3);
      if (array_key_exists($prefix, $prefixes)) {
        return $prefixes[$prefix];
      } else {
        return '00';
      }
    } catch (\Exception $exception) {
      return '00';
    }
  }

  public function messagePriority($text)
  {
    $keywords = ['OTP', 'otp', 'CODE', 'code', 'টোকেন', 'ভেরিফাই', 'কোড', 'নাম্বার'];

    foreach ($keywords as $word) {
      if (mb_stripos($text, $word) !== false) {
        return 1;
      }
    }
    return 0;
  }
}
