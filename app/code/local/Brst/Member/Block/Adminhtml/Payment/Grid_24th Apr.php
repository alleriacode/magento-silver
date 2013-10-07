<?php
class Brst_Member_Block_Adminhtml_Payment_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        
        // Set some defaults for our grid
        $this->setDefaultSort('id');
        $this->setId('brst_member_payment_grid');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }
    
    protected function _getCollectionClass()
    {
        // This is the model we are using for the grid
        return 'brst_member/payment_collection';
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
        $model = Mage::getModel('brst_member/payment')->getCollection()->getData();
        foreach($model as $k => $name)
        {
            $name1[$name['member_name']] = $name['member_name'];
        }
        $this->addColumn('id',
            array(
                'header'=> $this->__('ID'),
                'align' =>'right',
                'width' => '50px',
                'index' => 'id'
            )
        );
        
        $this->addColumn('member_name',
            array(
                'header'=> $this->__('Member Name'),
                'index' => 'member_name',
                'type'  => 'options',
                'options' => $name1
            )
        );
        
        $this->addColumn('total_earned',
            array(
                'header'=> $this->__('Total Earned'),
                'index' => 'total_earned'
            )
        );
        
        $this->addColumn('total_paid',
            array(
                'header'=> $this->__('Total Paid'),
                'index' => 'total_paid'
            )
        );
        
        $this->addColumn('pending_amount',
            array(
                'header'=> $this->__('Pening Amount'),
                'index' => 'pending_amount'
            )
        );
        
        $this->addColumn('inprogress',
            array(
                'header'=> $this->__('Inprogress'),
                'index' => 'inprogress'
            )
        );
        //1365120000
        //2013-04-08
        $this->addColumn('last_invoice_date',
            array(
                'header'=> $this->__('Last Invoice Date'),
                'index' => 'last_invoice_date',
                'type' => 'datetime',
                'width' => '100px',
            )
        );
        
        $this->addColumn('status',
            array(
                'header'=> $this->__('Status'),
                'index' => 'status'
            )
        );
        
        $this->addColumn('send_invoice',
            array(
                'header'=> $this->__('Send Invoice'),
                'index' => 'send_invoice',
                'sortable'  => false,
                'filter'    => false,
                'renderer'  => Brst_Member_Block_Adminhtml_Grid_Renderer_Mail,
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