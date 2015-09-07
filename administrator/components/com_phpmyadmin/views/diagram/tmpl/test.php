<?php
/**
 * Created by PhpStorm.
 * User: cuongnd
 * Date: 9/3/2015
 * Time: 10:01
 */
?>
<?php

?>
<div id="dictionary">
    hello dictionary
</div>
<div class="main_test">
    <div class="letter" id="letter-a">
        <a href="#">A</a>
    </div>
    <div class="letter" id="letter-g">
        <a href="#">G</a>
    </div>
    <div class="letter" id="letter-f">
        <h3>F</h3>
        <form>
            <div class="definition" style="display: none">
                hello definition
            </div>
            <input type="text" name="term" class="term" value="" id="term" />
            <input type="submit" name="search" value="search"
                   id="search" />
        </form>
    </div>
</div>

<script type="text/javascript">
    $ = jQuery;

    $(document).ready(function() {
        var url = 'http://examples.learningjquery.com/jsonp/g.php';
        $('#letter-g a').click(function () {
            $.getJSON(url + '?callback=?',
                function (data) {
                    $('#dictionary').empty();
                    $.each(data, function (entryIndex, entry) {
                        var html = '<div class="entry">';
                        html += '<h3 class="term">' + entry['term']
                        + '</h3>';
                        html += '<div class="part">' + entry['part']
                        + ' </div>';
                        html += '<div class="definition">';
                        html += entry['definition'];
                        if (entry['quote']) {
                            html += '<div class="quote">';
                            $.each(entry['quote'], function (lineIndex, line) {
                                html += '<div class="quote-line">' + line
                                + '</div>';
                            });
                            if (entry['author']) {
                                html += '<div class="quote-author">'
                                + entry['author'] + '</div>';
                            }
                            html += '</div>';
                        }
                        html += '</div>';
                        html += '</div>';
                        $('#dictionary').append(html);
                    });
                });
            return false;
        });
    });


</script>
<style type="text/css">
</style>







