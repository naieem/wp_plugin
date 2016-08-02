jQuery(document).ready( function() {
	//alert("fgfdgf");
			jQuery(".custom_cat ul.children").parent("li").addClass("cat-parent");
			jQuery(".custom_cat .cat-parent").append('<div class="list_parent"><i class="fa fa-plus" aria-hidden="true" style="color:#666666;"></i></div>');
			jQuery(".custom_cat .list_parent").click(function(e) {
				e.preventDefault();
				if(jQuery(this).hasClass("droped"))
				{
				jQuery(this).prev("ul.children").slideUp("fast");
				jQuery(this).removeClass("droped").html('<i class="fa fa-plus" aria-hidden="true" style="color:#666666;"></i>');
				}
				else{
                jQuery(this).prev("ul.children").slideDown("fast");
				jQuery(this).addClass("droped").html('<i class="fa fa-minus" aria-hidden="true" style="color:#666666;"></i>');
				}
            });
   
});