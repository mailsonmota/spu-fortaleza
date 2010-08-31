<?php
interface DataTableAdapterInterface
{
    public function getPostData();
    public function hasPostData();
    public function getCurrentUrl();
    public function getParameters();
    public function getParameter($name);
    public function makeUrl($url, array $parameters);
    public function getDefaultLinkColumnUrl();
}
?>