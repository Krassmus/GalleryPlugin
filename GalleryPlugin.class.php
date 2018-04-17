<?php

class GalleryPlugin extends StudIPPlugin implements SystemPlugin
{
    public function __construct()
    {
        parent::__construct();
        PageLayout::addSqueezePackage('lightbox');
        PageLayout::addScript($this->getPluginURL() . "/assets/gallery.js");
        if (stripos($_SERVER['REQUEST_URI'], "dispatch.php/files") !== false) {
            NotificationCenter::addObserver($this, "addGalleryToProfileSidebar", "SidebarWillRender");
        }
        if (stripos($_SERVER['REQUEST_URI'], "dispatch.php/course/files") !== false) {
            NotificationCenter::addObserver($this, "addGalleryToCourseSidebar", "SidebarWillRender");
        }

    }

    public function addGalleryToProfileSidebar()
    {
        if (strpos($_SERVER['REQUEST_URI'], "dispatch.php/files/flat") !== false) {
            return;
        }
        if (strpos($_SERVER['REQUEST_URI'], "dispatch.php/files/index") !== false) {
            $folder_id = (string) substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], "dispatch.php/files/index") + strlen("dispatch.php/files/index"));
            if ($folder_id[0] === "/") {
                $folder_id = substr($folder_id, 1);
            }
            if (strpos($folder_id, "?") !== false) {
                $folder_id = substr($folder_id, 0, strpos($folder_id, "?"));
            }
            if ($folder_id) {
                $folder = new Folder($folder_id);
            } else {
                $folder = Folder::findTopFolder($GLOBALS['user']->id);
            }
        } else {
            $folder =  Folder::findTopFolder($GLOBALS['user']->id);
        }

        $folder = $folder->getTypedFolder();
        $this->addGalleryToSidebar($folder);
    }

    public function addGalleryToCourseSidebar()
    {
        if (strpos($_SERVER['REQUEST_URI'], "dispatch.php/course/files/flat") !== false) {
            return;
        }
        if (strpos($_SERVER['REQUEST_URI'], "dispatch.php/course/files/index") !== false) {
            $folder_id = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], "dispatch.php/course/files/index") + strlen("dispatch.php/course/files/index"));
            if ($folder_id[0] = "/") {
                $folder_id = substr($folder_id, 1);
            }
            if (strpos($folder_id, "?") !== false) {
                $folder_id = substr($folder_id, 0, strpos($folder_id, "?"));
            }
            $folder = new Folder($folder_id);
        } else {
            $folder =  Folder::findTopFolder(Context::get()->id);
        }

        $folder = $folder->getTypedFolder();
        $this->addGalleryToSidebar($folder);
    }

    public function addGalleryToSidebar($folder)
    {
        $images = array();
        foreach ($folder->getFiles() as $file) {
            if ($file->isImage()) {
                $images[] = $file->getId();
            }
        }
        if (count($images)) {
            $actions = Sidebar::Get()->getWidget("actions");
            $actions->addLink(
                _("Galerie anzeigen"),
                "#gallery",
                Icon::create("file-pic+visibility-visible", "clickable"),
                array(
                    'onclick' => "STUDIP.Gallery.showGallery(); return false;"
                )
            );
        }
    }
}