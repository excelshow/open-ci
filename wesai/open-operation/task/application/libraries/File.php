<?php

class File
{
    const TMP_DIR = '/tmp/';

    public function download($url)
    {
        if (!file_exists(self::TMP_DIR)) {
            return -1;
        }

        $file_content = file_get_contents($url);
        if (empty($file_content)) {
            return -2;
        }

        $file_name = $this->getFileNameFromUrl($url);

        $file_path = self::TMP_DIR . $file_name;

        $is_down_ok = @file_put_contents($file_path, $file_content);

        if (!$is_down_ok) {
            return -3;
        }

        return $file_path;
    }

    private function getFileNameFromUrl($url)
    {
        $parsed_url = parse_url($url);
        $path = $parsed_url['path'];
        $file_suffix = 'png';
        if (!empty($path)) {
            $path = explode('.', $path);
            if (count($path) > 1) {
                $file_suffix = array_pop($path);
            }
        }

        $file_name = md5(microtime());

        return $file_name . '.' . $file_suffix;
    }

}
