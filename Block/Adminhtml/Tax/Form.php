<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Nas\Taxgen\Block\Adminhtml\Tax;

/**
 * Encryption key change form block
 *
 * @api
 * @since 100.0.2
 */
class Form extends \Magento\Backend\Block\Template
{
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Data\Form\FormKey $formKey,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->formKey = $formKey;
    }

    /**
     * get form key
     *
     * @return string
     */
    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }

    public function getActionSaveUrl(){
        return $this->getUrl(
            'nas_taxgen/tax/save'
        );
    }

}
