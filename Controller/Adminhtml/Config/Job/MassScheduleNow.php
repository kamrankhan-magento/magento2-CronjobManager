<?php

namespace EthanYehuda\CronjobManager\Controller\Adminhtml\Config\Job;

use EthanYehuda\CronjobManager\Model\ManagerFactory;

class MassScheduleNow extends \Magento\Backend\App\Action
{   
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;
    
    /**
     * @var ManagerFactory
     */
    private $managerFactory;
    
    public function __construct(
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\App\Action\Context $context,
        ManagerFactory $managerFactory
        ) {
            parent::__construct($context);
            $this->resultPageFactory = $resultPageFactory;
            $this->managerFactory = $managerFactory;
    }
    
    /**
     * {@inheritDoc}
     * @see \Magento\Backend\App\AbstractAction::_isAllowed()
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('EthanYehuda_CronjobManager::cronjobmanager');
    }
    
    /**
     * Save cronjob
     *
     * @return Void
     */
    public function execute()
    {
        $manager = $this->managerFactory->create();
        $params = $this->getRequest()->getParam('selected');
        if (!isset($params)) {
            $this->getMessageManager()->addErrorMessage("Something went wrong when recieving the request");
            $this->_redirect('*/config/index');
            return;
        }
        try {
            foreach ($params as $jobCode) {
                $manager->scheduleNow($jobCode);
            }
        } catch (\Exception $e) {
            $this->getMessageManager()->addErrorMessage($e->getMessage());
            $this->_redirect('*/config/index/');
            return;
        }
        $this->getMessageManager()->addSuccessMessage("Successfully Ran Schedule Now Action");
        $this->_redirect("*/config/index/");
    }
}