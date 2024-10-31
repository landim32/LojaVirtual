<?php
namespace Emagine\Base\Utils;


class DateTimeUtils
{
    /**
     * @param int $now
     * @param int|null $otherDate
     * @param int|null $offset
     * @return string
     */
    public static function humanizeDateDiff($now, $otherDate = null, $offset = null) {
        if (is_null($otherDate) && is_null($offset))
            $otherDate = time();
        if($otherDate != null)
            $offset = $now - $otherDate;
        if($offset != null) {
            $deltaS = $offset%60;
            $offset /= 60;
            $deltaM = $offset%60;
            $offset /= 60;
            $deltaH = $offset%24;
            $offset /= 24;
            $deltaD = ($offset > 1)?ceil($offset):$offset;
        }
        else
            //throw new \Exception("Must supply otherdate or offset (from now)");
            return "Agora";

        if($deltaD > 1) {
            if($deltaD > 365){
                $years = ceil($deltaD/365);
                if($years == 1)
                    return "Aproximandamente um ano";
                else
                    return "$years anos atrás";
            }
            if($deltaD > 6)
                //return date('d-M',$otherDate);
                return strftime('%d-%b',$otherDate);

            return "$deltaD dias atrás";
        }
        if($deltaD == 1)
            return "Ontem";
        if($deltaH == 1)
            return "Menos de uma hora";
        if($deltaM == 1)
            return "Menos de um minuto";
        if($deltaH > 0)
            return $deltaH." horas atrás";
        if($deltaM > 0)
            return $deltaM." minutos atrás";
        else
            return "À alguns segundos";
    }
}