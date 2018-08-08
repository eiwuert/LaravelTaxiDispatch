<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"
        type="text/javascript"></script>
<script>

$.ajax({
           url: "http://maps.huge.info/zipv0.pl/?ZIP=60602",
           dataType: "xml",
		   type:'get',
		   async:true,
		   beforeSend: function( xhr ) {
				alert();
			  },
           success: function(data) {
               alert('suc');
               console.log(data);
               },
               error: function(jqXHR, textStatus, error) {
                   alert('errt');
                   console.log(jqXHR);
                   console.log(textStatus);
               }
               //async: false
       });

</script>