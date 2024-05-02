<?php
include('conn.php');

if (isset($_POST['subject_id'])) {
    $quizId = $_POST['subject_id'];

    // Query to retrieve questions and options for the specified subject
    $sql = "SELECT q.id AS question_id, q.question_text, q.answer_type, o.option_text, o.id AS option_id, o.correct_ans
            FROM questions q
            LEFT JOIN options o ON q.id = o.question_id
            WHERE q.subject_id = ?
            ORDER BY q.id, o.id";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $quizId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // Array to store questions and associated options
        $questions = [];

        // Process each row to organize questions and options
        while ($row = $result->fetch_assoc()) {
            $questionId = $row['question_id'];

            if (!isset($questions[$questionId])) {
                $questions[$questionId] = [
                    'question_text' => $row['question_text'],
                    'answer_type' => $row['answer_type'],
                    'options' => []
                ];
            }

            if (!empty($row['option_text'])) {
                $questions[$questionId]['options'][] = [
                    'option_id' => $row['option_id'],
                    'option_text' => $row['option_text'],
                    'correct_ans' => $row['correct_ans'] // Include correct_ans information
                ];
            }
        }

        // Output the form with questions and answer options
        echo "<form id='submit_data' method='post' class='p-4 m-2'>";
        echo "<input type='hidden' name='subject_id' value='$quizId'>";
        echo "<h2 align='center'>Questions/Answers</h2>";

        foreach ($questions as $questionId => $question) {
            echo "<div>";
            echo "<h5>Question: " . $question['question_text'] . "</h5>";
            echo "<div class='mt-3' style='font-size: 15px;'>";

            if ($question['answer_type'] === 'textarea') {
                echo "<textarea name='answer[$questionId]' class='form-control mt-2 mb-3' rows='4' cols='50'>" . (isset($_POST['answer'][$questionId]) ? htmlspecialchars($_POST['answer'][$questionId]) : '') . "</textarea>";
            } else {
                foreach ($question['options'] as $option) {
                    $optionId = $option['option_id'];
                    $optionText = $option['option_text'];
                    $correctAns = $option['correct_ans'] == 1 ? 'correct' : '';

                    // For checkboxes, use array notation for multiple selections
                    echo "<input type='" . $question['answer_type'] . "' name='answer[$questionId][]' value='$optionId' id='option_" . $questionId . "_" . $optionId . "'>";
                    echo "<label class='form-label $correctAns' for='option_" . $questionId . "_" . $optionId . "'>&nbsp;" . htmlspecialchars($optionText) . "</label><br>";
                }
            }

            echo "</div>";
            echo "</div>";
        }

        echo '<button type="submit" id="submit" class="btn btn-success mt-3 mb-3">Submit</button>';
        echo '</form>';
    } else {
        echo '<p class="alert alert-danger">No questions found for the selected subject.</p>';
    }

    $stmt->close();
    $conn->close();
} else {
    echo '<p class="alert alert-danger">Subject ID not provided</p>';
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    // Form submission using AJAX
    $('#submit_data').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'show_result.php',
            data: $(this).serialize(),
            success: function(response) {
                $('#showdata').html(response); 
            },
            error: function(xhr, status, error) {
                var errorMessage = 'An error occurred while processing your request.';
                if (xhr.responseText) {
                    errorMessage += '<br>' + xhr.responseText; 
                }
                $('#showdata').html('<p class="alert alert-danger">' + errorMessage + '</p>');
            }
        });
    });
});
</script>
