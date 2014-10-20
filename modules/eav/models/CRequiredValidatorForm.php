<?php
/**
 * CRequiredValidatorForm class file
 * @author Igor Manturov, Jr. <igor.manturov.jr@gmail.com>
 * @link https://github.com/iAchilles/eavactiverecord/
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/**
 * CRequiredValidatorForm class
 *
 * @author Igor Manturov, Jr. <igor.manturov.jr@gmail.com>
 */
class CRequiredValidatorForm extends BaseValidatorForm
{
    const ALIAS = 'required';

    public $requiredValue;

    public $strict = false;

    public $trim = true;

    private $attribute;


    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('strict, trim', 'boolean', 'trueValue' => 'true', 'falseValue' => 'false'),
            array('requiredValue', 'default', 'setOnEmpty' => true, 'value' => null),
        ));
    }


    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'requiredValue' => Yii::t('EavModule.eavactiverecord', 'Required value'),
            'strict' => Yii::t('EavModule.eavactiverecord', 'Strict comparison'),
            'trim' => Yii::t('EavModule.eavactiverecord', 'Value trimming'),
        ));
    }


    public function setAttribute(EavAttribute $attribute)
    {
        $this->attribute = $attribute;
    }


    public function getHtml()
    {
        $validators = $this->attribute->getEavValidatorList();
        $validators = isset($validators[self::ALIAS]) ? $validators[self::ALIAS] : array();
        $attributes = array_merge($this->getAttributes(), $validators);
        $attributes = array_merge($attributes, $this->prepareInput($attributes));
        $this->setAttributes($attributes);
        return Yii::app()->getController()->renderPartial(self::ALIAS, array('model' => $this), true);
    }


    public function getOutput()
    {
        if (!$this->validate())
        {
            $this->attribute->setValidatorErrors(self::ALIAS);
        }
        return array_merge($this->getAttributes(), $this->prepareOutput());
    }


    private function prepareInput($attributes)
    {
        $preparedAttributes = array();
        foreach ($attributes as $key => $value)
        {
            if (in_array($key, array('enableClientValidation', 'safe', 'skipOnError', 'strict', 'trim')))
            {
                $preparedAttributes[$key] = CPropertyValue::ensureString($value);
            }

            if ($key === 'except' || $key === 'on')
            {
                if (is_array($value))
                {
                    $preparedAttributes[$key] = implode(', ', $value);
                }
            }
        }

        return $preparedAttributes;
    }


    private function prepareOutput()
    {
        $preparedAttributes = array();
        foreach ($this->getAttributes() as $key => $value)
        {
            if (in_array($key, array('enableClientValidation', 'safe', 'skipOnError', 'strict', 'trim')))
            {
                $preparedAttributes[$key] = CPropertyValue::ensureBoolean($value);
            }

            if ($key === 'except' || $key === 'on')
            {
                if (!is_null($value))
                {
                    $preparedAttributes[$key] = array_map('trim', explode(',', $value));
                }
            }
        }

        return $preparedAttributes;
    }
} 