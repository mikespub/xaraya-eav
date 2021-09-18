<?php
/**
 * EAV Module
 *
 * @package modules
 * @subpackage eav
 * @category Third Party Xaraya Module
 * @version 1.0.0
 * @copyright (C) 2013 Netspan AG
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @author Marc Lutolf <mfl@netspan.ch>
 */

sys::import('modules.dynamicdata.class.objects.base');

class Attribute extends DataObject
{
    private $valuefields = ['tinyint', 'integer', 'decimal', 'string', 'text'];

    public function __construct(DataObjectDescriptor $descriptor)
    {
        parent::__construct($descriptor);
        $this->adjustStates();
    }

    public function getItem(array $args = [])
    {
        $itemid = parent::getItem($args);
    }

    public function checkInput(array $args = [], $suppress=0, $priority='dd')
    {
        $this->properties['property_id']->checkInput();
        echo $this->properties['property_id']->value;
        foreach ($this->properties as $key => $value) {
            echo $key . $value->getDisplayStatus() . "<br/>";
        }
        $this->adjustStates($this->properties['property_id']->value);
        foreach ($this->properties as $key => $value) {
            echo $key . $value->getDisplayStatus() . "<br/>";
        }
        return parent::checkInput();
    }

    public function createItem(array $args = [])
    {
        $this->adjustStates();
        return parent::createItem($args);
    }

    public function updateItem(array $args = [])
    {
        $this->adjustStates();
        return parent::updateItem($args);
    }

    public function deleteItem(array $args = [])
    {
        $this->adjustStates();
        return parent::deleteItem($args);
    }

    private function adjustStates($propertytype=0)
    {
        if (empty($propertytype)) {
            $propertytype = $this->properties['property_id']->value;
        }
        $property = DataPropertyMaster::getProperty(['type' => $propertytype]);
        foreach ($this->valuefields as $field) {
            if ($property->basetype == $field) {
                continue;
            }
            $this->properties['default_' . $field]->setDisplayStatus(DataPropertyMaster::DD_DISPLAYSTATE_DISABLED);
        }
    }
}
