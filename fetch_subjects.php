<?php
include('conn.php');

$sql = "SELECT * FROM subjects ORDER BY id DESC ";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<a href="./index.php" class="btn btn-primary" style="margin-left:900px">Back page</a>';
    echo "<div class='container text-center' id='showtable'>";
    echo "<h2>Show_data</h2>";
    echo '<table class="table">';
    echo '<thead class="table-primary">';
    echo '<tr>';
    echo '<th>Subject Name</th>';
    echo '<th colspan="3">Actions</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody id="exam_subjects">';

    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['subject_name'] . '</td>';
        echo '<td><button type="button" class="btn btn-warning start_show_btn" data-subject-id="' . $row['id'] . '">Show data</button> <button type="button" class="btn btn-primary start_exam_btn ms-3" data-subject-id="' . $row['id'] . '">Start exam</button></td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo "</div>";
} else {
    echo '<a href="./index.php" class="btn btn-light">back to home</a>';
    echo '<p class="display-4">No Records found</p>';

}

$conn->close();
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        function showQuestions(subjectId) {
            $.ajax({
                type: 'POST',
                url: 'show_q.php',
                data: {
                    subject_id: subjectId
                },
                success: function(response) {
                    $("#showdata").html(response);
                    $("#showquestion").hide();
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    errorMessage += '<br>Error message: ' + xhr.responseText;
                    $('#showdata').html('<div class="alert alert-danger" role="alert">' + errorMessage + '</div>');
                }
            });
        }

        // Click event for the Show Data button
        $(document).on('click', '.start_show_btn', function() {
            var subjectId = $(this).data('subject-id');
            showQuestions(subjectId);
        });

        function startquiz(subjectId){
            $.ajax({
                type:'POST',
                url:'startquiz.php',
                data:{
                    subject_id:subjectId
                },
                success:function(response){
                    $("#showdata").html(response);
                    $("#showquestion").hide();

                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    errorMessage += '<br>Error message: ' + xhr.responseText;
                    $('#showdata').html('<div class="alert alert-danger" role="alert">' + errorMessage + '</div>');
                }
            })
        }

        // click event start quiz
        $(document).on('click','.start_exam_btn',function(){
            var subjectId = $(this).data('subject-id');
            startquiz(subjectId);
        })
    });
</script>