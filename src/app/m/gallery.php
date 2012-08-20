<?php

class m_gallery extends f_m
{

    public $gallery_id;

    public function relations()
    {
        $this->relation('article_mag', 'gallery_id', 'resource_attachId',
                array('resource_attachType' => 'gallery'));
    }

}
