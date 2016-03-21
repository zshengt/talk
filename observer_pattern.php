<?php
    interface subject{

        public function registerServer($server);

        public function removeServer($server);

        public function notifyServer();

    }

    interface server{

        public function update($args1, $args2);

    }

    class writeLog implements server{

        public function update($money, $time){
            echo mb_convert_encoding("写日志:".$time."花了".$money."钱\n", "GBK", "UTF-8");
        }

    }

    class sendSms implements server{

        public function update($money, $time){
            echo mb_convert_encoding("发短信:".$time."花了".$money."钱\n", "GBK", "UTF-8");
        }

    }

    class giveScore implements server{

        public function update($money, $time){
            echo mb_convert_encoding("送积分:".$time."花了".$money."钱,赠送".$money."积分\n", "GBK", "UTF-8");
        }

    }

    class buyTicket implements subject{
        private $servers = array();
        private $money;
        private $time;
        public function registerServer($server){
            array_push($this->servers, $server);
        }

        public function removeServer($server){
            $_servers = $this->servers;
            foreach ($_servers as $key => $val) {
               if ($_servers[$key] == $server) {
                   unset($_servers[$key]);
                   $this->servers = $_servers;
               }
            }
        }

        public function notifyServer(){
            $_servers = $this->servers;
            foreach ($_servers as $key => $val) {
               $_servers[$key]->update($this->money, $this->time);
            }
        }

        public function setBuyTicket($buyTicket, $time){
            $this->money = $buyTicket;
            $this->time = $time;
            $this->notifyServer();
        }
    }

    $bt = new buyTicket();
    $wl = new writeLog();
    $ss = new sendSms();
    $gs = new giveScore();

    $bt->registerServer($wl);
    $bt->registerServer($ss);
    $bt->registerServer($gs);
    $bt->setBuyTicket(100, "2016年1月1日");
    $bt->setBuyTicket(200, "2016年1月2日");
?>