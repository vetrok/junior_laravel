<?php

class WorkersTableSeeder extends Seeder {

    //Workers tree depth
    protected $workersLevels = 5;
    protected $numOfWorkers = 1000;
    protected $biggestSalary = 1000000;

    public function run()
    {
        //Fake workers
        $workers = $this->addFakeWorkers($this->numOfWorkers);
        $chunkSize = ($this->numOfWorkers / $this->workersLevels) + 1;
        $chunks = array_chunk($workers, $chunkSize);
        $workers = [];
        //Array with all depends WORKER->BOSS
        $depend = [];
        $bigBoss = array(
            'id' => 1,
            'name' => 'John Smith',
            'position' => 'big boss',
            'start_day' => 1441786098,
            'salary' => 1000000,
        );
        //Everebody under big boss
        $depend[0] = $bigBoss;
        $depend = $this->createDependencies($depend, $chunks[0]);
        for ($i = 0; $this->workersLevels > $i + 1; $i++ ) {
            //Result - is array with dependencies
            $depend = array_merge($depend, $this->createDependencies($chunks[$i], $chunks[$i + 1]));
            //Also i need to insert user salary + post
            $this->addSalaryRank($chunks[$i], $i);
            $workers = array_merge($workers, $chunks[$i]);
        }
        $this->addSalaryRank($chunks[$i], $i);
        $workers = array_merge($workers, $chunks[$i]);
        array_unshift($workers, $bigBoss);

        //Adding records in DB
        DB::table('dependencies')->delete();
        DB::table('workers')->delete();

        DB::table('workers')->insert($workers);


        DB::table('dependencies')->insert($depend);
    }

    public function getFaker() {
        return Faker\Factory::create();
    }

    /**
     * add Fake Workers
     *
     * @param int $howMany Number of workers
     *
     * @return array
     */
    public function addFakeWorkers($howMany) {
        $result = [];
        for ($i = 0; $howMany >= $i; $i++) {
            $result[$i]['name'] = $this->getFaker()->firstName ." ".  $this->getFaker()->firstName ." ". $this->getFaker()->lastName;
            $result[$i]['id'] = $i + 2;
            //$result[$i]['name'] = 'VASYA';
            $result[$i]['start_day'] = time() - rand(10000, 30000);
        }
        return $result;
    }

    public function addSalaryRank(&$workers, $level = 10) {
        foreach ($workers as $k => $worker) {
            switch ($level) {
                case 0 :
                    $workers[$k]['position'] = 'boss';
                    break;
                case 1 :
                    $workers[$k]['position'] = 'tiny boss';
                    break;
                case 2 :
                    $workers[$k]['position'] = 'worker';
                    break;
                case 3 :
                    $workers[$k]['position'] = 'tiny worker';
                    break;
                case 4 :
                    $workers[$k]['position'] = 'cleaner';
                    break;
                default :
                    $workers[$k]['position'] = 'cleaner assistant';
            }
            $workers[$k]['salary'] = $this->biggestSalary - (rand(100000, 150000) * ($level + 1));
        }
        return $workers;
    }

    /**
     * generate Dependencies by worker ID between two arrays
     *
     * @param array $bosses All bosses
     *
     * @param array $under workers under boss
     *
     * @return array like 'id' => worker_id 'boss_id' => worker_id
     */
    public function createDependencies($bosses, $under) {
        //I will pull out users
        $underCopy = $under;
        $result = [];
        $howManyBosses = count($bosses);
        $operationNumber = 0;
        foreach ($bosses as $boss) {
            if (empty($under)) {
                break;
            }
            //workers counter
            $workerCounter = 0;
            $howManyWorkers = count($under);
            $stop = (int)($howManyWorkers / $howManyBosses) + 1;
            foreach ($under as $k => $worker) {
                if ($stop == $workerCounter) {
                    break;
                }
                $result[$operationNumber]['boss_id'] = $boss['id'];
                $result[$operationNumber]['worker_id'] = $worker['id'];
                //When user added to boss I delete him
                unset($under[$k]);
                $workerCounter++;
                $operationNumber++;
            }
        }
        return $result;
    }
}