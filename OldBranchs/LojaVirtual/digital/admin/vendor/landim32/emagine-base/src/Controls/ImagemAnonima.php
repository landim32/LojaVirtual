<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 29/07/2017
 * Time: 16:56
 */

namespace Emagine\Base\Controls;

class ImagemAnonima
{
    /**
     * @return string
     */
    private static function imagemBase64() {
        return
            "iVBORw0KGgoAAAANSUhEUgAAAMgAAADICAYAAACtWK6eAAAABmJLR0QA/wD/AP+gvaeTAAAACXBI" .
            "WXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3QcGDDIMGOPHowAACmZJREFUeNrtnX+I13cdxx+KlxyX" .
            "ZnIpcjhF7PKww7zM3OxmMhHZqjFPQmpUEmMWtBg16o9B//ZnRIUj+mMEEais1RrmhBBrtRrOGqKz" .
            "YV4mohORxOTw0P54vw3Z7nt6d98f7x+PB3wQzz+8z+f9en6er9f7/f683rNu3bqFiEzMbB+BiAIR" .
            "USAizWbOnj17fApSLbt379ZBRKbtID6ClrII6Abue9fPu+O/3clN4Oy7fvav+Oc5YNzHqUByZC6w" .
            "AugFlgIL49XXgv/rfBTLP4EzwChwwyFQIKmxEhgEVgHL2/j/LonXujt+dg44AZwETgFjDk8TBeJC" .
            "4T3TC2wC1gMLEvq9+uK1Jf79VLxeAy46bDpIO97aD8e3dg6TGv3x+kx0lSPAMWsYBdKKZ/MIsDXj" .
            "57QqXleBw8BBUzAF0gwWALticJXAvOgonwJ+AfzNIVYgU2UNMByL7nkFC//rwCHgQHQWsUi/69v1" .
            "i8Daiu55S7zeBp63mG/MbMXBM5WJ405WAt8irVk5BZJQevkNYLH1Fl/jvSv7UrlAPg8sMwQg1l3f" .
            "Azb7KCzSB4AngB6H/z2xsJOw6PhLXDeptkjfpDgmZTimnXuAa6ZYdbG64oJ8KvQD37V4r0sgfYTF" .
            "P7k3FgFPEbbmK5DCWUGYzpxn3E/5pfKkNUjZbAZ24K6B6TIADAFHqxRIwffWHVOqNcb4jHmoVoGU" .
            "mmItB55VHE3j9or7YgWSP8OE7SO9xnVT6Qe+TWVT5KXVII8SPm6S1jAf+EhN6VZJDrJVcbSFT5hi" .
            "5cfi6B7SeoZi0a5AMuJBnMZtdypbxZpSKTXIcmO2rcwl7Gl7SQdJn22EaUhpL/ebYqXPWuAxY7Uj" .
            "9NKa7pEKpEn0EL5fEFNba5AGhaLfUncWHSRR5gMbjc+Os1SBpInTujqIKdYk6B7p1IE9FPxpbo4O" .
            "0kc4f0PSoOgdvjkKZNCYTIpeBZIWA8akArEGaYzN3izUdZAGLKTyLhuJOvpsBZIG9o9Njx5C1xhT" .
            "rARw5TxNVhGOUtBBFIhMQLG7qXMTiI3f0uQ+U6w0sEBPtw4pckU9NweZaywmS5Er6gpEmkWRM4we" .
            "4ik6SEEOIunSq0A6T5dxmCzzTbE6zw3jMFmKnII3xZJm0aNARBpT5BpVbinWTeMwWYqcgs/NQcaM" .
            "Q0WiQMR4MsWSElN2FS/SmC4F0lmuG4NiiiUW6TqIFMiYAuksroM4PqZYk3DNGNRBdJDG/NcYTJri" .
            "NpPm5iBXjcFkGY+XDiJSQ3qlQESBFJZi2fYnXYpcxPWTW9FBChLIB41DHcQUqzHvNw6TpchF3BzP" .
            "B5E0GS/xpnJzEM8HSZciO87k5CBz8PiDlOlSIJ3lQ8Zg0rzPFMv6QxpjXyzrD5kEW4/qIDIJC2Id" .
            "UlSxbg0izYylpaZYnaPXGEyeflMsaxBpzBBwQAdpP114/FoOLANW6CDt5wPGXjZ8DviBDtJeeoy7" .
            "bBgANiiQ9qdYkg+Px3TLFEuBSIPxWgyM6iBtErIxlxU3geM6SPuwaXVenKeQJn+5OIgdFfPipEV6" .
            "e/mPMZcVb5ZyI7mkWNcIXTNcLMyDMzpI+7lo3GUzTsXUjDntxTpLgbtFC+RcSTeTk4P829jLgvMK" .
            "pDOMGntZcKGkm8ktxbqJDbetFXWQCRkD3jH+dBAdpDFvE/b4SLovsaIWdXNLV04ag6ZXOsjkDiLp" .
            "crm0G5qd4QC4YJgu7yiQzvMP4zBZLplipVGHbDQWrUF0EOuQ3DhX2g3l6CC36xD7ZKXFGHBFB7EO" .
            "kYkpcitQjg5iHWLqq4NYhzgmCmRmdcglYzKp+uOUKVZavIUd31PhDTzEMzlOGZfJ8JdSbyxnB1Eg" .
            "aXAeOFHqzeXsIJcpcHNchvya8CGbDpKoi2wwRjvGWeBYyTeY++erbxmjHeMG8LOS3aMEgViHdIZx" .
            "4DkK62BSYop1KdYhHhHdXuf4ccmFeUkOAq6qt5MxwvFqJ2q54dwd5Haatd7YbTnXgR8Cp2u66RIc" .
            "xEK99dyMNcfp2m68BAe5aB3Scg7VlFaV5iBgO6BWchl4sdabL0UgJ4zjlrrHeK03X0KKBc5ktYob" .
            "wB9qfgClOMhlCmwYkEjqOlbzAyjFQSCci9dnTDeV12t/ACUdJWChbuqqg9xlMG8AXcZ1U7iCnzUX" .
            "5SA3dBHdQ4HcvQ4RBWKKNUlRuROPaVMgOsiEXMO9Wc3gOk6bF+kgAH8FBhzaGbvHTR9DmanIMQfX" .
            "Wk4HmTzNOg4MOrwKRAeZmFcd2mlzGtspFS+QvwNXHd5pcdhHUHaKBWF79p+ArQ7xlLiI+6+qcBCA" .
            "31usT5kXqfjbj5ochJhHHwXWOcz3zDYdpB4HAXhJF5kSS/Hb/mocBELnP11kanwJ+ClhulwHqeAe" .
            "i+4+3gIGgG8CPT6KOgRygYIPeGkRy4CngW5TrLJTrNu8AKwF5hr7U6pHvgr8pGYHrmVb+BXgZWN+" .
            "ygwCj+kgdXCI0MPXxg5TYyuh/3GV+7Nq+rBoHHjegn1afBmYp4PUwbVaB3sGzAN2Ebq76yClvgyA" .
            "3Ypj2qwGNiuQcvkwrhLPlBFgiSlWmSiOmdNFmPr9PpVsaqzJQUytmsNSYIcOUh5rje2msZkw7Xtc" .
            "ByknvVpuXDeVXcB8HaQMbODQmpT1K8CPKHhtqRYHUSCtYXXp9UgNAukCVhnLLeMhYJMpVr4swyMR" .
            "Ws3OmGYd0UHyQ/doTxw9ToFdZGpwkBXGb9sYAVYCP6eQvmSlO8hsBdJ21gDPUMjCbOkC6cPPRjvB" .
            "YsI6SfbxVXqKtdJY7RirgUeA3+gg1h8yMQ9HoeggCQrjSWCBMdrxF/AThMYPp7IUSEGD0U3YkLgu" .
            "97dWYXQTWgi9ABxUIO3//VcD9wMfxQXBlJ1kBNgA7CejXcC5plirCB1KPoYdAHOiD3gKOBGFclYH" .
            "aR5Loyg2UME268IZAJ4F/khoMJ7siVapO0hvFMR6wty6lMXGOLavxPrkug5yd+YCH491Rb8xVDxd" .
            "hOngTYQDfI6Q0PclKQlkBfAAYRbK1e/66AG+AGwhzHgdNcUK+3U+CTxoCiWRRYQ1rNHoKB2d8eqU" .
            "gwwCw/HP2caETMAywozX6SiUk6U7yKJYlD2As1AytdT7aeBtwtTw6ZIcZA4wFN3CgltmwkrgO4Q1" .
            "lF8BZ3IWyHxC76RhbNgmzWUgXm8SdgqP5pRirYqiGLK2kDbUsYOE2a6XadGqfDMcZC5hMW8zlTU2" .
            "liQYitdx4ABN3jU8EwdZQljc2YDrFtJ5VsfrNGFV/o1OOcha4NPYLUTSZAXhHJjzUSh/ZgYr8/cq" .
            "kHmxthjGYwQkD5YQjo57NKZerwJjzU6xlsfaYh1lfVwl9bCA0Njus4SDXA8TjuGbtoN0EXZYbiKs" .
            "ZoqUQE90k22EDZGvEI4Hn5RZO3b8v/fwwugWG/EjJCmfceA14Hd79+690FAgIyMjtw9ntAO61MpR" .
            "4MC+fftGJxLIcz4fESCsofx23759J++1SBepiX6gf2RkZBQ4uH///tdnbd++XQcRmZgL7pcSacxi" .
            "UyyRSdBBRBSIyPQwxRLRQUSmx/8AJkVVEgQOaPMAAAAASUVORK5CYII=";
    }

    /**
     * @return string
     */
    public static function gerar() {
        return "data:image/png;base64," . ImagemAnonima::imagemBase64();
    }
}