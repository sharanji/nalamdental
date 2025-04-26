<?php
/************************************
 * Author      : Vetri
 * Created By  : Vetri
 * Created On  : 29-Oct-2024 
*************************************/
?>
<script>

    function sectionShow(section_type,show_hide_type)
    {	
        if(section_type == 'FIRST_SECTION')
        {
            if(show_hide_type == 'SHOW')
            {
                $(".first_sec_hide").hide();
                $(".first_sec_show").show();

                $(".first_section").hide("slow");
            }
            else if(show_hide_type == 'HIDE')
            {
                $(".first_sec_hide").show();
                $(".first_sec_show").hide();

                $(".first_section").show("slow");
            }
        }
        else if(section_type == 'SECOND_SECTION')
        {
            if(show_hide_type == 'SHOW')
            {
                $(".sec_sec_hide").hide();
                $(".sec_sec_show").show();

                $(".sec_section").hide("slow");
            }
            else if(show_hide_type == 'HIDE')
            {
                $(".sec_sec_hide").show();
                $(".sec_sec_show").hide();

                $(".sec_section").show("slow");
            }
        }
        else if(section_type == 'THIRD_SECTION')
        {
            if(show_hide_type == 'SHOW')
            {
                $(".thi_sec_hide").hide();
                $(".thi_sec_show").show();

                $(".thi_section").hide("slow");
            }
            else if(show_hide_type == 'HIDE')
            {
                $(".thi_sec_hide").show();
                $(".thi_sec_show").hide();

                $(".thi_section").show("slow");
            }
        }
        else if(section_type == 'FOURTH_SECTION')
        {
            if(show_hide_type == 'SHOW')
            {
                $(".fou_sec_hide").hide();
                $(".fou_sec_show").show();

                $(".fou_section").hide("slow");
            }
            else if(show_hide_type == 'HIDE')
            {
                $(".fou_sec_hide").show();
                $(".fou_sec_show").hide();

                $(".fou_section").show("slow");
            }
        }
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


        
    </script>    
