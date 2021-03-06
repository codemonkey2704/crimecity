<?php
function Get_ID($username)
{
    $worked = DB::run("SELECT * FROM `grpgusers` WHERE `username` =?", [$username])->fetch();
    return $worked['id'];
}
function Get_Userbar($honorbar, $username)
{
    if (trim($honorbar) != '' ) {
        return '<div class="honorbar"><img src="'.$honorbar.'" style="height:10px" style="width:10px"><span>'.$username.'</span></div>';
    }

    //Or implement a backup userbar.
    return '<span>No userbar for this user</span>';
}

function mrefresh($url, $time = '1')
{
    echo '<meta http-equiv="refresh" content="'.$time.';url='.$url.'">';
}

function car_popup($text, $id)
{
    return "<a href='#' onclick=\"javascript:window.open( 'cardesc.php?id=".$id."', '60', 'left = 20, top = 20, width = 400, height = 400, toolbar = 0, resizable = 0, scrollbars=1' );\">".$text."</a>";
}

function item_popup($text, $id)
{
    return "<a href='#' onclick=\"javascript:window.open( 'description.php?id=".$id."', '60', 'left = 20, top = 20, width = 400, height = 400, toolbar = 0, resizable = 0, scrollbars=1' );\">".$text."</a>";
}

function prettynum($num,$dollar = "0")
{
// Basic send a number or string to this and it will add commas. If you want a dollar sign added to the
// front and it is a number add a 1 for the 2nd variable.
// Example prettynum(123452838,1)  will return $123,452,838 take out the ,1 and it looses the dollar sign.
    $out=strrev( (string)preg_replace( '/(\d{3})(?=\d)(?!\d*\.)/', '$1,' , strrev( $num ) ) );
    if ($dollar && is_numeric($num)) {
        $out= "$".$out;
    }
    return $out;
}

function Check_Item($itemid, $userid)
{
    $result = DB::run("SELECT * FROM `inventory` WHERE `userid`='$userid' AND `itemid`='$itemid'");
    $worked = $result->fetch(PDO::FETCH_ASSOC);
    return ($worked['quantity'] > 0) ? $worked['quantity'] : 0;
}

function Check_Land($city, $userid)
{
    $result = DB::run("SELECT * FROM `land` WHERE `userid`='".$userid."' AND `city`='".$city."'");
    $worked = $result->fetch(PDO::FETCH_ASSOC);
    return ($worked['quantity'] > 0) ? $worked['quantity'] : 0;
}

//userid	companyid	howmany
function Give_Share($stock, $userid, $quantity = "1")
{
    $result = DB::run("SELECT * FROM `shares` WHERE `userid`='".$userid."' AND `companyid`='".$stock."'");
    $worked = $result->fetch(PDO::FETCH_ASSOC);
    $itemexist = $result->rowCount();

    if ($itemexist == 0) {
        $result= DB::run("INSERT INTO `shares` (`companyid`, `userid`, `amount`) VALUES ('$stock', '$userid', '$quantity')");
    } else {
        $quantity = $quantity + $worked['amount'];
        $result = DB::run("UPDATE `shares` SET `amount` = '".$quantity."' WHERE `userid`='$userid' AND `companyid`='$stock'");
    }
}

function Take_Share($stock, $userid, $quantity = "1")
{
    $result = DB::run("SELECT * FROM `shares` WHERE `userid`='".$userid."' AND `companyid`='".$stock."'");
    $worked = $result->fetch(PDO::FETCH_ASSOC);
    $itemexist = $result->rowCount();

    if ($itemexist != 0) {
        $quantity = $worked['amount'] - $quantity;
        if ($quantity > 0) {
            $result = DB::run("UPDATE `shares` SET `amount` = '".$quantity."' WHERE `userid`='$userid' AND `companyid`='$stock'");
        } else {
            $result = DB::run("DELETE FROM `shares` WHERE `userid`='$userid' AND `companyid`='$stock'");
        }
    }
}

function Check_Share($stock, $userid)
{
    $result = DB::run("SELECT * FROM `shares` WHERE `userid`='".$userid."' AND `companyid`='".$stock."'");
    $worked = $result->fetch(PDO::FETCH_ASSOC);

    return ($worked['amount'] > 0) ? $worked['amount'] : 0;
}

