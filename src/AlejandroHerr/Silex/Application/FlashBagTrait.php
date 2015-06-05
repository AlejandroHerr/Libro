<?php

namespace AlejandroHerr\Silex\Application;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

trait FlashBagTrait
{
    /**
     * Adds a flash message for type.
     *
     * @param string $type
     * @param string $message
     *
     * @see FlashBag::add($type, $message)
     */
    public function addFlashBag($type, $message, $context = '')
    {
        $message = sprintf('<strong>%s</strong> %s', $message, $context);
        $this['session']->getFlashBag()->add($type, $message);
    }

    /**
     * Gets all flash messages.
     *
     * @return array
     *
     * @see FlashBag::all()
     */
    public function getFlashBag()
    {
        return $this['session']->getFlashBag()->all();
    }
}
