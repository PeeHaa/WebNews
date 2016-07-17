<?php declare(strict_types=1);

namespace WebNews\Presentation\Template;

use CodeCollab\Template\Renderer;
use CodeCollab\Theme\Loader;

class Javascript implements Renderer
{
    protected $thisShouldMakeItUniqueCodeCollabTemplateHtmlVariables = [];

    private $theme;

    public function __construct(Loader $theme)
    {
        $this->theme = $theme;
    }

    public function render(string $template, array $data = []): string
    {
        // we store the current state of the template variables
        // so that we have isolated cases on multiple calls to render()
        $backupVariables = $this->thisShouldMakeItUniqueCodeCollabTemplateHtmlVariables;

        if (!empty($data)) {
            $this->thisShouldMakeItUniqueCodeCollabTemplateHtmlVariables = $data;
        }

        try {
            ob_start();
            /** @noinspection PhpIncludeInspection */
            require $this->theme->load($template);
        } finally {
            $output = ob_get_clean();
        }

        $this->thisShouldMakeItUniqueCodeCollabTemplateHtmlVariables = $backupVariables;

        return $output;
    }
}