function Give_Land($city, $userid, $quantity = "1")
{
    $result = DB::run("SELECT * FROM `land` WHERE `userid`='".$userid."' AND `city`='".$city."'");
    $worked = $result->fetch(PDO::FETCH_ASSOC);
    $itemexist = $result->rowCount();

    if ($itemexist == 0) {
        $result= DB::run("INSERT INTO `land` (`city`, `userid`, `amount`)"."VALUES ('$city', '$userid', '$quantity')");
    } else {
        $quantity = $quantity + $worked['amount'];
        $result = DB::run("UPDATE `land` SET `amount` = '".$quantity."' WHERE `userid`='$userid' AND `city`='$city'");
    }
}

function Take_Land($city, $userid, $quantity = "1")
{
    $result = DB::run("SELECT * FROM `land` WHERE `userid`='".$userid."' AND `city`='".$city."'");
    $worked = $result->fetch(PDO::FETCH_ASSOC);
    $itemexist = $result->rowCount();

    if ($itemexist != 0) {
        $quantity = $worked['amount'] - $quantity;
        if ($quantity > 0) {
            $result = DB::run("UPDATE `land` SET `amount` = '".$quantity."' WHERE `userid`='$userid' AND `city`='$city'");
        } else {
            $result = DB::run("DELETE FROM `land` WHERE `userid`='$userid' AND `city`='$city'");
        }
    }
}

function Give_Item($itemid, $userid, $quantity = "1")
{
    $result = DB::run("SELECT * FROM `inventory` WHERE `userid`='$userid' AND `itemid`='$itemid'");
    $worked = $result->fetch();
    $itemexist = $result->rowCount();

    if ($itemexist == 0) {
        $result= DB::run("INSERT INTO `inventory` (`itemid`, `userid`, `quantity`)"."VALUES ('$itemid', '$userid', '$quantity')");
    } else {
        $quantity = $quantity + $worked['quantity'];
        $result = DB::run("UPDATE `inventory` SET `quantity` = '".$quantity."' WHERE `userid`='$userid' AND `itemid`='$itemid'");
    }
}

function Take_Item($itemid, $userid, $quantity = "1")
{
    $result = DB::run("SELECT * FROM `inventory` WHERE `userid`='$userid' AND `itemid`='$itemid'");
    $worked = $result->fetch();
    $itemexist = $result->rowCount();

    if ($itemexist != 0) {
        $quantity = $worked['quantity'] - $quantity;
        if ($quantity > 0) {
            $result = DB::run("UPDATE `inventory` SET `quantity` = '".$quantity."' WHERE `userid`='$userid' AND `itemid`='$itemid'");
        } else {
            $result = DB::run("DELETE FROM `inventory` WHERE `userid`='$userid' AND `itemid`='$itemid'");
        }
    }
}

function Message($text)
{
    return '<tr><td class="contenthead">.: Important Message</td></tr><tr><td class="contentcontent">'.$text.'</td></tr>';
}

function Send_Event ($id, $text)
{
    $timesent = time();
    $result= DB::run("INSERT INTO `events` (`to`, `timesent`, `text`) VALUES ('$id', '$timesent', '$text')");
}

function Is_User_Banned($id)
{
    $result = DB::run("SELECT * FROM `bans` WHERE `id`='$id'");
    return $result->rowCount();
}

function Why_Is_User_Banned($id)
{
    $result = DB::run("SELECT * FROM `bans` WHERE `id`='$id'");
    $worked = $result->fetch();
    return $worked['reason'];
}

function Radio_Status()
{
    $result = DB::run("SELECT * FROM `serverconfig`");
    $worked = $result->fetch();
    return $worked['radio'];
}

function howlongago($ts)
{
    $ts = time() - $ts;
    if ($ts < 1)
        return " NOW";
	elseif ($ts == 1)
        return $ts." second";
	elseif ($ts < 60)
        return $ts." seconds";
	elseif ($ts < 120)
        return "1 minute";
	elseif ($ts < 60 * 60)
        return floor($ts / 60)." minutes";
	elseif ($ts < 60 * 60 * 2)
        return "1 hour";
	elseif ($ts < 60 * 60 * 24)
        return floor($ts / (60 * 60))." hours";
	elseif ($ts < 60 * 60 * 24 * 2)
        return "1 day";
	elseif ($ts < (60 * 60 * 24 * 7))
        return floor($ts / (60 * 60 * 24))." days";
	elseif ($ts < 60 * 60 * 24 * 30.5)
        return floor($ts / (60 * 60 * 24 * 7))." weeks";
	elseif ($ts < 60 * 60 * 24 * 365)
        return floor($ts / (60 * 60 * 24 * 30.5))." months";
    else
        return floor($ts / (60 * 60 * 24 * 365))." years";
};

