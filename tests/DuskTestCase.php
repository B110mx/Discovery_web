<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Collection;
use Laravel\Dusk\TestCase as BaseTestCase;
use Tests\Support\DuskDatabaseSafety;

abstract class DuskTestCase extends BaseTestCase
{
    /**
     * Validate the database before Laravel runs DatabaseMigrations.
     *
     * @return array<class-string>
     */
    protected function setUpTraits(): array
    {
        $connection = (string) config('database.default');

        DuskDatabaseSafety::assertSafe(
            $connection,
            (string) config("database.connections.{$connection}.database"),
            base_path(),
        );

        return parent::setUpTraits();
    }

    protected function driver(): RemoteWebDriver
    {
        $options = (new ChromeOptions)->addArguments(collect([
            $this->shouldStartMaximized() ? '--start-maximized' : '--window-size=1920,1080',
            '--disable-search-engine-choice-screen',
            '--disable-smooth-scrolling',
            '--blink-settings=imagesEnabled=false',
        ])->unless($this->hasHeadlessDisabled(), function (Collection $items) {
            return $items->merge([
                '--disable-dev-shm-usage',
                '--disable-gpu',
                '--headless=new',
                '--no-sandbox',
            ]);
        })->all());

        $capabilities = DesiredCapabilities::chrome()
            ->setCapability(ChromeOptions::CAPABILITY, $options)
            ->setCapability('pageLoadStrategy', 'eager');

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? env('DUSK_DRIVER_URL') ?? 'http://localhost:9515',
            $capabilities,
        );
    }
}
