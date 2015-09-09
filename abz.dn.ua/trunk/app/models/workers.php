<?php
/**
 * Model which works with users
 */
class Workers {
    public function getAllWorkersAsList() {
        $result = $this->getAllWorkersAsArray();
        $result = $this->getUnderAsList($result, 1);
        return $result;
    }

    public function getAllWorkersAsArray() {
        return DB::table('workers')
            ->leftJoin('dependencies', 'workers.id', '=', 'dependencies.worker_id')
            ->select('workers.id', 'workers.name', 'workers.position', 'workers.salary', 'workers.start_day', 'dependencies.boss_id')
            ->get();
    }


    /**
     *
     * Function which sorting users
     *
     *@returns array
     */
    public function getUnder($arr, $userIndex) {
        $i = 0;
        foreach ($arr as $key => $user) {
            $under = array_search($userIndex, $user);
            if ($under == 'boss_id') {
                //Deleting used Users, to reduce load on processor
                unset($arr[$key]);
                $result[$i]['user'] = $user;
                $result[$i]['under'] = $this->getUnder($arr, $user['id']);
                $i++;
            }
        }
        return isset($result) ? $result : null;
    }

    /**
     *
     * Function which sorting users
     *
     * @param array $users Unsorted array
     * @param int $userIndex Start User index
     *
     *@returns array
     */
    public function getUnderAsList($users, $userIndex) {
        $i = 0;
        $str = '';
        foreach ($users as $key => $user) {

            $under = array_search($userIndex, $user);
            if ($under == 'boss_id' or $under == 'id') {
                //Deleting used Users, to reduce load on processor
                unset($users[$key]);
                $str .= "<ul><li>Name: {$user['name']}</li>";
                $underBoss = $this->getUnderAsList($users, $user['id']);
                if ($underBoss) {
                    $str .= "<li>Under: ". $underBoss ."</li>";
                }
                $str .= "</ul>";
                $i++;
            }
        }
        return isset($str) ? $str : null;
    }
}