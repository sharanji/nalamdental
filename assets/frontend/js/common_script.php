<?php
/************************************
 * Author      : Vetri
 * Created By  : Vetri
 * Created On  : 29-Oct-2024 
*************************************/
?>
<script>
    function validateNumber(input) {
        input.value = input.value.replace(/\D/g, '');
    }
    $(document).ready(function() {
        // Sanitize spaces for input and textarea fields
        $('input[type="text"], input[type="search"], input[type="email"],input[type="number"], textarea').on('input', function() {
            let value = $(this).val();

            // Remove leading spaces only
            value = value.replace(/^\s+/g, '');

            // Replace multiple consecutive spaces with a single space
            value = value.replace(/\s+/g, ' ');

            // Set the sanitized value back to the field
            $(this).val(value);
        });

        // Apply sanitization for CKEditor content
        if (typeof CKEDITOR !== 'undefined') {
            CKEDITOR.on('instanceReady', function(event) {
                const editorInstance = event.editor;

                // Listen for changes in CKEditor content
                editorInstance.on('change', function() {
                    let content = editorInstance.getData();

                    // Remove initial spaces
                    content = content.replace(/^\s+/g, '');

                    // Replace multiple consecutive spaces with a single space
                    content = content.replace(/\s{2,}/g, ' ');

                    // Update the cleaned content back to CKEditor without moving the cursor
                    editorInstance.setData(content, function() {
                        editorInstance.updateElement();  // Ensures textarea reflects changes
                    });
                });
            });
        }
    });

    document.getElementById("mainCaptcha").addEventListener("copy", function (e) {
        e.preventDefault();
     
    });

    document.getElementById("client_mainCaptcha").addEventListener("copy", function (e) {
        e.preventDefault();
     
    });

    document.getElementById("captchaLetter").addEventListener("copy", function (e) {
        e.preventDefault();
     
    });
    </script>    
