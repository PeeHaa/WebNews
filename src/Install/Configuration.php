<?php declare(strict_types=1);

namespace WebNews\Install;

use League\CLImate\CLImate;

class Configuration
{
    private const CONFIGURATION_MAPPING = [
        1 => [
            'label'    => 'Environment',
            'property' => 'devEnvironment',
            'prompt'   => 'setUpEnvironment',
            'filter'   => 'renderOverviewEnvironment',
        ],
        2 => [
            'label'    => 'Database host',
            'property' => 'databaseHost',
            'prompt'   => 'setUpDatabaseHost',
            'filter'   => null,
        ],
        3 => [
            'label'    => 'Database name',
            'property' => 'databaseName',
            'prompt'   => 'setUpDatabaseName',
            'filter'   => null,
        ],
        4 => [
            'label'    => 'Database username',
            'property' => 'databaseUsername',
            'prompt'   => 'setUpDatabaseUsername',
            'filter'   => null,
        ],
        5 => [
            'label'    => 'Database password',
            'property' => 'databasePassword',
            'prompt'   => 'setUpDatabasePassword',
            'filter'   => 'renderOverviewPassword',
        ],
    ];

    private $climate;

    private $devEnvironment = 'n';

    private $databaseHost = '127.0.0.1';

    private $databaseName = null;

    private $databaseUsername = null;

    private $databasePassword = '';

    public function __construct(CLImate $climate)
    {
        $this->climate = $climate;
    }

    public function run(): array
    {
        $this->renderIntroMessage();

        $this->setUpEnvironment();

        $this->setUpDatabaseHost();

        $this->setUpDatabaseName();

        $this->setUpDatabaseUsername();

        $this->setUpDatabasePassword();

        $this->renderOverview();

        return $this->getConfiguration();
    }

    private function renderIntroMessage(): void
    {
        $this->climate->out('Setting up configuration...');

        $this->climate->out('');
    }

    private function setUpEnvironment(): void
    {
        $this->devEnvironment = $this->climate
            ->input('Is this a development environment? [' . $this->devEnvironment . ']')
            ->defaultTo($this->devEnvironment)
            ->accept(['y', 'n'])
            ->prompt()
        ;
    }

    private function setUpDatabaseHost(): void
    {
        $this->databaseHost = $this->climate
            ->input('Database host: [' . $this->databaseHost . ']')
            ->defaultTo($this->databaseHost)
            ->prompt()
        ;

        if (!$this->databaseHost) {
            $this->setUpDatabaseHost();
        }
    }

    private function setUpDatabaseName(): void
    {
        $this->databaseName = $this->climate
            ->input('Database name:')
            ->prompt()
        ;

        if (!$this->databaseName) {
            $this->setUpDatabaseName();
        }
    }

    private function setUpDatabaseUsername(): void
    {
        $this->databaseUsername = $this->climate
            ->input('Database username:')
            ->prompt()
        ;

        if (!$this->databaseUsername) {
            $this->setUpDatabaseUsername();
        }
    }

    private function setUpDatabasePassword(): void
    {
        $this->databasePassword = $this->climate
            ->password('Database password:')
            ->prompt()
        ;

        $this->confirmDatabasePassword();
    }

    private function confirmDatabasePassword(): void
    {
        $password = $this->climate
            ->password('Repeat database password:')
            ->prompt()
        ;

        if ($password !== $this->databasePassword) {
            $this->setUpDatabasePassword();
        }
    }

    private function renderOverview(): void
    {
        $overview = [
            [
                '#',
                'Configuration option',
                'Value',
            ],
        ];

        foreach (self::CONFIGURATION_MAPPING as $key => $configurationOption) {
            $value = $this->{$configurationOption['property']};

            if ($configurationOption['filter']) {
                $value = $this->{$configurationOption['filter']}();
            }

            $overview[] = [
                $key,
                $configurationOption['label'],
                $value,
            ];
        }

        $this->climate->out('');
        $this->climate->out('Configuration overview');

        $this->climate->table($overview);

        $this->climate->out('');

        $confirmation = $this->climate
            ->input('Select an configuration option to edit or type y to confirm [y]')
            ->defaultTo('y')
            ->accept(['y', '1', '2', '3', '4', '5'])
            ->prompt()
        ;

        if ($confirmation !== 'y') {
            $this->{self::CONFIGURATION_MAPPING[$confirmation]['prompt']}();

            $this->renderOverview();
        }
    }

    private function renderOverviewEnvironment(): string
    {
        return $this->devEnvironment === 'y' ? 'Development' : 'Production';
    }

    private function renderOverviewPassword(): string
    {
        return '*****';
    }

    private function getConfiguration(): array
    {
        return [
            'reloadRoutes'       => $this->devEnvironment === 'y' ? true : false,
            'minifyResources'    => $this->devEnvironment === 'y'  ? true : false,
            'activeTheme'        => 'Default',
            'resourcesDirectory' => '/resources',
            'activeLanguage'     => 'en_US',
            'dbDsn'              => 'pgsql:dbname=' . $this->databaseName . ';host=' . $this->databaseHost,
            'dbUsername'         => $this->databaseUsername,
            'dbPassword'         => $this->databasePassword,
        ];
    }
}
