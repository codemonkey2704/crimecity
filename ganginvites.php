<?phpinclude 'header.php';if ($_GET['accept'] != ""){    $gang_class = new Gang($_GET['accept']);    $checkuser = DB::run("SELECT `username` FROM `ganginvites` WHERE `username`='".$user_class->username."' AND `gangid`='".$_GET['accept']."'");    $username_exist = $checkuser->rowCount();    if ($username_exist != 0){        $result = DB::run("DELETE FROM `ganginvites` WHERE `username`='".$user_class->username."'");        $newsql = DB::run("UPDATE `grpgusers` SET `gang` = '".$gang_class->id."' WHERE `id`= '".$user_class->id."'");        echo Message("You have joined.");    }}if ($_GET['delete'] != ""){    $result = DB::run("DELETE FROM `ganginvites` WHERE `username`='".$user_class->username."' AND `gangid`='".$_GET['delete']."'");    echo Message("You have declined that offer.");}?>    <tr><td class="contenthead">Gang Invitations</td></tr><?php$result = DB::run("SELECT * FROM `ganginvites` WHERE `username` = '$user_class->username'");while ($line = $result->fetch(PDO::FETCH_LAZY)){    $invite_class = New Gang($line['gangid']);    echo "<tr><td class='contentcontent'>".$invite_class->formattedname." - <a href='ganginvites.php?accept=".$invite_class->id."'>Accept</a>- <a href='ganginvites.php?delete=".$invite_class->id."'>Decline</a></td></tr>";}include 'footer.php';?>