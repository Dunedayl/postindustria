<html>
    <body>
        <table-of-content>
<?php foreach ($Headers as $value) {
        echo "\t\t<div>" . $value . "</div>\n";
}
?>
        </table-of-content>
        <content>
<?php foreach ($Articles as $key => $value) {
    echo "\t\t<article>\n";
    echo "\t\t\t<h1>" . $key . "</h1>\n";
    echo "\t\t\t<p>" .$value . "</p>\n";
    echo "\t\t</article>\n";
} ?>
        </content>
        <tags>"<?php echo $Tags ?></tags>
    </body>
</html>