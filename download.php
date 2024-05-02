<?php
include('conn.php');

if (isset($_POST['subject_id'])) {
    $quizId = $_POST['subject_id'];

    // Prepare SQL query to fetch questions and related options
    $sql = "SELECT q.question_text, q.answer_type, o.option_text, o.selected_ans, o.correct_ans, s.subject_name
            FROM questions q
            LEFT JOIN options o ON q.id = o.question_id
            LEFT JOIN subjects s ON q.subject_id = s.id
            WHERE q.subject_id = ?
            ORDER BY q.id, o.id";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $quizId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Set CSV headers for download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="quiz_results.csv"');

        $output = fopen('php://output', 'w');

        // Output CSV headers
        fputcsv($output, [
            'Topics',
            'Question',
            'Option_Type',
            'Option',
            'answer1',
            'answer2',
            'Correct_Answer'
        ]);

        while ($row = $result->fetch_assoc()) {
            // Initialize row data
            $rowData = [
                $row['subject_name'],       // Topics (Subject Name)
                $row['question_text'],      // Question
                $row['answer_type'],        // Option_Type (Answer Type)
                '',                         // Placeholder for Option1
                '',                         // Placeholder for Selected_Answer1
                '',                         // Placeholder for Selected_Answer2
                ($row['correct_ans'] == 1) ? 'Yes' : 'No' // Correct_Answer
            ];

            // Assign options and selected answers based on answer_type
            switch ($row['answer_type']) {
                case 'radio':
                case 'checkbox':
                    $options = explode(',', $row['option_text']);
                    $selectedAnswers = explode(',', $row['selected_ans']);

                    // Assign all options to Option1
                    $rowData[3] = implode(', ', $options);

                    // Assign selected answers to Selected_Answer1 and Selected_Answer2
                    if (isset($selectedAnswers[0])) {
                        $rowData[4] = $selectedAnswers[0]; // Selected_Answer1 (first selected answer)
                    }
                    if (isset($selectedAnswers[1])) {
                        $rowData[5] = $selectedAnswers[1]; // Selected_Answer2 (second selected answer)
                    }
                    break;
                case 'textarea':
                    // For textarea type, no predefined options or selected answers
                    break;
            }

            // Output row data to CSV
            fputcsv($output, $rowData);
        }

        fclose($output);
    } else {
        echo 'No questions found for the selected subject.';
    }

    $stmt->close();
    $conn->close();
} else {
    echo 'Subject ID not provided.';
}
?>
