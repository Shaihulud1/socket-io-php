<?
if(isset($_POST['method']))
{
    $result = [];
    $mysql = new mysqlHelper;
    switch ($_POST['method']) {
        case 'userJoin':
            if($mysql->isUserExist($_POST['nickname'])){
                $result = ['result' => 'exist'];
            }else{
                $id = $mysql->newUser($_POST['nickname']);
                $result = ['result' => $id];
            }
        break;
        case 'isExistID': 
            if($mysql->isExistUserByID((int)$_POST['id'])){
                $result = ['result' => 'exist'];
            }else{
                $result = ['result' => 'notExist'];
            }
        break;
        case 'getUserDataByID':
            $userData = $mysql->getUserDataByID((int)$_POST['id']);   
            $result = ['result' => $userData ? $userData : 'notExist'];                         
        break;
        case 'sendMessage':
            if($mysql->isExistUserByID((int)$_POST['id'])){
                $this->messData($_POST);
                $result = ['result' => 'success'];
            }else{
                $result = ['result' => 'failed'];
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

    public function newMessage(array $messData)
    {
        $this->conn->query("INSERT INTO mess (userID, message) VALUES (".$messData['userID']."'".$messData['message']."')");
    }

    public function getUserDataByID(int $id)
    {
        $q = $this->conn->query("SELECT * FROM users WHERE id = $id ORDER BY id DESC LIMIT 1");
        if($r = $q->fetch_assoc()){
            return ['id' => $r['id'], 'name' => $r['name']];
        }else{
            return false;
        }
    }

    public function isExistUserByID(int $id)
    {
        $res = $this->conn->query("SELECT COUNT(*) FROM users WHERE id = '$id'");
        $row = $res->fetch_row();
        return $row[0] > 0;        
    }

    public function isUserExist(string $name): bool
    {   
        $res = $this->conn->query("SELECT COUNT(*) FROM users WHERE name = '$name'");
        $row = $res->fetch_row();
        return $row[0] > 0;
    }

    public function newUser(string $name): ?string
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

