<!DOCTYPE>
<html lang="ru">
<head>
 <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <title>Подождите.</title>
        <meta http-equiv="refresh" content="6; url=<?=$_SERVER['QUERY_STRING']?>">

    <script type="text/javascript">
    //<![CDATA[
    // Fix Mozilla bug: 209020
    if ( navigator.product == 'Gecko' )
    {
        navstring = navigator.userAgent.toLowerCase();
        geckonum  = navstring.replace( /.*gecko\/(\d+)/, "$1" );

        setTimeout("moz_redirect()",5500);
    }

    function moz_redirect()
    {
        var url_bit     = "<?=$_SERVER['QUERY_STRING']?>";
        window.location = url_bit.replace( new RegExp( "&amp;", "g" ) , '&' );
    }
    //>
    </script>
    </head>
    <body>
        <div id="redirectwrap">
        <center>
        <noindex>
            <h4><b>Хаки и Скрипты</b></h4>
        <p>Все что дальше этой страницы - не наш сайт, ответственности за файлы мы не несем</p>
            <p>Вы перешли по внешней ссылке, возможно вы скачиваете файл. Подождите 5 секунды или : </p>
            <p class="redirectfoot">(<a href="<?=$_SERVER['QUERY_STRING']?>">нажмите сюда, если не хотите ждать</a>)</p>
            </noindex>
            </center>
        </div>
    </body>
    </html>