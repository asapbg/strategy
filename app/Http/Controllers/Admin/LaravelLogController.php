<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaravelLogController extends Controller
{
    public function index(Request $request, $log = 'laravel')
    {
        $log .= '.log';

        if (!Storage::disk('logs')->exists($log)) {
            abort(404);
        }

        $file_path = Storage::disk('logs')->path($log);
        $current_page = $request->get('page', 1);
        $per_page = 10;
        $start = ($current_page * $per_page) - $per_page;

        $laravel_errors = [];
        $file_size = filesize($file_path);
        $handle = fopen($file_path, "r");

        /**
         * If the file size is bigger than 1000000 bytes
         * get the last 1000000 bytes before the end of the file
         */
        if ($file_size > 1000000) {
            fseek($handle, -1000000, SEEK_END);
        }
        //fseek($handle, -1000000, SEEK_END);
        if ($handle) {
            $key = 1;
            $errors_count = 0;
            while (($line = fgets($handle)) !== false) {
                if (str_contains($line, '.ERROR:') && substr($line, 0, 1) == "[") {
                    $errors_count++;
                    $first_line_key = $key;
                    $date = substr($line, 1, 19);
                    $laravel_errors[$key]['date'] = $date;
                    $offset = strpos($line, '.ERROR:')+7;
                    $laravel_errors[$key]['error'] = substr($line, $offset);
                } else if (str_contains($line, '[previous exception]') && isset($first_line_key)) {
                    $laravel_errors[$first_line_key]['exception'] = $line;
                    continue;
                } else if (str_contains($line, '.INFO:') && substr($line, 0, 1) == "[") {
                    $errors_count++;
                    $date = substr($line, 1, 19);
                    $laravel_errors[$key]['date'] = $date;
                    $offset = strpos($line, '.INFO:')+7;
                    $laravel_errors[$key]['error'] = substr($line, $offset);
                }
                $key++;
            }
            fclose($handle);
        }
        $pages = ceil($errors_count / $per_page);

        /**
         * Sort the array by key in reverse order
         */
        krsort($laravel_errors);

        return $this->view('admin.laravel-logs.index',
            compact('laravel_errors', 'pages', 'current_page', 'per_page', 'errors_count', 'start')
        );
    }
}
