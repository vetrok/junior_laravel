<table>
<?php
    $counter = 50;
    $str = '';
    $wrap = function ($name) {
        return '<td>' . $name . '</td>';
    };
    foreach ($users as $key => $user) {
        if ($counter % 50) {
            $str .= "<tr><th>ID</tH><th>Name</th><th>Position</th><th>Salary</th><th>Boss ID</th></tr>";
        }
        $str .= "<tr>";
        $str .= $wrap($user['id']);
        $str .= $wrap($user['name']);
        $str .= $wrap($user['position']);
        $str .= $wrap($user['salary']);
        $str .= $wrap($user['boss_id']);
        $str .= "</tr>";
    }
    echo $str;
?>
</table>