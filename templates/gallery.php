<?php global $post,$spc,$wpdb,$spc_captcha_activated;?>

 <script type="text/javascript" src="<?PHP echo WP_PLUGIN_URL;?>/slick-photocontest/js/lightbox/jquery.lightbox-0.5.pack.js"></script>
<link rel='stylesheet'  href='<?PHP echo WP_PLUGIN_URL;?>/slick-photocontest/js/lightbox/css/jquery.lightbox-0.5.css' type='text/css' media='' />

    <script type="text/javascript">
    $(document).ready(function () {
    	var lightbox_setting = {
    	    					'imageLoading': '<?PHP echo WP_PLUGIN_URL;?>/slick-photocontest/js/lightbox/images/lightbox-ico-loading.gif',		// (string) Path and the name of the loading icon
				    			'imageBtnPrev':	'<?PHP echo WP_PLUGIN_URL;?>/slick-photocontest/js/lightbox/images/lightbox-btn-prev.gif',			// (string) Path and the name of the prev button image
				    			'imageBtnNext':	'<?PHP echo WP_PLUGIN_URL;?>/slick-photocontest/js/lightbox/images/lightbox-btn-next.gif',			// (string) Path and the name of the next button image
				    			'imageBtnClose': '<?PHP echo WP_PLUGIN_URL;?>/slick-photocontest/js/lightbox/images/lightbox-btn-close.gif',		// (string) Path and the name of the close btn
				    			'imageBlank': '<?PHP echo WP_PLUGIN_URL;?>/slick-photocontest/js/lightbox/images/lightbox-blank.gif',			// (string) Path and the name of a blank image (one pixel)
				    	   	   };
    	$("#spc_gallery a").lightBox(lightbox_setting); 
    });
    </script>
   	<style type="text/css">
	/* jQuery lightBox plugin - Gallery style */
	#spc_gallery {
		padding: 10px;
	}
	#spc_gallery ul { list-style: none; }
	#spc_gallery ul li { display: inline; }
	#spc_gallery ul img {
		border: 5px solid #3e3e3e;
		border-width: 5px 5px 20px;
	}
	#spc_gallery ul a:hover img {
		border: 5px solid grey;
		border-width: 5px 5px 20px;
		color: grey;
	}
	#spc_gallery ul a:hover { color: #fff; }
	</style>

	<div id="spc_gallery">
    <?php $photos = $spc->get_photo_list(array($wpdb->prefix."comments.comment_post_ID="=>$post->ID));?>
    <?php foreach($photos as $photo):?>
       <div style="float:left;margin:5px 5px 5px 0px;width:100px;height:100px;background:url('<?php echo SPC_UPLOADED_URL."thumb_".$photo['participant_photo']?>') no-repeat;  ">
            <a href="<?php echo SPC_UPLOADED_URL.$photo['participant_photo']?>" title="<?php echo $photo['participant_photo_description']?>">
                <div style="width:100px;height:90px;"></div>
            </a>
            <div style="width:100px;height:10px;" ><button onclick="$('#spc_gallery').toggle();$('#spc_vote').toggle();document.getElementByTagName('comment_id').value =<?php echo $photo['participant_commentID'];?>;" style="text-decoration:none;font-size:10px;font-weight:bold;margin-left:-15px;border:none;background:none;color:#0066CC;">Like</button></div>
        </div>
    <?php endforeach;?>  
        <div style="clear:both;"></div> 
   </div>
   
   <div id="spc_vote" style="display:none">
     <a onclick="$('#spc_gallery').toggle();$('#spc_vote').toggle();" style="text-decoration:none;font-size:10px;font-weight:bold;">Like</a>
     <?php spc_like_form();?>
   </div>
   
   <script type="text/javascript">
   $(document).ready(function(){
	    if(document.location.href.match(/#(\w.+)/)) {
			var loc = RegExp.$1;
			if(loc == 'like'){
				$('#spc_gallery').toggle();$('#spc_vote').toggle();
			}
		} 
   });
   </script>