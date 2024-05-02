<!DOCTYPE html>
<html>
<head>
    <title>Quiz Result</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .show {
            background-color: #f8f9fa;
            font-size: 20px;
            font-family: sans-serif;
        }

        b {
            font-size: 40px;
        }
    </style>
</head>
<body>

<?php
include('conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $answers = $_POST['answer'];

    $totalQuestions = 0;
    $correctAnswers = 0;
    $score = 0;

    foreach ($answers as $questionId => $selectedOptions) {
        $totalQuestions++;

        // Retrieve correct answers from options table
        $sql = "SELECT option_text FROM options WHERE question_id = ? AND correct_ans = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $questionId);
        $stmt->execute();
        $result = $stmt->get_result();

        $correctAnswer = [];
        while ($row = $result->fetch_assoc()) {
            $correctAnswer[] = $row['option_text'];
        }

        // Retrieve selected answers from options table
        $sqlSelected = "SELECT id, option_text FROM options WHERE question_id = ? AND id IN (" . implode(',', $selectedOptions) . ")";
        $stmtSelected = $conn->prepare($sqlSelected);
        $stmtSelected->bind_param("i", $questionId);
        $stmtSelected->execute();
        $resultSelected = $stmtSelected->get_result();

        $selectedAnswer = [];
        while ($rowSelected = $resultSelected->fetch_assoc()) {
            $selectedAnswer[] = $rowSelected['option_text'];
        }

        // Check if selected answers match the correct answers
        if (is_array($selectedAnswer) && is_array($correctAnswer)) {
            sort($selectedAnswer);
            sort($correctAnswer);
            if ($selectedAnswer === $correctAnswer) {
                $correctAnswers++;
            }
        }

        // Update selected_ans column in options table with the selected answers
        $updateSql = "UPDATE options SET selected_ans = ? WHERE question_id = ? AND id IN (" . implode(',', $selectedOptions) . ")";
        $stmtUpdate = $conn->prepare($updateSql);
        $selectedAnsText = implode(', ', $selectedAnswer);
        $stmtUpdate->bind_param("si", $selectedAnsText, $questionId);
        $stmtUpdate->execute();
        $stmtUpdate->close();
    }

    if ($totalQuestions > 0) {
        $score = ($correctAnswers / $totalQuestions) * 100;
    }

    // Display quiz result
    echo "<button id='download' class='btn btn-success' style='margin-left:900px'>Download Q/A</button>";
    echo "<div class='container w-50' style='margin-top:30px;'>";
    echo '<div class="d-flex jutify-content-center align-items-center border p-3 mt-3 mb-4 flex-column show">';
    echo "<h1>Quiz Result: </h1>";
    echo "<ul class='text-decoration-none'>";
    echo "<p class='text-danger'>Total Questions: $totalQuestions</p>";
    echo "<p class='text-success'>Correct Answers: $correctAnswers</p>";
    echo "</ul>";
    echo "<p class='fw-3 text-center'>Score: <br> <b>$score%</b></p>";
    echo "</div>";
    echo "</div>";

    // hidden inputs
    echo "<input type='hidden' id='subject_id' value='{$_POST['subject_id']}'>";

    $stmt->close();
    $stmtSelected->close();
} else {
    echo "<p class='alert alert-danger'>Error: Invalid request method.</p>";
}

$conn->close();
?>

<script>
$(document).ready(function() {
    // AJAX request to download.php on button click
    $('#download').click(function(e) {
        e.preventDefault();
        
        // Retrieve subject_id from hidden input field
        var subject_id = $('#subject_id').val();
        
        $.ajax({
            type: 'POST', 
            url: 'download.php',
            data: { subject_id: subject_id }, // Pass subject_id as data
            success: function(response) {
                if (response.startsWith('<')) {
                    alert('Error: Unexpected response received.');
                    console.log(response); // Log response for debugging
                } else {
                    var blob = new Blob([response]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'quiz_results.csv';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while downloading: ' + error);
                console.log(xhr.responseText); // Log detailed error response
            }
        });
    });
});
</script>

</body>
</html>
