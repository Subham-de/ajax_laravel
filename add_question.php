<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Question</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div id="showquestion">
            <h2>Add Questions</h2>
            <button class='btn btn-primary showbtn' id='showbtn' style="margin-left: 950px;">Start exam</button>
            <form id="question_form" method="post">
                <div class="form-group">
                    <label for="subject_name">title:</label>
                    <input type="text" class="form-control" id="subject_name" name="subject_name">
                </div>
                <div id="questions_container">
                    <!-- dynamically added here questions -->
                </div>
                <div id="show_btn">
                    <button id="add_question" type="button" class="btn btn-primary mt-2 mb-2">Add Question</button>
                    <button id="submit_btn" class="btn btn-success mt-2 mb-2" type="button">Create</button>
                </div>
            </form>
        </div>
        <div id="showdata" style="margin-top: -51px;"></div>
    </div>

    <!-- CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            var questionCount = 0; // Initialize questionCount variable

            // Adding options dynamically based on answer_type 
            $(document).on('change', '.answer_type', function() {
                var optionContainer = $(this).closest('.mb-3').find('.option_container');
                var selectedType = $(this).val();
                if (selectedType === 'radio' || selectedType === 'checkbox') {
                    optionContainer.empty();
                    optionContainer.append('<div class="d-flex w-25"><div class="input-group"><input type="text" class="form-control option" placeholder="Option" required>' +
                        '<div class="input-group-append"><div class="input-group-text">' +
                        '<input type="checkbox" class="form-check-input correct_answer" name="correct_answers[' + questionCount + '][]"></div></div></div><button type="button" class="btn btn-sm btn-primary ml-2" onclick="addOption(this)">+</button><button type="button" class="btn btn-sm btn-danger ml-2" onclick="removeOption(this)">-</button></div>');

                } else {
                    optionContainer.empty();
                }
            });

            $('.answer_type').each(function() {
                if ($(this).val() !== 'textarea') {
                    $(this).trigger('change');
                }
            });

            // multiple question adding
            $(document).on('click', '#add_question', function() {
                var questionField = '<div class="mb-3"><label class="form-label">Question ' + (questionCount + 1) + '</label>' +
                    '<input type="text" class="form-control w-25 question" placeholder="Enter your question?" name="questions[]">' +
                    '<select class="form-select mt-2 answer_type w-25" name="answer_types[]">' +
                    '<option value="">Select answer type</option>' +
                    '<option value="radio">Radio</option>' +
                    '<option value="checkbox">Checkbox</option>' +
                    '</select>' +
                    '<div class="option_container mt-2"></div>' +
                    '</div>';
                $('#questions_container').append(questionField);
                questionCount++;
            });

            // Modify the addOption function to generate correct indices for each option
            window.addOption = function(button) {
                var optionContainer = $(button).closest('.mb-3').find('.option_container');
                var questionIndex = $(button).closest('.mb-3').index(); // Get the index of the question
                var optionIndex = optionContainer.find('.d-flex').length; // Get the index of the option
                optionContainer.append('<div class="d-flex w-25 mt-2">' +
                    '<div class="input-group">' +
                    '<input type="text" class="form-control option" placeholder="Option" required>' +
                    '<div class="input-group-append">' +
                    '<div class="input-group-text">' +
                    '<input type="checkbox" class="form-check-input correct_answer" name="correct_answers[' + questionIndex + '][]" value="' + optionIndex + '">' + // Use optionIndex as value
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<button type="button" class="btn btn-sm btn-primary ml-2" onclick="addOption(this)">+</button><button type="button" class="btn btn-sm btn-danger ml-2" onclick="removeOption(this)">-</button>' +
                    '</div>');

                optionContainer.append('<input type="hidden" name=" []" value="">');
            };


            window.removeOption = function(button) {
                $(button).closest('.d-flex').remove();
            }

            function showData() {
                $.ajax({
                    type: 'GET',
                    url: 'fetch_subjects.php',
                    success: function(response) {
                        $('#showdata').html(response);
                        $('#showquestion').hide();
                    },
                    error: function() {
                        $('#showdata').html('<div class="alert alert-danger" role="alert">An error occurred while fetching data.</div>');
                    }
                });
            }

            // Click event for the Show Data button
            $(document).on('click', '#showbtn', function() {
                showData();
            });

            $('#submit_btn').click(function(e) {
                e.preventDefault();
                $("#show_btn").hide();
                $("#showbtn").hide();
                var formData = $('#question_form').serializeArray();

                // Serialize options as JSON
                var options = [];
                $('.option_container').each(function() {
                    var questionOptions = [];
                    $(this).find('.option').each(function() {
                        questionOptions.push($(this).val());
                    });
                    options.push(questionOptions);
                });
                formData.push({
                    name: 'options',
                    value: JSON.stringify(options)
                });

                $.ajax({
                    type: 'POST',
                    url: 'insert_data.php',
                    data: formData,
                    success: function(response) {
                        if (response === 'success') {
                            $('#question_form')[0].reset();
                            $("#show_btn").hide();

                        } else {
                            $('#questions_container').html(response);
                        }
                    },
                    error: function() {
                        $('#questions_container').html('<div class="alert alert-danger" role="alert">An error occurred!</div>');
                    }
                });
            });
        });
    </script>
</body>

</html>

