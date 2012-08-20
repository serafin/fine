<?php

class m_resource extends f_m
{

    public $resource_id;
    public $resource_baseType; // article_mag article_news article_wall wiki_book wiki_film wiki_person
    public $resource_baseId;
    public $resource_attachType; // gallery poll externalContent opinion
    public $resource_attachId;
    public $resource_order;


    public function reloations()
    {
        $this->relation('gallery', 'resource_attachId', 'gallery_id', array(
            'resource_attachType' => 'gallery'
        ));
    }

    public function paramGalleryForArticleMag($article_id)
    {
        $this->join('gallery');
        $this->param('resource_baseId', $article_id);
        $this->param('resource_baseType', 'article_mag');
    }

    public function paramGalleryPicForArticleMag($article_id)
    {
        $this->join('gallery');
        $this->join('galleryPic', null, 'gallery');
        $this->param('resource_baseId', $article_id);
        $this->param('resource_baseType', 'article_mag');
    }


    
}