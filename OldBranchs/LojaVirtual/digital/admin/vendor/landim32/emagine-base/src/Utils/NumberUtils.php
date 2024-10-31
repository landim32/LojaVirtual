<?php
namespace Emagine\Base\Utils;


class NumberUtils {

    const MIN = 0.01;
    const MAX = 2147483647.99;
    const MOEDA = " real ";
    const MOEDAS = " reais ";
    const CENTAVO = " centavo ";
    const CENTAVOS = " centavos ";

    private static $unidades = array("um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove", "dez", "onze", "doze",
        "treze", "quatorze", "quinze", "dezesseis", "dezessete", "dezoito", "dezenove");
    private static $dezenas = array("dez", "vinte", "trinta", "quarenta", "cinqüenta", "sessenta", "setenta", "oitenta", "noventa");
    private static $centenas = array("cem", "duzentos", "trezentos", "quatrocentos", "quinhentos",
        "seiscentos", "setecentos", "oitocentos", "novecentos");
    private static $milhares = array(
        array("text" => "mil", "start" => 1000, "end" => 999999, "div" => 1000),
        array("text" => "milhão", "start" => 1000000, "end" => 1999999, "div" => 1000000),
        array("text" => "milhões", "start" => 2000000, "end" => 999999999, "div" => 1000000),
        array("text" => "bilhão", "start" => 1000000000, "end" => 1999999999, "div" => 1000000000),
        array("text" => "bilhões", "start" => 2000000000, "end" => 2147483647, "div" => 1000000000)
    );

    public static function numberToExt($number, $moeda = true)
    {
        if ($number >= self::MIN && $number <= self::MAX) {
            $value = self::conversionR((int)$number);
            if ($moeda) {
                if (floor($number) == 1) {
                    $value .= self::MOEDA;
                } else if (floor($number) > 1)
                    $value .= self::MOEDAS;
            }

            $decimals = self::extractDecimals($number);
            if ($decimals > 0.00) {
                $decimals = round($decimals * 100);
                $value .= " e " . self::conversionR($decimals);
                if ($moeda) {
                    if ($decimals == 1) {
                        $value .= self::CENTAVO;
                    } else if ($decimals > 1)
                        $value .= self::CENTAVOS;
                }
            }
        }
        return trim($value);
    }

    private static function extractDecimals($number) {
        return $number - floor($number);
    }

    public static function conversionR($number) {
        if (in_array($number, range(1, 19))) {
            $value = self::$unidades[$number - 1];
        } else if (in_array($number, range(20, 90, 10))) {
            $value = self::$dezenas[floor($number / 10) - 1].
                " ";
        } else if (in_array($number, range(21, 99))) {
            $value = self::$dezenas[floor($number / 10) - 1].
                " e ".self::conversionR($number % 10);
        } else if (in_array($number, range(100, 900, 100))) {
            $value = self::$centenas[floor($number / 100) - 1].
                " ";
        } else if (in_array($number, range(101, 199))) {
            $value = ' cento e '.self::conversionR($number % 100);
        } else if (in_array($number, range(201, 999))) {
            $value = self::$centenas[floor($number / 100) - 1].
                " e ".self::conversionR($number % 100);
        } else {
            foreach(self::$milhares as $item) {
                if ($number >= $item['start'] && $number <= $item['end']) {
                    $value = self::conversionR(floor($number / $item['div'])).
                        " ".$item['text'].
                        " ".self::conversionR($number % $item['div']);
                    break;
                }
            }
        }
        return $value;
    }

    /**
     * @param mixed $value
     * @param int $default
     * @return int
     */
    public static function intvalx($value, $default = 0) {
        if (is_int($value)) {
            $retorno = $value;
        }
        elseif (is_string($value)) {
            $str = trim($value);
            $str = str_replace(".", "", $str);
            $str = str_replace(",", ".", $str);
            $retorno = intval($str);
        }
        else {
            $retorno = $default;
        }
        return $retorno;
    }

    /**
     * @param mixed $value
     * @param float $default
     * @return float
     */
    public static function floatvalx($value, $default = 0.0) {
        if (is_float($value)) {
            $retorno = $value;
        }
        elseif (is_string($value)) {
            $str = trim($value);
            $str = str_replace(".", "", $str);
            $str = str_replace(",", ".", $str);
            $retorno = floatval($str);
        }
        else {
            $retorno = $default;
        }
        return $retorno;
    }

    /**
     * @param mixed $value
     * @param bool $default
     * @return bool
     */
    public static function boolvalx($value, $default = false) {
        if (is_bool($value)) {
            $retorno = $value;
        }
        elseif (is_int($value)) {
            $retorno = ($value == 1);
        }
        elseif (is_float($value)) {
            $retorno = ($value == 1);
        }
        elseif (is_string($value) && is_numeric($value)) {
            $retorno = (floatval($value) == 1);
        }
        else {
            $retorno = $default;
        }
        return $retorno;
    }
}