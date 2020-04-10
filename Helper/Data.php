<?php
/**
 * generate tax percent for specific product
 * Copyright (C) 2019  2019
 * 
 * This file is part of Taoufiqaitali/Taxgen.
 * 
 * Taoufiqaitali/Taxgen is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Taoufiqaitali\Taxgen\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\StateException;

class Data extends AbstractHelper
{
    const CLASS_PREFIX= 'TAOUFIQAITALI-TAX-';
    protected $objectManager;
    private $productRepository;

    /**
     * Data constructor.
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
        $this->objectManager= \Magento\Framework\App\ObjectManager::getInstance(); //
        $this->productRepository = $productRepository;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return true;
    }

    /**
     * @param $sku
     * @param $percent
     * @return array
     */
    public function generateTax($sku,$percent){


        try {
            $product = $this->productRepository->get($sku);

        } catch (\Magento\Framework\Exception\NoSuchEntityException $e){
            return array(
                'success'=>false,
                'message'=>'error while loading product: '.$e->getMessage()
            );
        }

        try {
            $className = self::CLASS_PREFIX . intval($percent);
            $resource = $this->objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resource->getConnection();
            $sql = "Select * FROM tax_class where class_name='" . $className . "'";
            $result = $connection->fetchAll($sql);
            if (!count($result)) { //class not exist, so we will generate tax rate and create new tax rule and tax class

                //insert tax class Name
                $sql = "Insert Into tax_class(class_name, class_type) Values ('" . $className . "','PRODUCT')";
                $connection->query($sql);
                $classNameId = $connection->lastInsertId();

                //insert tax rule Name
                $sql = "Insert Into tax_calculation_rule(code, priority,position,calculate_subtotal) Values ('" . $className . "',0,0,0)";
                $connection->query($sql);
                $classRuleId = $connection->lastInsertId();

                //insert tax rate by country
                $sql = "Insert Into tax_calculation_rate select null,country_id,0,'*',CONCAT('" . $className . "-',country_id),'" . $percent . "',NULL,NULL,NULL from directory_country ";
                $connection->query($sql);

                //insert tax calculation
                $sql = "Insert Into tax_calculation select null,tax_calculation_rate_id,'" . $classRuleId . "',3,'" . $classNameId . "' from tax_calculation_rate WHERE `code` LIKE '" . $className . "%'";
                $connection->query($sql);

            } else {
                $classNameId = $result[0]['class_id'];
            }
        } catch (\Exception $e) {
            return array(
                'success'=>false,
                'message'=>'error while generating tax: '.$e->getMessage()
            );
        }

        try {
            $product->setTaxClassId($classNameId);
            $this->productRepository->save($product);
        } catch (CouldNotSaveException $e) {
            return array(
                'success'=>false,
                'message'=>'error while saving product: '.$e->getMessage()
            );
        } catch (InputException $e) {
            return array(
                'success'=>false,
                'message'=>'error while  saving product: '.$e->getMessage()
            );
        } catch (StateException $e) {
            return array(
                'success'=>false,
                'message'=>'error while  saving product: '.$e->getMessage()
            );
        }


        return array(
            'success'=>true,
            'message'=>'tax generated,please reindex and clear cache. Class Name: '.$className
        );
    }
}
