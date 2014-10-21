<?php
/**
 * NumericDataType class
 * @author Igor Manturov, Jr. <igor.manturov.jr@gmail.com>
 * @link https://github.com/iAchilles/eavactiverecord/
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */
 
/**
 * NumericDataType class
 *
 * @since 1.0.3
 */
class NumericDataType extends EavValue
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function tableName()
    {
        return '{{eav_attribute_numeric}}';
    }
} 