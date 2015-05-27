<?php
namespace AlejandroHerr\Silex;

use AlejandroHerr\Silex\Application\FlashBagTrait;
use Silex\Application as SilexApplication;

class Application extends SilexApplication
{
    use FlashBagTrait;
    use SilexApplication\TwigTrait;
    use SilexApplication\SecurityTrait;
    use SilexApplication\FormTrait;
    use SilexApplication\UrlGeneratorTrait;
    use SilexApplication\SwiftmailerTrait;
    use SilexApplication\MonologTrait;
    use SilexApplication\TranslationTrait;
}
