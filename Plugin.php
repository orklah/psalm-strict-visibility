<?php
namespace Orklah\PsalmStrictVisibility;

use Orklah\PsalmStrictVisibility\Hooks\StrictVisibility;
use SimpleXMLElement;
use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;

class Plugin implements PluginEntryPointInterface
{
    /** @return void */
    public function __invoke(RegistrationInterface $psalm, ?SimpleXMLElement $config = null)
    {
        if(class_exists(StrictVisibility::class)){
            $psalm->registerHooksFromClass(StrictVisibility::class);
        }
    }
}
