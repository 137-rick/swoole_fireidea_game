<?php

class Utils
{
    public static function sanitize($str)
    {
        $regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
        return preg_replace($regex, "", $str);
    }

    public static function clamp($min, $max, $value)
    {
        if ($value < $min) {
            return $min;
        } else if ($value > $max) {
            return $max;
        } else {
            return $value;
        }
    }

    public static function randomOrientation()
    {
        $r = mt_rand(0, 4);
        $o = TYPE_ORIENTATIONS::DOWN;
        if ($r === 0)
            $o = TYPE_ORIENTATIONS::LEFT;
        if ($r === 1)
            $o = TYPE_ORIENTATIONS::RIGHT;
        if ($r === 2)
            $o = TYPE_ORIENTATIONS::UP;
        if ($r === 3)
            $o = TYPE_ORIENTATIONS::DOWN;

        return $o;
    }

    public static function distanceTo($x, $y, $x2, $y2)
    {
        $distX = abs($x - $x2);
        $distY = abs($y - $y2);
        return ($distX > $distY) ? $distX : $distY;
    }
}
/*


Utils.random = function(range) {
    return Math.floor(Math.random() * range);
};

Utils.randomRange = function(min, max) {
    return min + (Math.random() * (max - min));
};

Utils.randomInt = function(min, max) {
    return min + Math.floor(Math.random() * (max - min + 1));
};


Utils.Mixin = function(target, source) {
  if (source) {
    for (var key, keys = Object.keys(source), l = keys.length; l--; ) {
      key = keys[l];

      if (source.hasOwnProperty(key)) {
        target[key] = source[key];
      }
    }
  }
  return target;
};

 */