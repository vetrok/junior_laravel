<?php
/**
 * Перемешивание предложения по заданному шаблону
 *
 * @author Vitaliy Yatsenko <vetrok87@gmail.com>
 * @version 1.0
 */

/**
 * Перемешивает строку по шаблону, строка должна иметь аказатель на начало шаблона
 * конец и розделитель символы между которым будут случайным образом подставлены,
 * вложенность шаблонов не ограничена - используется рекурсвиный обход
 *
 * @param string $startStr сама строока
 * @param int $offset смещение поиска
 * @param string $startChar символ начала шаблона
 * @param string $endChar символ конца шаблона
 * @param string $delimiter разделитель в шаьлоне
 * @return string
 */
function myShuffle($startStr, $offset = 0, $startChar = '{', $endChar = '}', $delimiter = '|') {
    while (($startPoint = strpos($startStr, $startChar, $offset)) !== false) {
        if (($endPoint = strpos($startStr, $endChar, $startPoint + 1)) !== false) {
            //Если есть второй указатель на начало шаблона, и он начинается раньше чем предыдущий шаблон закончился
            if (($secondStart = strpos($startStr, $startChar, $startPoint + 1)) !== false) {
                if ($secondStart < $endPoint) {
                    $startStr = myShuffle($startStr, $secondStart - 1);
                }
            }
            if (($endPoint = strpos($startStr, $endChar, $startPoint + 1)) === false) {
                //Если нету закрывающего указателя, прекращаю цикл
                break;
            }
            //Я нашол открывающий и закрывающий указатели, теперь обрабатываю то что между ними
            $templateStr = substr($startStr, $startPoint, $endPoint - $startPoint + 1);
            $shuffleStr = substr($startStr, $startPoint + 1, $endPoint - $startPoint - 1);
            $shuffleWords = explode($delimiter, $shuffleStr);
            $newWord = $shuffleWords[rand(0, count($shuffleWords) - 1)];
            $startStr = str_replace($templateStr, $newWord, $startStr);
        }
    }
    return $startStr;
}


$str = "{Пожалуйста|Просто} сделайте так, чтобы это {удивительное|крутое|простое}
        тестовое предложение {изменялось {быстро|мгновенно} случайным образом|менялось каждый раз}.";
echo myShuffle($str);
?>