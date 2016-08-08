<?php declare(strict_types=1);

namespace WebNews\Presentation\Template;

use CodeCollab\Template\Html as BaseTemplate;
use CodeCollab\Theme\Loader;
use CodeCollab\I18n\Translator;
use CodeCollab\CsrfToken\Token;
use Minifine\Minifine;

class Html extends BaseTemplate
{
    private $theme;

    private $translator;

    protected $csrfToken;

    protected $minifier;

    public function __construct(
        string $basePage,
        Loader $theme,
        Translator $translator,
        Token $csrfToken,
        Minifine $minifier
    )
    {
        parent::__construct($basePage);

        $this->theme      = $theme;
        $this->translator = $translator;
        $this->csrfToken  = $csrfToken;
        $this->minifier   = $minifier;
    }

    public function render(string $template, array $data = []): string
    {
        return parent::render($this->theme->load($template), $data);
    }

    public function renderPage(string $template, array $data = []): string
    {
        $this->thisShouldMakeItUniqueCodeCollabTemplateHtmlVariables = $data;

        /** @noinspection PhpUnusedLocalVariableInspection */
        $content = $this->render($template, $data);

        try {
            ob_start();
            /** @noinspection PhpIncludeInspection */
            require $this->theme->load($this->thisShouldMakeItUniqueCodeCollabTemplateHtmlBasePage);
        } finally {
            $output = ob_get_clean();
        }

        return $output;
    }

    protected function translate(string $key, array $data = []): string
    {
        return $this->translator->translate($key, $data);
    }

    protected function buildUrlPath(string $content): string
    {
        $path = iconv('utf-8', 'iso-8859-1//TRANSLIT', $content);
        $path = preg_replace('~[^A-Za-z0-9\-]~', '-', $path);
        $path = preg_replace('~-{2,}~', '-', $path);
        $path = trim($path, '-');

        return $path;
    }
}
