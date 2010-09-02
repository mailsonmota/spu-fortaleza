<?php
class Model_AssuntoDao extends BaseDao
{
    protected $_name = 'assunto';
    protected $_primary = 'id';
    protected $_rowClass = 'TipoAssunto';
    protected $_orderField = 'nome';
}