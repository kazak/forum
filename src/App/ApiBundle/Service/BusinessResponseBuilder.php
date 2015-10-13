<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 16.09.15
 */

namespace App\ApiBundle\Service;


use App\ApiBundle\Model\Service\BaseResponseBuilder;

/**
 * Class BusinessResponseBuilder
 * @package App\ApiBundle\Service
 */
class BusinessResponseBuilder extends BaseResponseBuilder
{
    /**
     * @inheritDoc
     */
    public function buildGetBusinessResponse($customer)
    {
        if (!$customer) {
            return $this->getCustomerNotFound();
        }

        return $this->getSuccessResponse($customer);
    }

    /**
     * @return \FOS\RestBundle\View\View
     */
    private function getCustomerNotFound()
    {
        $data = [
            'status' => 404,
            'error' => [
                'code' => 404,
                'message' => 'Business not found'
            ]
        ];
        return $data;
    }

    /**
     * @param Customer $customer
     * @return \FOS\RestBundle\View\View
     */
    private function getSuccessResponse($customer)
    {
        $customerData = [
            "id" => $customer->getId(),
            "osCustomerId" => $customer->getOsCustomerId(),
            "companyName" => $customer->getCompanyName(),
            "canPayWithInvoice" => false,
            "invoiceAddress" => null,
            "invoicePostCode" => null,
            "invoicePostOffice" => null,
            "invoiceCountry" => null,
            "references" => $this->container->get('app_core.customer.handler')->getReferences($customer)
        ];

        if ($customer->getSettings()) {
            $customerData['canPayWithInvoice'] = !!$customer->getSettings()->getIsInvoiceAvailable();
        }

        if ($customer->getInvoice()) {
            $customerData['invoiceAddress'] = $customer->getInvoice()->getAddress();
            $customerData['invoicePostCode'] = $customer->getInvoice()->getPostCode();
            $customerData['invoicePostOffice'] = $customer->getInvoice()->getCoAddress();
            $customerData['invoiceCountry'] = $customer->getInvoice()->getCountry();
        }

        $data = [
            "status" => 200,
            "data" => $customerData
        ];

        return $data;
    }

}