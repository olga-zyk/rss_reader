<?php

namespace App\Controller;

use SimplePie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class RssReaderController
 * @package App\Controller
 */
class RssReaderController extends AbstractController
{
    private const BLACKLIST = array(
        'the', 'be', 'to', 'of', 'and', 'a', 'in', 'that', 'have', 'I',
        'it', 'for', 'not', 'on', 'with', 'he', 'as', 'you', 'do', 'at',
        'this', 'but', 'his', 'by', 'from', 'they', 'we', 'say', 'her', 'she',
        'or', 'an', 'will', 'my', 'one', 'all', 'would', 'there', 'their', 'what',
        'so', 'up', 'out', 'if', 'about', 'who', 'get', 'which', 'go', 'me'
    );

    /**
     * @Route("/rss-reader", name="rss_reader")
     * @param KernelInterface $kernel
     * @return Response
     */
    public function rssRead(KernelInterface $kernel)
    {
        $url = 'https://www.theregister.co.uk/software/headlines.atom';
        $feed = new SimplePie();
        $feed->set_feed_url($url);
        $feed->enable_cache();
        $feed->set_cache_location($kernel->getCacheDir());
        $feed->init();

        $items = $feed->get_items(0, 0);

        $link = '';
        $title = '';
        $date = '';
        $description = '';
        $description_words = array();
        foreach ($items as $item) {
            $link = $item->get_link();
            $title = $item->get_title();
            $date = $item->get_date('Y-m-d H:i:s');
            $description = $item->get_description();

            $words = explode(' ', strip_tags($description));
            $words = array_map(function ($word) {
                return trim($word, '\'"!@#$%^&*()_<>?:[]{}/.,-+');
            }, $words);
            $description_words = array_merge($description_words, $words);
        }

        $wordTotals = $this->getWordsFromDescription($description_words);

        return $this->render('rss_reader/rss_reader.html.twig', [
            'items' => $items,
            'link' => $link,
            'title' => $title,
            'date' => $date,
            'description' => $description,
            'words' => $wordTotals
        ]);
    }

    /**
     * @param array $description_words
     * @return array
     */
    public function getWordsFromDescription(array $description_words): array
    {
        $description_words = array_filter($description_words, function ($word) {
            return !in_array($word, self::BLACKLIST) && preg_match('/[[:alpha:]]/u', $word, $match);
        });

        $wordTotals = [];
        foreach ($description_words as $word) {
            if (!array_key_exists($word, $wordTotals)) {
                $wordTotals[$word] = 0;
            }
            $wordTotals[$word]++;
        }
        arsort($wordTotals, SORT_NUMERIC);
        return $wordTotals;
    }

}
