<?php

use Imagick;
ini_set('memory_limit', -1);

class ImageWithImagik
{
    // accept image from terminal and save it to a file and also resize using IMagick
    public function handle(array $request)
    {

        $data = $this->parse_argv($request);

        $destination = __DIR__ . '/uploads/';

        // get image from test-image folder
        $image = __DIR__ . '/test-image/testphoto.jpg';

        $imageName = basename($image);

        // move image to uploads folder
        $uploaded = copy($image, $destination . 'original_' . $imageName);

        if ($uploaded) {
            return print_r($this->resizeImage($image, $data, $destination, $imageName));
        } else {
            echo 'An error occurred!';
        }

        ini_restore('memory_limit');
    }

    private function parse_argv(array $argv): array
    {
        $request = [];
        foreach ($argv as $i => $a) {
            if (!$i) {
                continue;
            }
            if (preg_match('/^-*(.+?)=(.+)$/', $a, $matches)) {
                $request[$matches[1]] = $matches[2];
            } else {
                $request[$i] = $a;
            }
        }

        return array_values($request);
    }

    private function resizeImage($image, $data, $destination, $imageName)
    {
        $result = [];
        $result['oldSize'] = filesize($image) / 1024 . 'KB';
        $result['oldHeight'] = getimagesize($image)[1];
        $result['oldWidth'] = getimagesize($image)[0];

        $image = new Imagick($image);
        $image->resizeImage($data[0], $data[1], \Imagick::FILTER_LANCZOS, 0);
        $image->writeImage($destination . 'compress_' . $imageName);

        if ($image) {
            $result['newSize'] = filesize($destination . 'compress_' . $imageName) / 1024 . 'KB';
            $result['newHeight'] = getimagesize($destination . 'compress_' . $imageName)[1];
            $result['newWidth'] = getimagesize($destination . 'compress_' . $imageName)[0];
                return $result;
                exit;
            } else {
                return 'An error occured!';
                exit;
        }
    }
}

$compress = new ImageWithImagik();
$compress->handle($argv);