function howlongtil($ts) {
    $ts = $ts - time();
    if ($ts < 1)
        return " NOW";
    elseif ($ts == 1)
        return $ts." second";
    elseif ($ts < 60)
        return $ts." seconds";
    elseif ($ts < 120)
        return "1 minute";
    elseif ($ts < 60 * 60)
        return floor($ts / 60)." minutes";
    elseif ($ts < 60 * 60 * 2)
        return "1 hour";
    elseif ($ts < 60 * 60 * 24)
        return floor($ts / (60 * 60))." hours";
    elseif ($ts < 60 * 60 * 24 * 2)
        return "1 day";
    elseif ($ts < (60 * 60 * 24 * 7))
        return floor($ts / (60 * 60 * 24))." days";
    elseif ($ts < 60 * 60 * 24 * 30.5)
        return floor($ts / (60 * 60 * 24 * 7))." weeks";
    elseif ($ts < 60 * 60 * 24 * 365)
        return floor($ts / (60 * 60 * 24 * 30.5))." months";
    else
        return floor($ts / (60 * 60 * 24 * 365))." years";
};

function experience($L)
{
    $a = 0;
    $end = 0;

    for($x = 1; $x < $L; $x++) {
        $a += floor($x + 1500 * pow(4, ($x / 7)));
    }

    return floor($a/4);
}

function Get_The_Level($exp)
{
    $a = 0;
    $end = 0;

    for($x = 1; $x < 100; $x++) {
        $a += floor($x + 1500 * pow(4, ($x / 7)));
        if ($exp >= floor($a / 4)) {

        } else {
            return $x;
        }
    }
}

class User_Stats
{
    public int $playersloggedin = 0;
    public int $playersonlineinlastday = 0;
    public int $playerstotal;

    function __construct($wutever)
    {
        $result = DB::run("SELECT * FROM `grpgusers` ORDER BY `username` ASC");

        while($line = $result->fetch(PDO::FETCH_LAZY)) {

            $secondsago = time() - $line['lastactive'];

            if ($secondsago <= 300) {
                $this->playersloggedin++;
            }
        }

        $result3 = DB::run("SELECT * FROM `grpgusers` ORDER BY `username` ASC");

        while($line3 = $result3->fetch(PDO::FETCH_ASSOC)) {

            $secondsago = time() - $line3['lastactive'];

            if ($secondsago <= 86400) {
                $this->playersonlineinlastday++;
            }
        }

        $result2 = DB::run("SELECT * FROM `grpgusers`");
        $this->playerstotal = $result2->rowCount();
    }
}

class Gang
{
    public int $id;
    public int $members;
    public string $name;
    public string $formattedname;
    public ?string $description;
    public string $leader;
    public string $tag;
    public int $exp;
    public int $level;
    public int $vault;

    function __construct($id)
    {
        $result = DB::run("SELECT * FROM `gangs` WHERE `id`='$id'");
        $worked = $result->fetch();
        $gangcheck = DB::run("SELECT * FROM `grpgusers` WHERE `gang`= ?",[$id]);

        $this->id = $worked['id'];
        $this->members = $gangcheck->rowCount();
        $this->name = $worked['name'];
        $this->formattedname = "<a href='viewgang.php?id=".$worked['id']."'>".$worked['name']."</a>";
        $this->description = $worked['description'];
        $this->leader = $worked['leader'];
        $this->tag = $worked['tag'];
        $this->exp = $worked['exp'];
        $this->level = Get_The_Level($this->exp);
        $this->vault = $worked['vault'];
    }

}

class User
{
    public int $id;
    public string $username;
    public string $formattedname = '';

    public int $eqweapon;
    public string $weaponname = "fists";
    public string $weaponimg;

    public int $eqarmor;
    public string $armorname;
    public string $armorimg;

    public int $weaponoffense = 0;
    public int $armordefense = 0;

    public int $moddedstrength;
    public int $moddeddefense;

    public string $ip;
    public int $style;

    public int $speedbonus;

    public int $marijuana;
    public int $potseeds;
    public int $cocaine;
    public int $nodoze;
    public int $genericsteroids;
    public int $hookers;

    public int $level;

    public int $exp;
    public int $maxexp;
    public int $exppercent;
    public string $formattedexp;

    public int $money;
    public int $bank;
    public int $whichbank;

    public int $hp;
    public int $maxhp;
    public int $hppercent;
    public string $formattedhp;

    public int $energy;
    public int $maxenergy;
    public int $energypercent;
    public string $formattedenergy;

