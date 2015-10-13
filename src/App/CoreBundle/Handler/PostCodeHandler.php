<?php

/**
 * @author:     lars <lars@norse.digital>
 *
 * @copyright   Copyright (C) 2015 Nor.
 * @date: 06 07 2015
 */
namespace App\CoreBundle\Handler;

use Symfony\Component\Console\Output\OutputInterface;
use App\CoreBundle\Model\Handler\EntityHandler;

/**
 * Class PostCodeHandler.
 */
class PostCodeHandler extends EntityHandler
{
    /**
     * @param $data
     * @param bool|false|OutputInterface $output
     */
    public function import($data, $output = false)
    {
        if (!is_array($data)) {
            $data = explode("\r\n", utf8_encode($data));
        }

        $values = [];
        
        foreach ($data as $line) {
            list($postCode, $city, $municipality, $latitude, $longitude) = explode(';', $line);

            if ($this->isCorrectPostCode($postCode)) {
                $this->writeln($output, "Importing $postCode\n  - $city\n  - $municipality\n  - $longitude\n  - $latitude\n");

                $values[] = "('$postCode','$city','$municipality','$longitude','$latitude')";
            }
        }

        if (empty($values)) {
            return;
        }

        $this->container->get('doctrine')->getManager()->getConnection()->query(
            "REPLACE INTO postcode VALUES " . implode(',', $values)
        )->execute();
    }

    /**
     * @param $postCode
     *
     * @return bool
     */
    private function isCorrectPostCode($postCode)
    {
        return isset($postCode) && preg_match('/^\d\d\d\d$/', $postCode);
    }

    /**
     * @param OutputInterface $output
     * @param $str
     */
    private function writeln($output, $str)
    {
        if ($output) {
            $output->writeln($str);
        }
    }
}
