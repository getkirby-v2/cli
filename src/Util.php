<?php

namespace Kirby\Cli;

use ZipArchive;
use RuntimeException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Util {

  public static function unzip($file, $to) {

    if(!class_exists('ZipArchive')) {
      throw new RuntimeException('The ZipArchive class is not available');
    }

    $zip = new ZipArchive;

    if($zip->open($file) === true) {
      $zip->extractTo($to);
      $zip->close();
      return true;
    } else {
      return false;
    }

  }

  public static function download($url, $progress = null) {

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    if(is_callable($progress)) {
      curl_setopt($curl, CURLOPT_BUFFERSIZE, 128);
      curl_setopt($curl, CURLOPT_NOPROGRESS, false);
      curl_setopt($curl, CURLOPT_PROGRESSFUNCTION, $progress);      
    }

    $content = curl_exec($curl);
    $error   = curl_error($curl);

    curl_close($curl);

    if(!empty($error)) {
      throw new RuntimeException('Download failed: ' . $url);
    }

    return $content;

  }

  public static function remove($item) {

    // delete a folder and all its contents
    if(is_dir($item)) {

      $iterator = new RecursiveDirectoryIterator($item, RecursiveDirectoryIterator::SKIP_DOTS);
      $files    = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST);
      
      foreach($files as $file) {
        if($file->isDir()) {
          rmdir($file->getRealPath());
        } else {
          unlink($file->getRealPath());
        }
      }

      rmdir($item);

    // delete a file
    } else if(is_file($item)) {
      return unlink($item);
    }

  }

}