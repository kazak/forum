<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 06 07 2015
 */
namespace App\CoreBundle\Handler;

use App\CoreBundle\Entity\SmsHistory;
use Symfony\Component\Security\Core\Util\SecureRandom;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use App\CoreBundle\Model\Handler\EntityHandler;

/**
 * Class SmsHandler.
 */
class SmsHandler extends EntityHandler
{
    private $config;

    /**
     * @param Container $container
     * @param $config
     * @param $entityClass
     */
    public function __construct(Container $container, $entityClass, $config)
    {
        parent::__construct($container, $entityClass);

        $this->config = $config;
        $this->validateConfig();
    }

    /**
     * @return mixed
     */
    public function createEntity()
    {
        return parent::createEntity();
    }

    /**
     * Check SMS code in smshistory.
     *
     * @param int    $id
     * @param string $code
     * @param string $phone
     *
     * @return array
     */
    public function checkSMSCode($id, $code, $phone)
    {
        $response = [];

        /**
         * @var SmsHistory $smssend
         */
        $smssend = $this->repository->findOneBy(['id' => $id]);

        $response['error'] = true;
        $response['errorCode'] = 406;
        $response['errorMessage'] = 'Not accepted';

        if (!is_null($smssend)
            && $smssend->getCode() == $code
            && $smssend->getPhone() == $phone
            && $smssend->getInvalidTriedCount() <= $this->config['maxInvalidTried']) {
            if ($smssend->getVerified() == 1) {
                $response['error'] = true;
                $response['errorCode'] = 406;
                $response['errorMessage'] = 'Code is already verified';
            } else {
                $response['error'] = false;
                $response['errorCode'] = 200;
                $response['errorMessage'] = null;
            }
        } elseif (!is_null($smssend)) {
            $smssend->addInvalidTriedCount();
            $this->objectManager->flush();
        }

        return $response;
    }

    /**
     * Send sms code to phone number.
     *
     * @param string $check
     * @param string $phone
     * @param string $ip
     *
     * @return array
     */
    public function sendSMS($phone, $ip, $check)
    {
        $customerHandler = $this->container->get('app_core.customer.handler');
        $customer = $customerHandler->findOne(['phone' => $phone]);
        $customerTypeExists = $customer && $customer->getCustomerType() == 0;

        if ($check == 204) {
            $response['error'] = true;
            $response['errorCode'] = 204;
            $response['errorMessage'] = 'Customer does not exists';
            if (!$customerTypeExists) {
                return $response;
            }
        }

        // Generate a 4 digit PIN code
        $generator = new SecureRandom();
        $code = $generator->nextBytes(2);
        $code = sprintf("%'.04d", intval(hexdec(bin2hex($code)) % 10000));

        $message = 'Code: '.$code;
        $phoneNumber = trim($phone);

        if (!$this->checkCanSend($ip, $phoneNumber)) {
            $response['error'] = true;
            $response['errorCode'] = 406;
            $response['errorMessage'] = 'Exceeding the number of sent messages!';

            return $response;
        }

        $id = $this->writeCode($code, $phoneNumber, $ip);

        $url = $this->config['uri'].'?number='.rawurlencode($phoneNumber).'&message='.rawurlencode($message);
        $userpass = $this->config['userpass'];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERPWD, $userpass);

        $result = curl_exec($curl);

        curl_close($curl);

        if ($result === false) {
            return [
                'status' => 'FAILURE',
                'description' => "Curl returned false!\nError no: ".curl_errno($curl)."\nError message: ".curl_error($curl),
            ];
        }

        if (strncmp($result, 'HTTP/1.', 7) != 0 || substr($result, 8, 1) != ' ') {
            return [
                'status' => 'FAILURE',
                'description' => 'Server response not HTTP 1.',
            ];
        }

        $code = substr($result, 9, strpos($result, ' ', 9) - 9);
        $headend = strpos($result, "\r\n\r\n");
        $body = trim(substr($result, $headend + 4));

