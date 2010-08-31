<?php
class Model_LotacaoDao extends BaseDao
{
    protected $_name = 'lotacao';
    protected $_primary = 'id';
    protected $_rowClass = 'Lotacao';
    protected $_orderField = 'sigla';
}