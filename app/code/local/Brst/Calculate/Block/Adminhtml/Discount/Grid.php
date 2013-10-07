<?php
class Brst_Calculate_Block_Adminhtml_Discount_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
         
        // Set some defaults for our grid
        $this->setDefaultSort('total_order');
        $this->setId('brst_calculate_discount_grid');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }
     
    protected function _getCollectionClass()
    {
        // This is the model we are using for the grid
        return 'brst_calculate/discount_collection';
    }
     
    protected function _prepareCollection()
    {
        // Get and set our collection for the grid
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $this->setCollection($collection);
         
        return parent::_prepareCollection();
    }
     
    protected function _prepareColumns()
    {
        // Add the columns that should appear in the grid
        $model = Mage::getModel('brst_calculate/discount')->getCollection()->getData();
        foreach($model as $k => $name)
        {
            $name1[$name['member_name']] = $name['member_name'];
        }
        $this->addColumn('member_id',
            array(
                'header'=> $this->__('Member ID'),
                'align' =>'left',
                'width' => '50px',
                'index' => 'member_id'
            )
        );
        $this->addColumn('member_name',
            array(
                'header'=> $this->__('Name'),
                'index' => 'member_name',
                'type'  => 'options',
                'options' => $name1,
            )
        );
        $this->addColumn('total_order',
            array(
                'header'=> $this->__('Total Orders'),
                'index' => 'total_order'
            )
        );
        $this->addColumn('gross_earned',
            array(
                'header'=> $this->__('Gross Earned'),
                'index' => 'gross_earned'
            )
        );
        $this->addColumn('amount_earned',
            array(
                'header'=> $this->__('Amount Earned'),
                'index' => 'amount_earned'
            )
        );
        
        $this->addColumn('tax_paid',
            array(
                'header'=> $this->__('Tax Paid'),
                'index' => 'tax_paid'
            )
        );
        
        $this->addColumn('admin_amount',
            array(
                'header'=> $this->__('Admin Earned'),
                'index' => 'admin_amount'
            )
        );
        
        $this->addColumn('balance',
            array(
                'header'=> $this->__('Balance'),
                'index' => 'balance'
            )
        );
        
        $this->addExportType('*/*/exportCsv', $this->__('CSV'));
        $this->addExportType('*/*/exportExcel', $this->__('Excel XML'));
        
        return parent::_prepareColumns();
    }
    
    public function getRowUrl($row)
    {
        // This is where our row data will link to
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}