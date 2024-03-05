<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suggestion List</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
    #suggestion-list {
        list-style: none;
        padding: 0;
        margin: 0;
        max-height: 150px;
        overflow-y: auto;
        border: 1px solid #ccc;
    }

    #suggestion-list li {
        padding: 8px;
        cursor: pointer;
    }

    #suggestion-list li:hover {
        background-color: #f4f4f4;
    }
    </style>
</head>

<body>

    <label for="userInput">Enter a value:</label>
    <input type="text" id="userInput" oninput="fetchSuggestions(this.value)">

    <ul id="suggestion-list"></ul>

    <script>
    function fetchSuggestions(inputValue) {
        const suggestionList = $('#suggestion-list');

        // Hide the suggestion list if the input is empty
        if (!inputValue.trim()) {
            suggestionList.empty();
            return;
        }

        console.log('Fetching suggestions for:', inputValue);
        // AJAX request to fetch suggestions from the server
        $.ajax({
            url: '../admin/update_qty.php', // Update with the correct path to your PHP script
            method: 'POST',
            data: {
                input: inputValue
            },
            dataType: 'json',
            success: function(data) {
                const suggestions = data.suggestions;

                // Check if suggestions is an array before using forEach
                if (Array.isArray(suggestions) && suggestions.length > 0) {
                    suggestionList.empty();

                    suggestions.forEach(suggestion => {
                        const li = $('<li>').text(suggestion
                            .email); // Adjust this based on the structure of your database
                        li.on('click', function() {
                            $('#userInput').val(suggestion
                                .email
                            ); // Adjust this based on the structure of your database
                            suggestionList.empty();
                        });
                        suggestionList.append(li);
                    });
                } else {
                    // Display a message when no suggestions are found
                    suggestionList.html('<li>No suggestions found</li>');
                }
            },
            error: function(error) {
                console.error('Error fetching suggestions:', error);
            }
        });

    }
    </script>

</body>

</html>