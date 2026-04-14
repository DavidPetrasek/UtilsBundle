<?php 
namespace Psys\UtilsBundle\Service;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FormErrors
{    
    public function getArray(Form $baseForm) : array
    {
        $errsArr = [];
        $baseFormName = $baseForm->getName();
        $fileMultiple = [];

        $errs_FormErrorIterator = $baseForm->getErrors(true);
        foreach($errs_FormErrorIterator as $err_it)
        {
            $cause = $err_it->getCause();
            if (is_object($cause) && method_exists($cause, 'getPropertyPath')) 
            {
                $path = $cause->getPropertyPath();
            } 
            else 
            {
                $path = '';
            }

            $path = preg_replace("/^(data.)|(.data)|(\\])|(\\[)|children/", '', $path);
            $path = str_replace('.', '_', $path);
            $field_id = $baseFormName . ($path !== '' ? '_'.$path : '');

            $invalidValue = null;
            if (is_object($cause) && method_exists($cause, 'getInvalidValue')) 
            {
                $invalidValue = $cause->getInvalidValue();
            }
            $pathWithoutTrailingIntegers = preg_replace('/\d+$/', '', $path);
            $fmMessage = ltrim($err_it->getMessage(), '_');

            // Collect errors for file inputs with multiple files
            if ($invalidValue instanceof UploadedFile  &&  $pathWithoutTrailingIntegers !== $path)
            {
                $fileMultiple[$baseFormName.'_'.$pathWithoutTrailingIntegers][] = $fmMessage;
                continue;
            }
            else if (is_array($invalidValue))
            {
                // RepeatedType - error matches the first field's ID
                $invalidValue_firstKey = array_key_first($invalidValue);
                if ($invalidValue_firstKey === 'first')
                {
                    $field_id .= '_'.$invalidValue_firstKey;
                }
            }

            $errsArr[] = 
            [
                'field_id' => $field_id,
                'message' => $fmMessage               
            ];
        }

        // Concatenate and add collected errors of file inputs with multiple files into single field
        foreach($fileMultiple as $fieldID => $fm)
        {
            $errsArr[] = 
            [
                'field_id' => $fieldID,
                'message' => implode(' ',$fm)               
            ];
        }

        return $errsArr;
    }
}