<?php
/*
 * @author Enes Gür <mail@enesgur.com.tr>
 * @version 1.0
 * @copyright GPL License
 * @example $cronjob->defineCron($param1,$param2);
 * @param $param1 string function name.
 * @param $param2 string second range.
*/
class cronjob{
    
    public $pdo;
    public $functions;
    public function __construct($database,$id,$pw){
        if(!isset($database) || !isset($id) || !isset($pw)){
            echo "Database Connect Error";
            exit();
        }else{
            try{
                $pdo = new PDO("mysql:dbname={$database};host=localhost",$id,$pw);
                $this->pdo = $pdo;
                self::existstable($database);
                return $this;
            }catch(Exception $e){
                echo $e->getMessage();
            }
        }
    }

    private function existstable($database){
        $pdo = $this->pdo;
        $tables = $pdo->query("SHOW TABLES FROM {$database}")->fetchAll();
        if(in_array("cron", $tables[0])){
            $this->functions = $pdo->query("SELECT * FROM cron")->fetchAll();
            return $this;
        }
        else{
            try{
                $pdo->query("CREATE TABLE cron(id int NOT NULL AUTO_INCREMENT PRIMARY KEY, function_name varchar(20), interval_time int, next_run int)");
            }catch(Exception $e){
                echo $e->getMessage();
            }
        }
    }

    public function defineCron($functionName,$intervalTime){
        if(!function_exists($functionName))
            echo "Function not found.";
        else{
            foreach ($this->functions as $key => $value) {
                if(in_array($functionName, $value))
                    $count++;
                else
                    false;
            }
            if(!isset($count)){
                try{
                    $nextRun = time() + $intervalTime;
                    $this->pdo->query("INSERT INTO cron (function_name,interval_time,next_run) values ('{$functionName}','{$intervalTime}','{$nextRun}')");
                }catch(Exception $e){
                    echo $e->getMessage();
                    }
            }else{
                self::cronUpdate($functionName,$intervalTime);
            }
        }
    }

    private function cronUpdate($functionName,$intervalTime){
        foreach ($this->functions as $key => $value) {
            if(in_array($functionName, $value)){
                $cronArray = $value;
                break;
            }
        }
        if(isset($cronArray)){
            if($cronArray['interval_time'] == $intervalTime)
                true;
            else{
                 $nextRun = time() + $intervalTime;
                 try{
                    $cronID = $cronArray['id'];
                    $this->pdo->query("UPDATE cron SET interval_time = {$intervalTime}, next_run = {$nextRun} WHERE id = {$cronID}");
                 }catch(Exception $e){
                    echo $e->getMessage();
                 }
            }
        }
        self::cronRun();
    }

    private function cronRun(){
        $time = time();
        $functions = $this->pdo->query("SELECT * FROM cron WHERE next_run <= {$time}")->fetchAll();
        foreach ($functions as $key => $value) {
            if(function_exists($value['function_name'])){
                call_user_func($value['function_name']);
                self::cronNextRun($value['function_name'],$value['interval_time'],$value['next_run'],$value['id']);
            }else{
                continue;
            }
        }
    }

    private function cronNextRun($functionname,$intervaltime,$nextrun,$id){
        $nextrun = time() + $intervaltime;
        $this->pdo->query("UPDATE cron SET next_run = {$nextrun} WHERE id = {$id}");
    }
}