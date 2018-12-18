<?php
include "PK2Map.php";

$map = new PK2Map();
$map->load("level001.map", true);

echo "
    <h2>PK2Map</h2><br />
    
    <table>
    <tr><td><b>Name</b>:</td><td>".$map->getMapName()."</td></tr>
    <tr><td><b>Tileset</b>:</td><td>".$map->getTileset()."</td></tr>
    <tr><td><b>Background</b>:</td><td>".$map->getBackground()."</td></tr>
    <tr><td><b>Author</b>:</td><td>".$map->getAuthor()."</td></tr>
    <tr><td><b>Level number</b>:</td><td>".$map->getLevelNumber()."</td></tr>
    <tr><td><b>Player sprite</b>:</td><td>".$map->getPrototypeList()[$map->getPlayerSprite()] ."</td></tr>
    <tr><td><b>Weather</b>:</td><td>".$map->getWeatherAsString()."</td></tr>
    <tr><td><b>Scrolling</b>:</td><td>".$map->getScrollingAsString()."</td></tr>
    <tr><td><b>Time limit</b>:</td><td>".$map->getTime()."</td></tr>
    <tr><td><b>Position on map</b>:</td><td>".$map->getMapX().",".$map->getMapY()."</td></tr>
    <tr><td><b>Icon</b>:</td><td>".$map->getIcon()."</td></tr>
    </table>
    <br />
    <b>Sprites</b> (<i>".$map->getPrototypeAmount()."</i>):<br />
";

echo "<table>";
foreach ($map->getPrototypeList() as $sprite) {
    echo "<tr><td>".$sprite."</td></tr>";
}
echo "</table>";
?>