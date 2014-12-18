<?php
namespace AlejandroHerr\Exception\Arr;

/**
 * Interface for exception that keep exception messages as array to.
 * Useful to translate to JSON/XML response.
 */
interface ExceptionInterface
{
    /**
     * Returns exception message as array
     */
    public function getArrayMessage();
}
