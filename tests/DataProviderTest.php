<?php

use PHPUnit\Framework\TestCase;
use Ltv\Service\DataProvider;

final class DataProviderTest extends TestCase
{
    public function testSetInput(): void
    {
        $dataProvider = new DataProvider();

        $this->assertTrue(is_array($dataProvider->getDataAll()));
        $this->assertTrue(is_array($dataProvider->getDataByLocale(DataProvider::LOCALE_EN)));
        $this->assertTrue(is_array($dataProvider->getLocales()));
    }
}
