<?php

    require_once './utils/DB.php';

    $connection = DB::connect();

    $query = "SELECT * FROM reviews limit 1000";
    $result = DB::query($connection, $query);

    // Print out the text
    foreach ($result as $row) {
        // Clean up the text
        $text = preg_replace('/[^A-Za-z]/', ' ', $row['text']);
        $text = strtolower($text);

        // Check the rating
        $rating = $row['rating'];

        // Check if the rating is positive
        $isPositive = $rating > 3;

        echo $text . '<br>';
        // Get only unique words
        $unique = array_unique(explode(' ', $text));

        foreach($unique as $word) {
            if($word !== '') {
                // Check if word is exist in the table
                $wordIsExist = DB::query($connection, "SELECT * FROM rewiew_words WHERE word = '$word'");

                $query = "";

                // Check field to update
                $field = $isPositive ? 'positive' : 'negative';

                if($wordIsExist->num_rows === 0) {
                    // Insert word
                    $query = "INSERT INTO rewiew_words(word, $field) VALUES ('$word', 1)";
                } else {
                    // Update the count
                    $query = "UPDATE rewiew_words SET $field = $field + 1 WHERE word = '$word'";
                }

                DB::query($connection, $query);
            }
        }

    }

    $connection->close();