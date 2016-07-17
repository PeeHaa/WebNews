<?php declare(strict_types=1);

namespace WebNews\Presentation\Template;

use CodeCollab\Template\Html as BaseTemplate;
use CodeCollab\Theme\Loader;
use CodeCollab\I18n\Translator;
use CodeCollab\CsrfToken\Token;

class Html extends BaseTemplate
{
    private $theme;

    private $translator;

    protected $csrfToken;

    public function __construct(
        string $basePage,
        Loader $theme,
        Translator $translator,
        Token $csrfToken
    )
    {
        parent::__construct($basePage);

        $this->theme      = $theme;
        $this->translator = $translator;
        $this->csrfToken  = $csrfToken;
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
}
