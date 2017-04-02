<?php declare(strict_types=1);

namespace WebNews\Install;

class Writer
{
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function write(array $configuration): void
    {
        file_put_contents($this->path . '/config.php', $this->getStart() . $this->getConfiguration($configuration));
    }

    private function getStart(): string
    {
        return "<?php declare(strict_types=1);\n\n";
    }

    private function getConfiguration(array $configuration): string
    {
        return '$configuration = ' . var_export($configuration, true) . ";\n";
    }
}
