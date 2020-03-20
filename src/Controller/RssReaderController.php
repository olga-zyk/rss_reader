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
    /**
     * @Route("/rss-reader", name="rss_reader")
     * @param KernelInterface $kernel
     * @return Response
     */
    public function index(KernelInterface $kernel)
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
        foreach ($items as $item) {
            $link = $item->get_link();
            $title = $item->get_title();
            $date = $item->get_date('Y-m-d H:i:s');
            $description = $item->get_description();
        }

        return $this->render('rss_reader/rss_reader.html.twig', [
            'items' => $items,
            'link' => $link,
            'title' => $title,
            'date' => $date,
            'description' => $description
        ]);
    }
}
