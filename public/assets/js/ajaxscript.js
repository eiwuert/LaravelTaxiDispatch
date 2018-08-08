 //create new product / update existing product
  
  function activate(id,url,variable_name){
	  
	  if (confirm("Do you want Deactivate "+variable_name)) {
		e.preventDefault(); 
		$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })
     
      
        //used to determine the http verb to use [add=POST], [update=PUT]
        var type = "POST"; //for creating new resource
        var product_id = $('#product_id').val();;
        var cur_url = url;
		
		 $.ajax({
            type: type,
            url: cur_url,
            data:  {name: id},
            dataType: 'json',
            success: function (data) {
                console.log(data);
               
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
      }
  }