<?php

namespace Filters;

use Phalcon\Assets\FilterInterface;

/**
 * Filters JS content using UglifyJS
 *
 * @param string $contents
 * @return string
 */
class UglifyJS implements FilterInterface
{
    protected $options;

    /**
     * UglifyJS constructor
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @param string $contents
     *
     * @return string
     */
    public function filter($contents)
    {
        // Write the string contents into a temporal file
        file_put_contents('/tmp/temp-minify.js', $contents);

        system(
            'uglifyjs --compress --mangle -o aurora.min.js -- /tmp/temp-minify.js'
        );

        // Return the contents of file
        return file_get_contents('public/js/aurora.min.js');
    }
}
