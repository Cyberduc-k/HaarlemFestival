<?php
require_once("ServiceUtils.php");
require_once(__DIR__ . "/../DAL/HistoricTourDAO.php");

class HistoricTourService extends ServiceUtils
{
    private HistoricTourDAO $dao;

    public function __construct(){
        $this->dao = new HistoricTourDAO();
    }



}