<?php

namespace App\Service;

use App\Model\Mountain;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverSelect;
use Symfony\Component\Panther\Client;

class BeraExtractorService
{
    private WebDriver $driver;

    public function __construct()
    {
        $options = new ChromeOptions();
        $options->addArguments(['--headless']);

        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

        $this->driver = Client::createFirefoxClient();
    }

    public function extractPdfUrls(\DateTime $date): array
    {

        $urlTimePublishment = 'https://donneespubliques.meteofrance.fr/?fond=produit&id_produit=265&id_rubrique=50';
        $client = Client::createChromeClient();
        $client->get($urlTimePublishment);

        $client->executeScript("document.querySelector('.telechargements:last-of-type h3').click()");


        $urls = [];
        $client->takeScreenshot('var/screen.png');
        $client->waitForVisibility('#from_bra');


        foreach (Mountain::cases() as $id => $mountain) {
            $datepicker = $client->getCrawler()->findElement(WebDriverBy::id('datepicker'));
            $datepicker->sendKeys(WebDriverKeys::CONTROL . 'a');
            $datepicker->sendKeys(strtotime('Ymd'));

            $select = $client->getCrawler()->findElement(WebDriverBy::id('select_massif'));
            $select->click();
            $client->takeScreenshot('var/screen' . $id . '.png');


            continue;
            $form = $client->selectButton('Valider')->form();
            $form['select_massif'] = $mountain[0];
            $crawler = $client->submit($form);
            $datetimePublication = $crawler->filter('#select_heures > option')->last()->attr('value');
            $urlDls[str_replace('/', '_', $mountain[1])][] = $datetimePublication;
        }

        return $urlDls;
    }


    public function extract(\DateTime $date = null): array
    {
        $urlTimePublishment = 'https://donneespubliques.meteofrance.fr/?fond=produit&id_produit=265&id_rubrique=50';
        $this->driver->get($urlTimePublishment);

//        $this->driver->takeScreenshot('var/screen-before-cookies.png');
        $this->acceptEventualCookies();

        $this->driver->findElements(WebDriverBy::cssSelector('.telechargements'))[3]->click();

        $datePicker = $this->driver->findElement(WebDriverBy::id('datepicker'));
        $urlDls = [];


        $datePicker->sendKeys(WebDriverKeys::CONTROL . 'a');
        $this->driver->takeScreenshot('var/screen-before-date');
        $datePicker->sendKeys($date->format('Ymd'));
        $datePicker->sendKeys(WebDriverKeys::ENTER);
        $this->driver->executeScript("document.querySelector('.ui-state-default.ui-state-active').click()");

        $this->driver->takeScreenshot('var/screen-after-date');

        $this->driver->executeScript("document.getElementById('select_massif').scrollIntoView();");
        $selectMassif = new WebDriverSelect($this->driver->findElement(WebDriverBy::id('select_massif')));

        if (count($selectMassif->getOptions()) === 0) {
            return [];
        }


        foreach (range(0, count($selectMassif->getOptions()) - 1) as $index) {
            $this->driver->takeScreenshot("var/screen-$index-before-click");
            $selectMassif->selectByIndex($index);
//                $massif->click();

            $elements = $this->driver->findElements(WebDriverBy::cssSelector('#select_heures > option'));
            $this->driver->takeScreenshot("var/screen-$index-before-hours");

            if (count($elements) > 0) {
                $datetimePublication = end($elements)->getAttribute('value');
                $urlDls[str_replace('/', '_', $selectMassif->getFirstSelectedOption()->getText())] = $datetimePublication;
                dump("adding {$selectMassif->getFirstSelectedOption()->getText()} : $datetimePublication");
            }
        }

        $this->driver->quit();

        return $urlDls;
    }

    private function acceptEventualCookies(): void
    {
        $this->driver->manage()->timeouts()->implicitlyWait(3);
        try {
            $this->driver->findElement(WebDriverBy::xpath("//input[@type='submit' and @value='Accepter']"))->click();
        } catch (NoSuchElementException $e) {
            // do nothing
        }
    }

}
