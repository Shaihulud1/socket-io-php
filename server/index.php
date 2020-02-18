<?
if(isset($_POST['method']))
{
    $result = [];
    switch ($_POST['method']) {
        case 'userJoin':
            $mysql = new mysqlHelper;
            if($mysql->isUserExist($_POST['nickname'])){
                $result = ['result' => 'exist'];
            }else{
                $id = $mysql->newUser($_POST['nickname']);
                $result = ['result' => $id];
            }
        break;
    }
    print_r(json_encode($result));
    die;
}
class mysqlHelper{
    private $conn;

    function __construct()
    {
        $this->conn = new mysqli("localhost", "siteAdmin", "DafafjarufEE123", "socketYii");
        if(!$this->conn){
            die('error');
        }
    }

    public function isUserExist($name): bool
    {   
        $res = $this->conn->query("SELECT COUNT(*) FROM users WHERE name = '$name'");
        $row = $res->fetch_row();
        return $row[0] > 0;
    }

    public function newUser($name): ?string
    {
        $this->conn->query("INSERT INTO users (name) VALUES ('$name')");
        $q = $this->conn->query("SELECT * FROM users WHERE name = '$name' ORDER BY id DESC LIMIT 1");
        $r = $q->fetch_assoc();
        return $r['id'];
    }
    // CREATE TABLE IF NOT EXISTS `users` (
    //     `id` int(11) NOT NULL AUTO_INCREMENT,
    //     PRIMARY KEY(`id`),
    //     `name` varchar(255) NOT NULL
    // ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
    // CREATE TABLE IF NOT EXISTS `mess` (
    //     `id` int(11) NOT NULL AUTO_INCREMENT,
    //     PRIMARY KEY(`id`),
    //     `userID` int(11) NOT NULL,
    //     `message` varchar(255) NOT NULL
    // ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
}

