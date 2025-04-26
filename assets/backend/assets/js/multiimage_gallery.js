$(document).ready(function()
{
	//Multi Image -1
	/* $('#add').hide(); */
	$('#remove').hide();
	//var i = $('inputs').size() + 1;
	var i = 1;
	
    $('#add').click(function() 
	{
		
		$('#remove').show();
        if(i != 10)  
		{	
			$('<div class="field file-right"><input type="file" id="first_'+i+'" class="form-control contact-form-control" name="files[]" label="upload_photos_'+i+'" value="" onchange="return validateFileExtension(this,'+i+')" ><br></div>').fadeIn("slow").appendTo('.inputs');
			i++;
			if(i == 10)
			{
				$('#add').hide();
			}
        }
		else
		{ 
			$('#add').hide(); 
		} 
    });
	
	
    $('#remove').click(function() 
	{
        if(i > 1) 
		{
			$('#add').show();
			$('.field:last').remove();
			i--;
			if(i==1)
			{
				$('#remove').hide();
			}
        }
		else if(i == 1)
		{
			alert('No more to remove');
			i = 1;
			return false;
        }
    });
	
	
	
	//Multi Image -2
	/* $('#add').hide(); */
	$('#remove_2').hide();
	//var i = $('inputs').size() + 1;
	var j = 1;
	
    $('#add_2').click(function() 
	{
		
		$('#remove_2').show();
        if(j != 10)  
		{	
			$('<div class="field_2 file-right"><input type="file" id="first_2_'+j+'" class="form-control contact-form-control" name="files_2[]" label="upload_photos_2_'+j+'" value="" onchange="return validateFileExtension_2(this,'+j+')" ><br></div>').fadeIn("slow").appendTo('.inputs_2');
			j++;
			if(j == 10)
			{
				$('#add_2').hide();
			}
        }
		else
		{ 
			$('#add_2').hide(); 
		} 
    });
    
    $('#remove_2').click(function() 
	{
		if(j > 1) 
		{
			$('#add_2').show();
			$('.field_2:last').remove();
			j--;
			if(j==1)
			{
				$('#remove_2').hide();
			}
        }
		else if(j == 1)
		{
			alert('No more to remove');
			j = 1;
			return false;
        }
    });
	
	
	
	
});

/** Multi Image Type & Size Validation **/

function validateFileExtension(fld,count) 
{
	//$('#add').show(); 1048576
	var fileUpload = fld;
	
	if (typeof (fileUpload.files) != "undefined")
	{
		var size = parseFloat( fileUpload.files[0].size / 1024 ).toFixed(2);
		var validSize = 1024 * 4; //1024 - 1Mb multiply 4mb
		
		if( size > validSize )
		{
			alert("Image upload size is 4 MB");
			$('#first_'+ count).val('');
			var value = 1;
			return false;
		}
	}
	
	if(!/(\.png|\.bmp|\.gif|\.jpg|\.jpeg)$/i.test(fld.value))
	{
		alert("Invalid image file type.");      
	   
		if($('#first_'+ count).val()) 
		{
			if(count < 4){
				$('#add').show();
			}else{
				
				$('#add').hide();
				$('#remove').show();
			}
			$('#first_'+ count).val('');
			
			//$('.field').remove();
			
			var value = 1;
		}
		else if($('.textbox').val())
		{
			$('.field:last').remove();
			$('#first').val('');
			$('.field').remove();
			$('#remove').hide();
			var value = 1;
		}
		//fld.focus();        
		return false;   
	}
	
	if(value != 1)	
		return true; 
}

function validateFileExtension_2(fld,count) 
{
	
	//$('#add').show(); 1048576
	var fileUpload = fld;
	
	if (typeof (fileUpload.files) != "undefined")
	{
		var size = parseFloat( fileUpload.files[0].size / 1024 ).toFixed(2);
		var validSize = 1024 * 4; //1024 - 1Mb multiply 4mb
		
		if( size > validSize )
		{
			alert("Image upload size is 4 MB");
			$('#first_2_'+ count).val('');
			var value = 1;
			return false;
		}
	}
	
	if(!/(\.png|\.bmp|\.gif|\.jpg|\.jpeg)$/i.test(fld.value))
	{
		alert("Invalid image file type.");      
	   
		if($('#first_2_'+ count).val()) 
		{
			if(count < 4){
				$('#add_2').show();
			}else{
				
				$('#add_2').hide();
				$('#remove_2').show();
			}
			$('#first_2_'+ count).val('');
			
			//$('.field').remove();
			
			var value = 1;
		}
		else if($('.textbox').val())
		{
			$('.field_2:last').remove();
			$('#first_2').val('');
			$('.field_2').remove();
			$('#remove_2').hide();
			var value = 1;
		}
		//fld.focus();        
		return false;   
	}
	
	if(value != 1)	
		return true; 
}


/** Video Validation **/
function validateFileExtensionVideo(fld) 
{
	var fileUpload = fld;
	
	if (typeof (fileUpload.files) != "undefined")
	{
		var size = parseFloat( fileUpload.files[0].size / 1024 ).toFixed(2);
		var validSize = 1024 * 4; //1024 - 1Mb multiply 4mb
		
		if( size > validSize )
		{
			alert("Video upload size is 4 MB");
			$("#video").val("");
			var value = 1;
			return false;
		}
	}
	
	if(!/(\.avi|\.flv|\.mp4|\.wmv|\.mov|\.mkv)$/i.test(fld.value)) 
	{
		$("#video").val("");
		alert("Invalid viedo file type.");
		var value = 1;		
		return false;   
	}
	
	if(value != 1)
		return true; 
}


/** Single Image Type & Size Validation **/
function validateSingleFileExtension(fld) 
{
	var fileUpload = fld;
	
	if (typeof (fileUpload.files) != "undefined")
	{
		var size = parseFloat( fileUpload.files[0].size / 1024 ).toFixed(2);
		var validSize = 1024 * 4; //1024 - 1Mb multiply 4mb
		
		if( size > validSize )
		{
			alert("Image upload size is 4 MB");
			$('.singleImage').val('');
			var value = 1;
			return false;
		}
		else if(!/(\.png|\.bmp|\.gif|\.jpg|\.jpeg)$/i.test(fld.value))
		{
			alert("Invalid image file type.");      
			$('.singleImage').val('');
			return false;   
		}
		
		if(value != 1)	
			return true; 
	}
}

/** Single Document Type & Size Validation **/
function validateSingleDocumentExtension(fld) 
{
	var fileUpload = fld;
	
	if (typeof (fileUpload.files) != "undefined")
	{
		var size = parseFloat( fileUpload.files[0].size / 1024 ).toFixed(2);
		var validSize = 1024 * 4; //1024 - 1Mb multiply 4mb
		
		if( size > validSize )
		{
			alert("Document upload size is 4 MB");
			$('.singleDocument').val('');
			var value = 1;
			return false;
		}
		else if(!/(\.doc|\.docx|\.pdf)$/i.test(fld.value))
		{
			alert("Invalid document file type.");      
			$('.singleDocument').val('');
			return false;   
		}
		
		if(value != 1)	
			return true; 
	}
}






