<?php

namespace App\Http\Response;

use Illuminate\Http\Response;

/**
 * Response for API calls.
 */
class ApiResponse extends Response
{
    /**
     * Morph the given content into JSON.
     *
     * @param mixed $content Content that will be displayed.
     *
     * @return string
     */
    protected function morphToJson($content)
    {
        if ($content instanceof Jsonable) {
            return $content->toJson();
        }

        $content = $this->morphToArray($content);

        return json_encode($content);
    }

    /**
     * Morph the given content into Array.
     *
     * @param mixed $content Content that will be morphed into array.
     *
     * @return string
     */
    protected function morphToArray($content)
    {
        if ($content instanceof ArrayableInterface || method_exists($content, 'toArray')) {
            return $this->morphToArray($content->toArray());
        }

        if (is_array($content)) {
            foreach ($content as $key => $value) {
                $content[$key] = $this->morphToArray($value);
            }
        }

        if (is_callable([$content, '__toString'])) {
            return (string) $content;
        }

        return $content;
    }
}
