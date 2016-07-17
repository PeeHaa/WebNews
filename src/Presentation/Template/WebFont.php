<?php declare(strict_types=1);

namespace WebNews\Presentation\Template;

use CodeCollab\Template\Renderer;
use CodeCollab\Theme\Loader;

class WebFont implements Renderer
{
    protected $thisShouldMakeItUniqueCodeCollabTemplateHtmlVariables = [];

    private $theme;

    private $filename;

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

    public function getMimeType()
    {
        if (!$this->filename) {
            return 'text/plain';
        }

        switch (pathinfo($this->filename)['extension']) {
            case 'otf':
                return 'application/x-font-otf';

            case 'eot':
                return 'application/vnd.ms-fontobject';

            case 'svg':
                return 'image/svg+xml';

            case 'ttf':
                return 'application/x-font-ttf';

            case 'woff':
                return 'application/font-woff';

            case 'woff2':
                return 'application/font-woff2';

            default:
                return 'application/octet-stream';
        }
    }
}
