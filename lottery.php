<?php
include 'header.php';

if ($_GET['buy'] == "ticket"){
    $checklotto = DB::run("SELECT * FROM `lottery` WHERE `userid` = '".$user_class->id."'");
    $numlotto = $checklotto->rowCount();

    if ($numlotto >= 5) {
        $error = "You have already bought 5 tickets today.";
    }
    if ($user_class->money < 1000){
        $error = "You don't have enough money to buy any tickets.";
    }
    if ($error == "") {
        $newmoney = $user_class->money - 1000;
        $result= DB::run("INSERT INTO `lottery` (userid)"."VALUES ('$user_class->id')");
        $result = DB::run("UPDATE `grpgusers` SET `money` = '".$newmoney."' WHERE `id`='".$user_class->id."'");
        echo Message("You have bought a lottery ticket.");
    } else {
        echo Message($error);
    }
}
?>
    <tr><td class="contenthead">Daily Lottery</td></tr>
    <tr><td class="contentcontent">
            Do you want to buy a ticket for the daily lottery? You can buy up to 5 tickets a day for $1000 a ticket. The more people that enter, the more that the winner will win. If your ticket is drawn at the end of the day, you win 75% of the ticket revenue!
            <br><br><a href="lottery.php?buy=ticket">Buy Ticket</a>
        </td></tr>
    <tr><td class="contentcontent">
<?php
$checklotto = DB::run("SELECT * FROM `lottery`");
$numlotto = $checklotto->rowCount();
$amountlotto = $numlotto * 750;
echo "There have been ".$numlotto." Lotto Tickets bought today.<br>";
echo "Lotto is currently worth $".$amountlotto;
echo '</td></tr>';
include 'footer.php';
?>