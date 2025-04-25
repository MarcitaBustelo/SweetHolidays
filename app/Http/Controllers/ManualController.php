<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use League\CommonMark\CommonMarkConverter;

class ManualController extends Controller
{
    public function show()
    {
        $markdownContent = File::get(base_path('manual.md'));
        $converter = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
        
        $htmlContent = $converter->convert($markdownContent);

        return view('manual.manual', compact('htmlContent'));
    }
}