<?php

class LinkBlockPresenter
{
    private $link;
    private $language;

    public function __construct(Link $link, Language $language)
    {
        $this->link = $link;
        $this->language = $language;
    }

    public function present(LinkBlock $cmsBlock)
    {
        return array(
            'id' => $cmsBlock->id,
            'title' => $cmsBlock->name[$this->language->id],
            'hook' => (new Hook($cmsBlock->id_hook))->name,
            'position' => $cmsBlock->position,
            'links' => $this->makeLinks($cmsBlock->content),
        );
    }

    private function makeLinks($content)
    {
        $cmsLinks = $productLinks = $staticsLinks = [];

        if (isset($content['cms'])) {
            $cmsLinks = $this->makeCmsLinks($content['cms']);
        }

        if (isset($content['product'])) {
            $productLinks = $this->makeProductLinks($content['product']);
        }

        if (isset($content['static'])) {
            $staticsLinks = $this->makeStaticLinks($content['static']);
        }

        return array_merge($cmsLinks, $productLinks, $staticsLinks);
    }

    private function makeCmsLinks($cmsIds)
    {
        $cmsLinks = array();

        foreach ($cmsIds as $cmsId) {
            $cms = new CMS($cmsId);
            if(null !== $cms->id) {
                $cmsLinks[] = array(
                    'id' => 'cms-page-'.$cms->id,
                    'class' => 'cms-page-link',
                    'title' => $cms->meta_title[$this->language->id],
                    'description' => $cms->meta_description[$this->language->id],
                    'url' => $this->link->getCMSLink($cms),
                );
            }

        }

        return $cmsLinks;
    }

    private function makeProductLinks($productIds)
    {
        $productLinks = array();
        foreach ($productIds as $productId) {
            if(false !== $productId) {
                $meta = Meta::getMetaByPage($productId, $this->language->id);
                $productLinks[] = array(
                    'id' => 'cms-page-'.$productId,
                    'class' => 'cms-page-link',
                    'title' => $meta['title'],
                    'description' => $meta['description'],
                    'url' => $this->link->getPageLink($productId, true),
                );
            }
        }

        return $productLinks;
    }

    private function makeStaticLinks($staticIds)
    {
        $staticLinks = array();
        foreach ($staticIds as $staticId) {
            $meta = Meta::getMetaByPage($staticId, $this->language->id);
            $staticLinks[] = array(
                'id' => 'cms-page-'.$staticId,
                'class' => 'cms-page-link',
                'title' => $meta['title'],
                'description' => $meta['description'],
                'url' => $this->link->getPageLink($staticId, true),
            );
        }

        return $staticLinks;
    }
}