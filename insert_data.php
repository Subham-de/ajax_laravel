<?php
include('conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $subject_name = $_POST['subject_name'];
    $questions = $_POST['questions'];
    $answerTypes = $_POST['answer_types'];
    $options = isset($_POST['options']) ? json_decode($_POST['options'], true) : [];
    $correctAnswers = isset($_POST['correct_answers']) ? $_POST['correct_answers'] : [];

    $sql = "INSERT INTO subjects (subject_name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $subject_name);
    $stmt->execute();
    $subjectId = $stmt->insert_id;

    $sqlQuestion = "INSERT INTO questions (question_text, answer_type, subject_id) VALUES (?, ?, ?)";
    $stmtQuestion = $conn->prepare($sqlQuestion);

    foreach ($questions as $index => $question) {
        $stmtQuestion->bind_param("ssi", $question, $answerTypes[$index], $subjectId);
        $stmtQuestion->execute();
        $questionId = $stmtQuestion->insert_id;
    
        if ($answerTypes[$index] === 'radio' || $answerTypes[$index] === 'checkbox') {
            if (!empty($correctAnswers[$index])) { 
                foreach ($options[$index] as $optionIndex => $option) {
                    $sqlOption = "INSERT INTO options (option_text, question_id, correct_ans) VALUES (?, ?, ?)";
                    $stmtOption = $conn->prepare($sqlOption);
                    if (!$stmtOption) {
                        echo "Error preparing statement: " . $conn->error;
                        exit();
                    }
                    
                    $correct_ans = in_array($optionIndex, $correctAnswers[$index]) ? 1 : 0;
                    $stmtOption->bind_param("sii", $option, $questionId, $correct_ans);
                    $stmtOption->execute();
    
                    
                    echo "Correct Answer for Question " . ($index+1) . ": " . $correct_ans . "<br>";
                }
            } else {
                echo "No correct answer provided for Question " . ($index+1) . "<br>";
            }
        }
    }
    $stmtQuestion->close();
    $stmt->close();
    $conn->close();
    
    header("Location: fetch_subjects.php");
}
?>
