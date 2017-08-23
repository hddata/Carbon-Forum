<?php
//Original Edition
//
/*
<?php
require(LanguagePath . 'favorite_users.php');
Auth(1);
$Page = intval(Request('Get', 'page'));
if ($Page < 0 || $Page == 1) 
    Redirect('users/following');
if ($Page == 0)
    $Page = 1;
$UsersFollowing = array();
$UsersFollowing = $DB->query('SELECT * FROM ' . PREFIX . 'favorites force index(UsersFavorites) 
        WHERE UserID = ? and Type = 3',
    array(
        $CurUserID
    )
);
$PostsArray     = array();
if ($UsersFollowing)
    $PostsArray = $DB->query('SELECT * FROM ' . PREFIX . 'posts force index(UserPosts) 
            WHERE UserName in (?) and IsDel = 0 
            ORDER BY PostTime DESC 
            LIMIT ' . ($Page - 1) * $Config['PostsPerPage'] . ', ' . ($Config['PostsPerPage'] + 1),
        ArrayColumn($UsersFollowing, 'Title')
    );
$DB->CloseConnection();

if (count($PostsArray) > $Config['PostsPerPage']) {
    $IsLastPage = false;
    array_pop($PostsArray);
} else {
    $IsLastPage = true;
}

$PageTitle = $Lang['My_Following_Users'];
$PageTitle .= $Page > 1 ? ' Page' . $Page : '';
$ContentFile = $TemplatePath . 'favorite_users.php';
include($TemplatePath . 'layout.php');
*/

require(LanguagePath . 'favorite_users.php');
Auth(1);
$Page = intval(Request('Get', 'page'));
if ($Page < 0 || $Page == 1) 
    Redirect('users/following');
if ($Page == 0)
    $Page = 1;
$UsersFollowing = array();
$UsersFollowing = $DB->query('SELECT * FROM ' . PREFIX . 'favorites force index(UsersFavorites) 
        Where UserID=? and Type=3', 
    array(
            $CurUserID
    )
);
$PostsArray     = array();
//更改获取的对应数据，将取IsDel改为IsTopic，使“我的好友”部分只看到好友发的帖子
if ($UsersFollowing)
    $PostsArray = $DB->query('SELECT * FROM ' . PREFIX . 'posts force index(UserPosts) 
        Where UserName in (?) and IsTopic=1 
        ORDER BY PostTime DESC 
        LIMIT ' . ($Page - 1) * $Config['PostsPerPage'] . ',' . ($Config['PostsPerPage'] + 1), 
        ArrayColumn($UsersFollowing, 'Title')
    );
$DB->CloseConnection();
if (count($PostsArray) > $Config['PostsPerPage']) {
    $IsLastPage = false;
    array_pop($PostsArray);
} else {
    $IsLastPage = true;
}
$PageTitle = $Lang['My_Following_Users'];
$PageTitle .= $Page > 1 ? ' Page' . $Page : '';
$ContentFile = $TemplatePath . 'favorite_users.php';
include($TemplatePath . 'layout.php');