<?php

class Formulas
{
    public static function dmg($weaponLevel, $armorLevel)
    {
        $dealt = $weaponLevel * mt_rand(5, 10);
        $absorbed = $armorLevel * mt_rand(1, 3);
        $dmg = $dealt - $absorbed;
        if ($dmg <= 0) {
            return mt_rand(0, 3);
        } else {
            return $dmg;
        }
    }

    public static function hp($armorLevel)
    {
        $hp = 80 + (($armorLevel - 1) * 30);
        return $hp;
    }
}