    public int $nerve;
    public int $maxnerve;
    public int $nervepercent;
    public string $formattednerve;

    public int $workexp;

    public int $strength;
    public int $defense;
    public int $speed;

    public int $totalattrib;

    public int $battlewon;
    public int $battlelost;
    public int $battletotal;
    public int $battlemoney;
    public int $crimesucceeded;
    public int $crimefailed;
    public int $crimetotal;
    public int $crimemoney;

    public int $lastactive;
    public string $age;
    public string $formattedlastactive;

    public int $points;
    public int $credits;
    public int $rmdays;
    public int $signuptime;

    public int $house;
    public string $housename;
    public int $houseawake;

    public int $awake;
    public int $maxawake;
    public int $awakepercent;
    public string $formattedawake;

    public string $email;
    public int $admin;
    public ?string $quote;
    public ?string $avatar;

    public int $gang;
    public ?string $gangname;
    public ?string $gangleader;
    public string $gangtag;
    public string $gangdescription;
    public string $formattedgang;

    public int $city;
    public string $cityname;

    public int $jail;
    public ?int $job;
    public int $hospital;
    public int $searchdowntown;

    public string $type;

    function __construct($id)
    {
        $worked = DB::run("SELECT * FROM `grpgusers` WHERE `id`=?",[$id])->fetch();
        $worked2 = DB::run("SELECT * FROM `gangs` WHERE `id`= ?",[$worked['gang']])->fetch();
        $worked3 = DB::run("SELECT * FROM `cities` WHERE `id`= ?",[$worked['city']])->fetch();
        $worked4 = DB::run("SELECT * FROM `houses` WHERE `id`= ? ",[$worked['house']])->fetch();
        $checkcocaine = DB::run("SELECT * FROM `effects` WHERE `userid`='".$id."' AND `effect`='Cocaine'");

        $cocaine = $checkcocaine->rowCount();

        if ($worked['eqweapon'] != 0) {
            $worked6 = DB::run("SELECT * FROM `items` WHERE `id`= ?",[$worked['eqweapon']])->fetch();
            $this->eqweapon = $worked6['id'];
            $this->weaponoffense = $worked6['offense'];
            $this->weaponname = $worked6['itemname'];
            $this->weaponimg = $worked6['image'];
        }

        if ($worked['eqarmor'] != 0) {
            $result6 = DB::run("SELECT * FROM `items` WHERE `id`='".$worked['eqarmor']."' LIMIT 1");
            $worked6 = $result6->fetch();
            $this->eqarmor = $worked6['id'];
            $this->armordefense = $worked6['defense'];
            $this->armorname = $worked6['itemname'];
            $this->armorimg = $worked6['image'];
        }

        $this->moddedstrength = $worked['strength'] * ($this->weaponoffense * .01 + 1);
        $this->moddeddefense = $worked['defense'] * ($this->armordefense * .01 + 1);

        $this->id = $worked['id'];
        $this->ip = $worked['ip'];
        $this->style = ($worked['style'] > 0) ? $worked['style'] : "1";
        $this->speedbonus = ($cocaine > 0) ? (floor($worked['speed'] * .30)) : 0;
        $this->username = $worked['username'];

        $this->marijuana = $worked['marijuana'];
        $this->potseeds = $worked['potseeds'];
        $this->cocaine = $worked['cocaine'];
        $this->nodoze = $worked['nodoze'];
        $this->genericsteroids = $worked['genericsteroids'];
        $this->hookers = $worked['hookers'];

        $this->exp = $worked['exp'];
        $this->level = Get_The_Level($this->exp);
        $this->maxexp = experience($this->level +1);
        $this->exppercent = ($this->exp == 0) ? 0 : floor(($this->exp / $this->maxexp) * 100);
        $this->formattedexp = $this->exp." / ".$this->maxexp." [".$this->exppercent."%]";

        $this->money = $worked['money'];
        $this->bank = $worked['bank'];
        $this->whichbank = $worked['whichbank'];

        $this->hp = $worked['hp'];
        $this->maxhp = $this->level * 50;
        $this->hppercent = floor(($this->hp / $this->maxhp) * 100);
        $this->formattedhp = $this->hp." / ".$this->maxhp." [".$this->hppercent."%]";

        $this->energy = $worked['energy'];
        $this->maxenergy = 9 + $this->level;
        $this->energypercent = floor(($this->energy / $this->maxenergy) * 100);
        $this->formattedenergy = $this->energy." / ".$this->maxenergy." [".$this->energypercent."%]";

        $this->nerve = $worked['nerve'];
        $this->maxnerve = 4 + $this->level;
        $this->nervepercent = floor(($this->nerve / $this->maxnerve) * 100);
        $this->formattednerve = $this->nerve." / ".$this->maxnerve." [".$this->nervepercent."%]";

        $this->workexp = $worked['workexp'];

        $this->strength = $worked['strength'];
        $this->defense = $worked['defense'];
        $this->speed = $worked['speed'] + $this->speedbonus;

        $this->totalattrib = $this->speed + $this->strength + $this->defense;

        $this->battlewon = $worked['battlewon'];
        $this->battlelost = $worked['battlelost'];
        $this->battletotal = $this->battlewon + $this->battlelost;
        $this->battlemoney = $worked['battlemoney'];
        $this->crimesucceeded = $worked['crimesucceeded'];
        $this->crimefailed = $worked['crimefailed'];
        $this->crimetotal = $this->crimesucceeded + $this->crimefailed;
        $this->crimemoney = $worked['crimemoney'];

        $this->lastactive = $worked['lastactive'];
        $this->age = howlongago($worked['signuptime']);

        $this->formattedlastactive = howlongago($this->lastactive) . " ago";

        $this->points = $worked['points'];
        $this->credits = $worked['credits'];
        $this->rmdays = $worked['rmdays'];
        $this->signuptime = $worked['signuptime'];

        $this->house = $worked['house'];
        $this->housename = $worked4 ? $worked4['name'] : 'Homeless';
        $this->houseawake = $worked4 ? $worked4['awake'] : 100;

        $this->awake = $worked['awake'];
        $this->maxawake = $this->houseawake;
        $this->awakepercent = floor(($this->awake / $this->maxawake) * 100);
        $this->formattedawake = $this->awake." / ".$this->maxawake." [".$this->awakepercent."%]";

        $this->email = $worked['email'];
        $this->admin = $worked['admin'];
        $this->quote = $worked['quote'];
        $this->avatar = $worked['avatar'];
        $this->gang = $worked['gang'];

        if ($worked2) {
            $this->gangname = $worked2['name'];
            $this->gangleader = $worked2['leader'];
            $this->gangtag = $worked2['tag'];
            $this->gangdescription = $worked2['description'];
            $this->formattedgang = "<a href='viewgang.php?id=".$this->gang."'>".$this->gangname."</a>";
        }

        $this->city = $worked['city'];
        $this->cityname = $worked3['name'];
        $this->jail = $worked['jail'];
        $this->job = $worked['job'];
        $this->hospital = $worked['hospital'];

        $this->searchdowntown = $worked['searchdowntown'];

        if ($this->gang != 0) {
            $this->formattedname .= "<a href='viewgang.php?id=".$this->gang."'";
            $this->formattedname .= $this->gangleader == $this->username ? " title='Gang Leader'>[<b>{$this->gangtag}</b>]</a>" : ">[{$this->gangtag}]</a>";
        }

        $this->type = "Regular Mobster";
        $whichfont = null;

        if ($this->rmdays != 0) {
            $this->type = "Respected Mobster";
            $whichfont = "blue";
        }

        if ($this->admin == 1) {

            $this->type = "Admin";
            $whichfont = "red";

        }

        if ($this->admin == 2) {
            $this->type = "Staff";
        }

        if ($this->admin == 3) {
            $this->type = "Pre ent";
            $whichfont = "purple";
        }

        if ($this->admin == 4) {
            $this->type = "Congress";
            $whichfont = "yellow";
        }

        if ($this->rmdays > 0){
            $this->formattedname .= "<b><a title='Respected Mobster [".$this->rmdays." RM Days Left]' href='profiles.php?id=".$this->id."'><font color = '".$whichfont."'>".$this->username."</a></font></b>";
        } elseif ($this->admin != 0) {
            $this->formattedname .= "<b><a href='profiles.php?id=".$this->id."'><font color = '".$whichfont."'>".$this->username."</a></font></b>";
        } else {
            $this->formattedname .= "<a href='profiles.php?id=".$this->id."'><font color = '".$whichfont."'>".$this->username."</a></font>";
        }

        if (time() - $this->lastactive < 300) {
            $this->formattedonline= "<font style='color:green;padding:2px;font-weight:bold;'>[online]</font>";
        } else {
            $this->formattedonline= "<font style='color:red;padding:2px;font-weight:bold;'>[offline]</font>";
        }

    }

}

?>

