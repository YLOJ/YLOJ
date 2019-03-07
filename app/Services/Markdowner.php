<?php

namespace App\Services;

use Michelf\MarkdownExtra;
use Michelf\SmartyPants;

class Markdowner
{
    public function toHTML($text) 
    {
        $text = $this->preTransformText($text);
        $text = MarkdownExtra::defaultTransform($text);
        $text = SmartyPants::defaultTransform($text);
        $text = $this->postTransformText($text);
        return $text;
    }

    protected function preTransformText($text) 
    {
        return $text;
    }

    protected function postTransformText($text)
    {
        $new_text = "";
        $len = strlen($text);

        for ($i = 0; $i < $len; $i ++) {
            $new_text .= $text[$i] == "\n" ? "<br>" : $text[$i];
        }
        
        return $new_text;
    }
}
