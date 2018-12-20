<?php
/*
*   Created 2018 by Deta
*   https://github.com/Detea
*  
*   Github repo
*   https://github.com/Detea/PK2SDK/tree/master/lib/PHP
*
*   Documentation
*   https://detea.github.io/PK2SDK/doc/php/PK2Map.html
*/
class PK2Map {

    public const MAP_WIDTH = 256;
    public const MAP_HEIGHT = 224;
    public const MAP_SIZE = self::MAP_WIDTH * self::MAP_HEIGHT;

    private $version = array();
    private $tileset;
    private $background;
    private $music;
    private $map_name;
    private $author;
    private $level_number;
    private $weather;                   // The weather effect. (See $WEATHER_EFFECTS)
    private $time;
    private $extra;                     // Scrolling of the level. (See $SCROLLING)
    private $player_sprite;             // Index to $prototype_list
    private $x;                         // X Position on the overworld map
    private $y;                         // Y Position on the overworld map
    private $icon;                      // The icon on the overworld map
    private $prototypes;                // Amount of sprites used in this level
    private $prototype_list = array();

    private $layer_foreground = array();
    private $layer_background = array();
    private $layer_sprites    = array();

    private $WEATHER_EFFECTS = array(
        "Normal", 
        "Rain", 
        "Falling Leaves", 
        "Rain & Leaves", 
        "Snow"
    );

    private $SCROLLING = array(
        "Static", 
        "Vertical", 
        "Horizontal", 
        "Horizontal & Vertical"
    );

    public function load($filename, $load_foreground = false, $load_background = false, $load_sprites = false) {
        if (!file_exists($filename))
            die("File (\"".$filename."\") doesn't exist.");

        $handle = fopen($filename, "rb");

        if (!$handle)
            die("Couldn't create file handle.");

        $this->version = fread($handle, 5);
        
        if (!$this->check_version())
            die("Wrong map version.");

        $this->tileset = $this->read($handle, 13);
        $this->background = $this->read($handle, 13);
        $this->music = $this->read($handle, 13);
        $this->map_name = $this->read($handle, 40);
        $this->author = $this->read($handle, 40);
        $this->level_number = $this->read($handle, 8);
        $this->weather = $this->read($handle, 8);
        
        // not used
        $this->read($handle, 8); // Switch 1 time
        $this->read($handle, 8); // Switch 2 time
        $this->read($handle, 8); // Switch 3 time

        $this->time = $this->read($handle, 8);
        $this->extra = $this->read($handle, 8);
        
        // not used
        $this->read($handle, 8); // Background
        
        $this->player_sprite = $this->read($handle, 8);
        $this->x = $this->read($handle, 8);
        $this->y = $this->read($handle, 8);
        $this->icon = $this->read($handle, 8);
        $this->prototypes = $this->read($handle, 8);

        for ($i = 0; $i < $this->prototypes; $i++) {
            array_push($this->prototype_list, $this->read($handle, 13));
        }

        $startX = 0;
        $startY = 0;
        $width = 0;
        $height = 0;

        // Doesn't seem to work correctly yet?
        if ($load_foreground) {
            $startX = $this->read($handle, 8);
            $startY = $this->read($handle, 8);
            $width  = $this->read($handle, 8);
            $height = $this->read($handle, 8);

            for ($y = $startY; $y <= $startY + $height; $y++) {
                for ($x = $startX; $x <= $startX + $width; $x++) {
                    $this->layer_foreground[self::MAP_WIDTH * $x + $y] = intval(fread($handle, 1));
                }
            }
        }

        if ($load_background) {
            $startX = $this->read($handle, 8);
            $startY = $this->read($handle, 8);
            $width  = $this->read($handle, 8);
            $height = $this->read($handle, 8);

            for ($y = $startY; $y <= $startY + $height; $y++) {
                for ($x = $startX; $x <= $startX + $width; $x++) {
                    $this->layer_background[self::MAP_WIDTH * $x + $y] = (int) fread($handle, 1);
                }
            }
        }

        if ($load_sprites) {
            $startX = $this->read($handle, 8);
            $startY = $this->read($handle, 8);
            $width  = $this->read($handle, 8);
            $height = $this->read($handle, 8);

            for ($y = $startY; $y <= $startY + $height; $y++) {
                for ($x = $startX; $x <= $startX + $width; $x++) {
                    $this->layer_sprites[self::MAP_WIDTH * $x + $y] = fread($handle, 1);
                }
            }
        }

        fclose($handle);
    }

    private function read($handle, $length) {
        return $this->clean_string(fread($handle, $length));
    }

    private function clean_string($string) {
        $end = 0;

        for ($i = 0; $i < strlen($string); $i++) {
            // Clean the string by only pulling the actual characters out, leaving the "trash" and the null terminator behind. (0xCC and 0xCD)
            if ($string[$i] == chr(0x00) || $string[$i] == chr(0xCC) || $string[$i] == chr(0xCD)) {
                $end = $i;
                break;
            }
        }

        $tmp = substr($string, 0, $end);

        return $tmp;
    }

    private function check_version() {
        return $this->version[0] == "1" && $this->version[1] == "." && $this->version[2] == "3" && $this->version[3] == chr(0x00) && $this->version[4] = chr(0xCD);
    }

    public function getTileset() {
        return $this->tileset;
    }

    public function getBackground() {
        return $this->background;
    }

    public function getMusic() {
        return $this->music;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function getMapName() {
        return $this->map_name;
    }

    public function getLevelNumber() {
        return $this->level_number;
    }

    public function getPlayerSprite() {
        return $this->player_sprite;
    }

    public function getPrototypeList() {
        return $this->prototype_list;
    }

    public function getPrototypeAmount() {
        return $this->prototypes;
    }

    public function getMapX() {
        return $this->x;
    }

    public function getMapY() {
        return $this->y;
    }

    public function getIcon() {
        return $this->icon;
    }

    public function getWeather() {
        return $this->weather;
    }

    public function getWeatherAsString() {
        return $this->WEATHER_EFFECTS[$this->weather];
    }

    public function getScrolling() {
        return $this->extra;
    }

    public function getScrollingAsString() {
        return $this->SCROLLING[$this->extra];
    }

    public function getTime() {
        return $this->time;
    }

    public function getLayerForeground() {
        return $this->layer_foreground;
    }

    public function getLayerBackground() {
        return $this->layer_background;
    }

    public function getLayerSprites() {
        return $this->layer_sprites;
    }
}
?>
