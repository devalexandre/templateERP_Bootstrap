<?php
/**
 * Required field validation
 *
 * @version    1.0
 * @package    validator
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2014 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class TRequiredValidator extends TFieldValidator
{
    /**
     * Validate a given value
     * @param $label Identifies the value to be validated in case of exception
     * @param $value Value to be validated
     * @param $parameters aditional parameters for validation
     */
    public function validate($label, $value, $parameters = NULL)
    {
        if (trim($value)=='' OR (is_array($value) AND count($value)==1 AND empty($value[0])))
        {
            throw new Exception(TAdiantiCoreTranslator::translate('The field ^1 is required', $label));
        }
    }
}
?>