<?php
namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Psys\UtilsBundle\Service\FileUploader;
use Psys\UtilsBundle\Service\Misc;
use Psys\UtilsBundle\Service\FormErrors;


return function(ContainerConfigurator $container): void 
{
    $container->services()

        ->set('su.misc', Misc::class)
            ->args([
                service('validator'),
            ])
            ->alias(Misc::class, 'su.misc')

        ->set('su.file_uploader', FileUploader::class)
            ->args([
                service('slugger'),
                service('filesystem'),
            ])
            ->alias(FileUploader::class, 'su.file_uploader')
        
        ->set('su.form_errors', FormErrors::class)
            ->alias(FormErrors::class, 'su.form_errors')
    ;
};