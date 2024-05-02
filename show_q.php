<?php
include('conn.php');

if (isset($_POST['subject_id'])) {
    $quizId = $_POST['subject_id'];

    $sql = "SELECT q.id AS question_id, q.question_text, q.answer_type, o.option_text,o.id AS option_id
            FROM questions q
            LEFT JOIN options o ON q.id = o.question_id
            WHERE q.subject_id = ?
            ORDER BY q.id, o.id";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $quizId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $questions = [];

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
                $questions[$questionId]['options'][] = $row['option_text'];
            }
        }
        echo '<a href="./index.php" class="btn btn-light mt-3 mb-3" style="margin-left:900px">Back to Home page</a>';
        echo "<h2 align='center'>Q/A</h2>";
        foreach ($questions as $questionId => $question) {
            echo "<div>";
            echo "<h5>Question: " . $question['question_text'] . "</h5>";
            echo "<div class='mt-3' style='font-size: 15px;'>";
            
            if ($question['answer_type'] === 'textarea') {
                echo "<textarea name='answer[$questionId]' class='form-control mt-2 mb-3' rows='4' cols='50'>" . (isset($_POST['answer'][$questionId]) ? $_POST['answer'][$questionId] : '') . "</textarea>";
            } else {
                foreach ($question['options'] as $optionIndex => $option) {
                    echo "<input type='" . $question['answer_type'] . "' name='answer[$questionId][]' value='" . $option . "' id='option_" . $questionId . "_" . $optionIndex . "'>";
                    echo "<label class='form-label' for='option_" . $questionId . "_" . $optionIndex . "'>" .'&nbsp;' .    $option   . "</label><br>";
                }
            }
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo '<p class="alert alert-danger">An error occurred while fetching questions: ' . mysqli_error($conn) . '</p>';
    }
    
    $conn->close();
} else {
    echo '<p class="alert alert-danger">Subject ID not provided</p>';
}
?>