<?php
/**
 * This file is part of a FireGento e.V. module.
 *
 * This FireGento e.V. module is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * PHP version 5
 *
 * @category  FireGento
 * @package   FireGento_Logger
 * @author    FireGento Team <team@firegento.com>
 * @copyright 2013 FireGento Team (http://www.firegento.com)
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 */
/**
 * Logger Grid
 *
 * @category FireGento
 * @package  FireGento_Logger
 * @author   FireGento Team <team@firegento.com>
 */
class FireGento_Logger_Block_Adminhtml_Logger_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * @var FireGento_Logger_Helper_Data
     */
    protected $_helper;

    /**
     * Instantiate the helper
     */
    protected function _construct()
    {
        $this->_helper = Mage::helper('firegento_logger');
    }

    /**
     * Grid Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('logger_grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('desc');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Prepare the grid collection with the database log entries
     *
     * @return FireGento_Logger_Block_Adminhtml_Logger_Grid the grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('firegento_logger/db_entry')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare the grid columns
     *
     * @return FireGento_Logger_Block_Adminhtml_Logger_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('id', [
            'header' => Mage::helper('firegento_logger')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'entity_id',
        ]);

        $this->addColumn('message', [
            'header' => Mage::helper('firegento_logger')->__('Message'),
            'align' => 'left',
            'index' => 'message',
        ]);

        $this->addColumn('timestamp', [
            'header' => Mage::helper('firegento_logger')->__('Timestamp'),
            'type' => 'datetime',
            'align' => 'left',
            'index' => 'timestamp',
        ]);

        $this->addColumn('advanced_info', [
            'header' => Mage::helper('firegento_logger')->__('Advanced Info'),
            'align' => 'left',
            'index' => 'advanced_info',
            'frame_callback'=> $this->decorateAdvancedInfo(...)
        ]);

        $this->addColumn('severity', [
            'header' => Mage::helper('firegento_logger')->__('Log Level'),
            'align' => 'left',
            'index' => 'severity',
            'type' => 'options',
            'width' => '120px',
            'options' => $this->getSeverityOptions(),
            'frame_callback' => $this->decorateSeverity(...)
        ]);

        return parent::_prepareColumns();
    }

    /**
     * Prepare mass actions.
     *
     * @return $this current
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('log_id');
        $this->getMassactionBlock()->setFormFieldName('log');
        $this->getMassactionBlock()->addItem('delete', [
            'label' => Mage::helper('firegento_logger')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('firegento_logger')->__('Are you sure?')
        ]);
        return $this;
    }

    /**
     * Add a severity css class to the value
     *
     * @param  mixed         $value Logging Value
     * @param  Varien_Object $row   DB Log Entry
     * @return string Decorated HTML
     */
    public function decorateSeverity($value, $row)
    {
        $class = '';
        $class = match ($row->getSeverity()) {
            Zend_Log::EMERG, Zend_Log::ALERT, Zend_Log::CRIT, Zend_Log::ERR => 'grid-severity-critical',
            Zend_Log::WARN, Zend_Log::NOTICE, Zend_Log::INFO, Zend_Log::DEBUG => 'grid-severity-minor',
            default => 'grid-severity-critical',
        };

        return '<span class="' . $class . '"><span>' . $value . '</span></span>';
    }

    /**
     * Formats advanced info
     *
     * @param  string $value just a value
     * @return string
     */
    public function decorateAdvancedInfo($value)
    {
        return nl2br($value);
    }

    /**
     * Retrieve the severity options
     *
     * @return array
     */
    public function getSeverityOptions()
    {
        return [
            Zend_Log::EMERG => $this->_helper->__('Emergency'),
            Zend_Log::ALERT => $this->_helper->__('Alert'),
            Zend_Log::CRIT => $this->_helper->__('Critical'),
            Zend_Log::ERR => $this->_helper->__('Error'),
            Zend_Log::WARN => $this->_helper->__('Warning'),
            Zend_Log::NOTICE => $this->_helper->__('Notice'),
            Zend_Log::INFO => $this->_helper->__('Info'),
            Zend_Log::DEBUG => $this->_helper->__('Debug'),
        ];
    }

    /**
     * Get the current row url
     *
     * @param  FireGento_Logger_Model_Db_Entry $item the entry
     *
     * @return string
     */
    public function getRowUrl($item)
    {
        return $this->getUrl('*/*/view', ['loggerentry_id' => $item->getId()]);
    }
}