        if ($code == '401') {
            return [
                'status' => 'FAILURE',
                'description' => 'Not authorized',
            ];
        } elseif ($code != '200') {
            $response['error'] = false;
            $response['errorCode'] = 400;
            $response['errorMessage'] = null;

            return $response;

        }
        if (strncmp($body, '<?xml ', 6) != 0) {
            return [
                'status' => 'FAILURE',
                'description' => 'Message body is not XML.',
            ];
        }

        if (!preg_match("/\<result\>/", $body)) {
            return [
                'status' => 'FAILURE',
                'description' => 'Root node not "result".',
            ];
        }

//        $status = "";
        if (!preg_match("/\<status\>/", $body)) {
            return [
                'status' => 'FAILURE',
                'description' => 'No (or too many, or empty) "status" node(s).',
            ];
        } else {
            $status = preg_replace("/^[\D\d]*\<status\>/", '', $body);
            $status = trim(preg_replace("/\<\/status\>[\D\d]*\$/", '', $status));
        }

//        $error = "";
        if (!preg_match("/\<error/", $body)) {
            return [
                'status' => 'FAILURE',
                'description' => 'No (or too many, or empty) "status" node(s).',
            ];
        } else {
            $error = preg_replace("/^[\D\d]*\<error\>/", '', $body);
            $error = trim(preg_replace("/\<\/error\>[\D\d]*\$/", '', $error));
        }

        $data = ['id' => $id, 'phone' => $phoneNumber];

        return $this->returnStatus($status, $data, $error);
    }

    /**
     * @param $status
     * @param string $data
     * @param string $error
     * @return mixed
     */
    public function returnStatus($status, $data = '', $error = '')
    {
        switch ($status) {
            case '200':
                $response['error'] = false;
                $response['errorCode'] = 201;
                $response['errorMessage'] = null;
                $response['data'] = $data;

                return $response;

            case '504':
                $response['error'] = true;
                $response['errorCode'] = 504;
                $response['errorMessage'] = 'External system reports: Invalid.';

                return $response;

            case '512':
                $response['error'] = true;
                $response['errorCode'] = 512;
                $response['errorMessage'] = 'External system reports: Failure.';

                return $response;

            case '401':
                $response['error'] = true;
                $response['errorCode'] = 401;
                $response['errorMessage'] = 'Not authorized';

                return $response;

            default:
                $response['error'] = true;
                $response['errorCode'] = $status;
                $response['errorMessage'] = "Returned status code '".$status."' not recognized. ".$error;

                return $response;
        }
    }

    /**
     * @param $clientIp
     * @param $phone
     *
     * @return bool
     */
    public function checkCanSend($clientIp, $phone)
    {
        $date = new \DateTime();

        $count_ip = $this->repository->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.ip = :ip')
            ->andWhere('u.date = :date')
            ->setParameter('ip', $clientIp)
            ->setParameter('date', $date->format('Y-m-d'))
            ->getQuery()
            ->getSingleScalarResult();

        $count_phone = $this->repository->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.phone = :phone')
            ->andWhere('u.date = :date')
            ->setParameter('phone', $phone)
            ->setParameter('date', $date->format('Y-m-d'))
            ->getQuery()
            ->getSingleScalarResult();

        return $count_ip < $this->config['maxMessageFromIp']
            && $count_phone < $this->config['maxMessageFromPhone'];
    }

    /**
     * @param $code
     * @param $phone
     * @param $clientIp
     */
    private function writeCode($code, $phone, $clientIp)
    {
        $date = new \DateTime();
        $sms = $this->createEntity();
        $sms->setIp($clientIp)->
            setDate($date)->
            setCode($code)->
            setVerified(0)->
            setPhone($phone);
        $this->objectManager->persist($sms);
        $this->objectManager->flush();

        return $sms->getID();
    }

    /**
     * @param $phone
     */
    public function setVerifiedByPhone($phone)
    {
        $sms = $this->getEntities(['phone' => $phone]);

        foreach ($sms as $entity) {
            $entity->setVerified(1);
        }
        $this->objectManager->flush();
    }

    /**
     * validate configuration
     */
    public function validateConfig()
    {
        if (!isset($this->config['uri']) || !isset($this->config['userpass'])) {
            throw new InvalidConfigurationException('Missing app_sms configuration');
        }
    }
}
