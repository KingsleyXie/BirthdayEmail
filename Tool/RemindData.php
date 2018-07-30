<?php
namespace Tool;

class RemindData
{
    private $con, $dep_id, $sql;

    private function parseData($sql) {
        $stmt = $this->con->prepare($sql);
        $stmt->execute([$this->dep_id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function __construct() {
        $conf = Config::$database;

        $host = $conf['host']; $dbname = $conf['database'];
        $username = $conf['username']; $password = $conf['password'];

        $this->con = new \PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $this->sql = Config::$remindSQL;
    }

    public function setDepId($dep_id) {
        $this->dep_id = $dep_id;
    }

    // Name of department
    public function getDepartment() {
        return $this->parseData($this->sql['dep'])[0]['dep'];
    }

    // Name and email address of ministers
    public function getMinisters() {
        return $this->parseData($this->sql['min']);
    }

    // Member list that is celebrating birthday this month
    public function getMembers() {
        return $this->parseData($this->sql['mem']);
    }

    // Name list that will be emailed
    public function getNames() {
        $stmt = $this->con->prepare($this->sql['nam']);
        $stmt->execute([$this->dep_id]);
        return implode('、', $stmt->fetchAll(\PDO::FETCH_COLUMN));
    }
}
