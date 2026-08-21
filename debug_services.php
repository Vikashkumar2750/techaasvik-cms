<?php
/**
 * TechAasvik Services Page Debugger
 * Visit: https://t1.techaasvik.com/debug_services.php
 * DELETE after use!
 */
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

echo "<style>body{font-family:monospace;padding:20px;background:#0f172a;color:#e2e8f0;}h2{color:#6366f1;}.ok{color:#34d399;}.err{color:#f87171;}pre{background:#1e293b;padding:15px;border-radius:8px;overflow-x:auto;font-size:13px;}</style>";
echo "<h1 style='color:#e2e8f0;'>TechAasvik Services Page Debugger</h1>";

define("APP_ROOT", __DIR__);
define("APP_PATH", APP_ROOT . "/app");
define("VIEWS_PATH", APP_ROOT . "/views");
define("STORAGE_PATH", APP_ROOT . "/storage");
define("ASSETS_PATH", APP_ROOT . "/assets");
define("APP_ENV", "development");

echo "<h2>1. Helpers</h2>";
foreach (["string","url","date","seo"] as $h) {
    $p = APP_PATH . "/Helpers/$h.php";
    try { require_once $p; echo "<span class='ok'>OK: $h.php</span><br>"; }
    catch (Throwable $e) { echo "<span class='err'>ERR $h.php: ".$e->getMessage()."</span><br>"; }
}

echo "<h2>2. Constants & Config</h2>";
try { require_once APP_PATH."/Config/constants.php"; echo "<span class='ok'>OK: constants.php</span><br>"; }
catch (Throwable $e) { echo "<span class='err'>ERR constants: ".$e->getMessage()."</span><br>"; }
try { $config = require APP_PATH."/Config/config.php"; echo "<span class='ok'>OK: config.php</span><br>"; }
catch (Throwable $e) { echo "<span class='err'>ERR config: ".$e->getMessage()."</span><br>"; }

echo "<h2>3. Autoloader</h2>";
spl_autoload_register(function(string $class): void {
    $map = ["Core\\" => APP_PATH."/Core/","Models\\" => APP_PATH."/Models/","Controllers\\" => APP_PATH."/Controllers/","Services\\" => APP_PATH."/Services/"];
    foreach ($map as $ns => $base) {
        if (str_starts_with($class, $ns)) {
            $f = $base . str_replace([$ns,"\\"],["","/"],$class) . ".php";
            if (file_exists($f)) { require_once $f; return; }
        }
    }
});

echo "<h2>4. Database</h2>";
try { $db = \Core\Database::getInstance(); echo "<span class='ok'>OK: DB connected</span><br>"; }
catch (Throwable $e) { echo "<span class='err'>ERR DB: ".$e->getMessage()." line:".$e->getLine()."</span><br><pre>".$e->getTraceAsString()."</pre>"; }

echo "<h2>5. SeoService</h2>";
try {
    $svc = new \Services\SeoService();
    $seo = $svc->buildStatic("Services Test","Test desc","https://t1.techaasvik.com/services");
    echo "<span class='ok'>OK: SeoService works</span><br>";
} catch (Throwable $e) { echo "<span class='err'>ERR SeoService: ".$e->getMessage()." in ".$e->getFile()." line:".$e->getLine()."</span><br><pre>".$e->getTraceAsString()."</pre>"; }

echo "<h2>6. services.php View File</h2>";
$vf = VIEWS_PATH."/pages/services.php";
echo file_exists($vf) ? "<span class='ok'>OK: file exists (".filesize($vf)." bytes)</span><br>" : "<span class='err'>ERR: services.php NOT FOUND!</span><br>";

echo "<h2>7. Render Test</h2>";
try {
    $_SERVER["REQUEST_URI"] = "/services";
    ob_start();
    $seo2 = (new \Services\SeoService())->buildStatic("Services","Test","https://t1.techaasvik.com/services");
    extract(["seo"=>$seo2]);
    require $vf;
    $out = ob_get_clean();
    echo "<span class='ok'>OK: Rendered successfully (".strlen($out)." bytes)</span><br>";
} catch (Throwable $e) {
    ob_end_clean();
    echo "<span class='err'>ERR Render: ".$e->getMessage()."</span><br>";
    echo "<span class='err'>File: ".$e->getFile()." Line: ".$e->getLine()."</span><br>";
    echo "<pre>".$e->getTraceAsString()."</pre>";
}

echo "<br><p style='color:#f87171;font-weight:bold;'>DELETE debug_services.php after use!</p>";
