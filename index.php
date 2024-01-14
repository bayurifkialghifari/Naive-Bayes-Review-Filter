<form action="" method="post">
    <textarea name="review"></textarea><br>
    <input type="submit" name="submit">
</form>

<?php

    if(isset($_POST['submit'])) {
        require_once './utils/DB.php';

        $connection = DB::connect();

        // Get sum postive and negative word
        $sumWord = DB::query($connection, "SELECT sum(positive) as positive, sum(negative) as negative FROM rewiew_words")->fetch_object();

        // Get the review
        $review = $_POST['review'];
        // Clean up the review
        $review = preg_replace('/[^A-Za-z]/', ' ', $review);
        $review = strtolower($review);

        // Get only unique words
        $unique = array_unique(explode(' ', $review));

        // Array sum
        $sumArray = [];

        // Get total sample
        $totalSample = DB::query($connection, "SELECT sum(total_positive) as total_positive, sum(total_negative) as total_negative FROM positive_negative")->fetch_object();
        $negativeSample = (int)$totalSample->total_negative / (int)$sumWord->negative;
        $positiveSample = (int)$totalSample->total_positive / (int)$sumWord->positive;

        // Sum up
        foreach($unique as $word) {
            if($word !== '') {
                // Check if word is exist in the table
                $wordIsExist = DB::query($connection, "SELECT * FROM rewiew_words WHERE word = '$word'");

                if($wordIsExist->num_rows !== 0) {
                    $row = $wordIsExist->fetch_object();
                    
                    // Count negative
                    $negative = (int)$row->negative / (int)$sumWord->negative;

                    // Count positive
                    $positive = (int)$row->positive / (int)$sumWord->positive;

                    // Determine if the word is positive or negative
                    $sumArray[$word] = [
                        'negative' => $negative,
                        'positive' => $positive,
                    ];
                }
            }
        }

        $totalPositive = 1;
        $totalNegative = 1;

        // Check positive or negative base on the sum
        foreach($sumArray as $key => $value) {
            $totalPositive *= $value['positive'] * $positiveSample;
            $totalNegative *= $value['negative'] * $negativeSample;
        }

        echo $_POST['review'];
        echo '<br>';
        echo '<br>';
        echo 'Positive : '. $totalPositive;
        echo '<br>';
        echo 'Negative : '. $totalNegative;
        echo '<br>';
        echo $totalNegative > $totalPositive ? 'Negative Review' : 'Positive Review';

        $connection->close();
    }
