<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->exclude('app/cache')
    ->in(__DIR__)

;

return Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FixerInterface::SYMFONY_LEVEL)
    ->fixers(array(
        '-unalign_double_arrow',
        '-unalign_equals',
	'-phpdoc_to_comment',
        'align_double_arrow',
        'newline_after_open_tag',
        'unused_use',
        'ordered_use',
        'linefeed',
        'concat_with_spaces',
//        'short_array_syntax',
        'phpdoc_order'
    ))
    ->setUsingCache(true)
    ->finder($finder)
;
