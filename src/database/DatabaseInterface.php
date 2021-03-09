<?php
namespace Netmatters;

interface DatabaseInterface
{
    public function getPDO(): \PDO;
}
