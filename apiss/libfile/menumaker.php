<?php

namespace kring\core;

class menumaker {

    public static function menu_item($menuurl, $menuname, $menumaticon = "keyboard_double_arrow_right", $title = null) {
        $kring = new \kring\core\kring();
        $insert = $title ? "title=\"{$title}\" " : null;
        return <<<EOT
        <li class="nav-item">
            <a href="{$kring->coreconf('baseurl')}/{$menuurl}" class="nav-link" {$insert}>
                <span class="material-icons">{$menumaticon}</span>
                    <p>{$menuname}</p>
            </a>
        </li>
EOT;
    }

    public static function groupmenu($groupname, $menuitem, $icon = "keyboard_double_arrow_right") {
        return <<<EOOT
    <li class="nav-item">
    <a href="#" class="nav-link">
        <span class="material-icons">{$icon}</span>
        <p>
            {$groupname}
            <i class="fa fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
         {$menuitem}   
    </ul>
    EOOT;
    }

}
