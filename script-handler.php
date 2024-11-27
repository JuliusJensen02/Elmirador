<?php
foreach(glob(get_stylesheet_directory()."/scripts/*.php") as $file){
    require_once $file;
}