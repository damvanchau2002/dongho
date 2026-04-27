<script type="text/javascript">
		    /*Search*/
		    $(document).on('click','#ducminh',function()
		    {
                console.log('open')
		        $('.search-bar').addClass('search-bar-active')
		    });
		 
		    $(document).on('click','.search-cancel',function()
		    {
                console.log('close')
		        $('.search-bar').removeClass('search-bar-active')
		    });		    

		    /*User*/
		    $(document).on('click','.user-ico, .already',function()
		    {
		        $('.form').addClass('login-active').removeClass('sign-up-active')
		    });		    


		    $(document).on('click','.sign-up-btn',function()
		    {
		        $('.form').addClass('sign-up-active').removeClass('login-active')
		    });
		 
		    $(document).on('click','.form-cancel',function()
		    {
		        $('.form').removeClass('login-active').removeClass('sign-up-active')
		    });

	</script>