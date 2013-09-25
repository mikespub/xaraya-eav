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
/**
 * Create a new item of the eav object
 *
 */
    sys::import('modules.dynamicdata.class.objects.master');
    
    function eav_admin_new()
    {
        if (!xarSecurityCheck('AddEAV')) return;

        if (!xarVarFetch('name',       'str',    $name,            'eav_attributes', XARVAR_NOT_REQUIRED)) return;
        if (!xarVarFetch('confirm',    'bool',   $data['confirm'], false,     XARVAR_NOT_REQUIRED)) return;

        $data['object'] = DataObjectMaster::getObject(array('name' => $name));
        $data['tplmodule'] = 'eav';
        $data['authid'] = xarSecGenAuthKey('eav');

        if ($data['confirm']) {
        
            // we only retrieve 'preview' from the input here - the rest is handled by checkInput()
            if(!xarVarFetch('preview', 'str', $preview,  NULL, XARVAR_DONT_SET)) {return;}

            // Check for a valid confirmation key
            if(!xarSecConfirmAuthKey()) return;
            
            // Get the data from the form
            $isvalid = $data['object']->checkInput();
            
            if (!$isvalid) {
                // Bad data: redisplay the form with error messages
                return xarTplModule('eav','admin','new', $data);        
            } else {
                // Good data: create the item
                $itemid = $data['object']->createItem();
                
                // Jump to the next page
                xarController::redirect(xarModURL('eav','admin','view'));
                return true;
            }
        }
        return $data;
    }
?>