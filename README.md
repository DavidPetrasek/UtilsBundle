# UtilsBundle

## Installation

`composer require psys/utils-bundle`

## Usage

### FileUploader
``` php
use Psys\UtilsBundle\Service\FileUploader;
...
FileUploader $fileUploader
...
$uploadedFile = $form->get('my_file')->getData();                        
$savedFile = $fileUploader->saveFile($uploadedFile, '/abs/path/target-dir');

print_r($savedFile);
```
$savedFile:
``` php
[
    'nameFileSystem' => 'dfb93338.pdf',
    'nameDisplay' => 'invoice.pdf',
    'mimeType' => 'application/pdf',
]
```

### FormErrors
``` php
use Psys\UtilsBundle\Service\FormErrors;
...
FormErrors $formErrors
...
$form->handleRequest($request);

if ($form->isSubmitted()) 
{
    ...
    if (!$form->isValid()) 
    {
        $formErrorsRes = $formErrors->getArray($form)
        print_r($formErrorsRes);
    }
}
```
$formErrorsRes:
``` php
[
    [
        'field_id' => 'login_email',
        'message' => 'E-mail not found'               
    ],
    [
        'field_id' => 'login_password',
        'message' => 'The password is wrong'               
    ]
]
```

### Miscellaneous
``` php
use Psys\UtilsBundle\Service\Misc;
...
Misc $utilsMisc
```

Available methods:
- isEmailValid