<?php
namespace Nas\Taxgen\Controller\Adminhtml\Tax;


use Nas\Taxgen\Helper\Data;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    protected $helper;

    /**
     * Save constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param Data $helper
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        Data $helper,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->helper=$helper;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        //return  $resultPage = $this->resultPageFactory->create();





        $post = $this->getRequest()->getPost();


        if(isset($post['sku'],$post['percent'])){
            $result = $this->helper->generateTax($post['sku'],$post['percent']);
            $message = $result['message'];

//exit;
            if($result['success']){
                $this->messageManager->addSuccessMessage($message);
            }else{
                $this->messageManager->addErrorMessage($message);
            }


        }else{
            $message = __('Please add correct sku and percentage');
            $this->messageManager->addErrorMessage($message);
        }

        //$this->messageManager->addSuccessMessage(__('Please add correct sku and percentage'));

        return $this->resultRedirectFactory->create()->setPath('nas_taxgen/tax/index', ['_current' => true]);
    }
}
?>