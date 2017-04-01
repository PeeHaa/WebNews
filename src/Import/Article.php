<?php declare(strict_types=1);

namespace WebNews\Import;

use PeeHaa\Nntp\Client;
use PeeHaa\Nntp\Command\Article as ArticleCommand;
use PeeHaa\Nntp\Command\XOver as XOverCommand;
use PeeHaa\Nntp\Encoding\Utf8;
use PeeHaa\Nntp\Result\Article as ArticleContentResult;
use PeeHaa\Nntp\Result\InvalidResultException;
use PeeHaa\Nntp\Result\ListGroup;
use PeeHaa\Nntp\Result\ListGroupCollection;
use PeeHaa\Nntp\Result\XOverArticle;
use PeeHaa\Nntp\Result\XOverArticleCollection;
use WebNews\Storage\Postgres\Article as ArticleStorage;
use WebNews\Storage\Postgres\Thread as ThreadStorage;

class Article
{
    private $client;

    private $threadStorage;

    private $articleStorage;

    private $converter;

    public function __construct(Client $client, ThreadStorage $threadStorage, ArticleStorage $articleStorage)
    {
        $this->client         = $client;
        $this->threadStorage  = $threadStorage;
        $this->articleStorage = $articleStorage;

        $this->converter      = new Utf8();
    }

    public function import(ListGroupCollection $groups): void
    {
        foreach ($groups as $groupIndex => $listResult) {
            $this->getArticlesOver($listResult);
        }
    }

    private function getArticlesOver(ListGroup $listResult): void
    {
        $start = $listResult->getLowWatermark();

        do {
            $xOverResult = $this->client->sendCommand(new XOverCommand($start, $start + 200));

            try {
                $articleResults = new XOverArticleCollection($this->converter, $xOverResult->getData());
            } catch (InvalidResultException $e) {
                var_dump('ERROR: Message could not be parsed.');

                exit;
            }

            $this->getArticles($articleResults, $listResult);

            $start += 200;
        } while($start <= $listResult->getHighWatermark());
    }

    private function getArticles(XOverArticleCollection $articleResults, ListGroup $listResult): void
    {
        foreach ($articleResults as $articleResult) {
            if ($this->threadStorage->articleExists($articleResult)) {
                continue;
            }

            $article = $this->client->sendCommand(new ArticleCommand($articleResult->getWatermark()));
            $articleContentResult = new ArticleContentResult($this->converter, $article->getData());

            $this->storeArticle($articleResult, $articleContentResult, $listResult);
        }
    }

    private function storeArticle(
        XOverArticle $articleResult,
        ArticleContentResult $articleContentResult,
        ListGroup $listResult
    ): void {
        $threadId = $this->getThreadId($articleResult, $listResult);

        $this->articleStorage->create($threadId, $articleResult, $articleContentResult);
    }

    private function getThreadId(XOverArticle $articleResult, ListGroup $listResult): int
    {
        if ($articleResult->startsNewThread()) {
            return $this->threadStorage->create($listResult, $articleResult);
        }

        $threadId = $this->threadStorage->findByReference($articleResult);

        // could not find the reference?
        if ($threadId === 0) {
            $threadId = $this->threadStorage->create($listResult, $articleResult);
        }

        return $threadId;
    }
}
