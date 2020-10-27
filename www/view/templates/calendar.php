<h2><?php print($year); ?>年<?php print((int)$month); ?>月<?php print((int)$day); ?>日</h2>
<table border="1">
    <thead>
        <tr>
            <?php foreach($weekdays as $w) { ?>
                <th><?php print($w); ?></th>
            <?php } ?>
        </tr> 
    </thead>
    <tbody>
        <?php $counter = -$weekday+1; ?>
        <?php for($i=0;$i<6;$i++){ ?>
            <tr>
            <?php for($j=0;$j<7;$j++){ ?>
                <td>
                <?php
                    if($counter>0 && $counter <= $lastDate){
                        print "<a href='index.php?year=" . $year;
                        print '&month=' . $month;
                        print '&day=' . $counter . "'>";
                        print $counter;
                        print ('</a>');
                    }
                ?>
                </td>
            <?php $counter++; ?>
            <?php } ?>       
            </tr>
        <?php } ?>
    </tbody>
</table>
<?php
    if((int)$month===12){
        $next_month='01';
        $next_year = $year+1;
    }else{
        $next_month=$month+1;
        $next_year =$year;
    }
    if((int)$month===1){
        $last_month='12';
        $last_year = $year-1;
    }else{
        $last_month=$month-1;
        $last_year =$year;
    }
?>

<a href='index.php?year=<?php print $last_year; ?>&month=<?php print $last_month; ?>'>前月</a>
<a href='index.php?year=<?php print $next_year; ?>&month=<?php print $next_month; ?>'>翌月</a>