<?php

include("config.php");
include("functions.php");
include("session.php");
include("userclass.php");

$loggedin = $session->isLoggedIn();

$faq = pg_query("SELECT * FROM faq");

$questions = array();

$i=0;

while($r = pg_fetch_array($faq, null, PGSQL_ASSOC)) {
    $questions[$i]["id"]                = $r['id'];
    $questions[$i]["question"]          = $r["question"];
    $questions[$i]["answer"]            = $r["answer"];
    $i++;
}


// number of questions for each column
$columncount = floor($i / 3.0);
// if the first or second column should have an extra question or not since it's not easily divisible by 3
$first = 0;
$second = 0;
if($i % 3 > 1) {
    $first = 1;
    $second = 1;
} elseif($i % 3 > 0) {
    $first = 1;
}


htmlHeader("FAQ - synccit - reddit history/link sync", $loggedin);


?>
<div class="fourcol">
    <ul class="faqlist">
        <?php for($j=0; $j<($columncount + $first); $j++): ?>
        <li>
            <h3 class="questiontitle" id="question<?php echo $questions[$j]["id"]; ?>">
                <?php echo $questions[$j]["question"]; ?>
            </h3>
            <p class="questionbody">
                <?php echo $questions[$j]["answer"]; ?>
            </p>
        </li>
        <?php endfor; ?>
    </ul>
</div>
<div class="fourcol">
    <ul class="faqlist">
        <?php
            // the offset for the second row in the questions array
            $buffer = $j;
            for($j=0; $j<($columncount + $second); $j++):
        ?>
        <li>
            <h3 class="questiontitle" id="question<?php echo $questions[$j+$buffer]["id"]; ?>">
                <?php echo $questions[$j+$buffer]["question"]; ?>
            </h3>
            <p class="questionbody">
                <?php echo $questions[$j+$buffer]["answer"]; ?>
            </p>
        </li>
        <?php endfor; ?>
    </ul>
</div>
<div class="fourcol last">
    <ul class="faqlist">
        <?php
        // the offset for the second row in the questions array
        $buffer = $j;
        for($j=0; $j<($columncount + $second); $j++):
            ?>
            <li>
                <h3 class="questiontitle" id="question<?php echo $questions[$j+$buffer]["id"]; ?>">
                    <?php echo $questions[$j+$buffer]["question"]; ?>
                </h3>
                <p class="questionbody">
                    <?php echo $questions[$j+$buffer]["answer"]; ?>
                </p>
            </li>
            <?php endfor; ?>
    </ul>
</div>

<?php

htmlFooter();
