<?php
interface BaseCrudControllerInterface
{
    public function getEntity();
    public function indexAction();
    public function pesquisarAction();
    public function editarAction();
    public function inserirAction();
    public function excluirAction();
}