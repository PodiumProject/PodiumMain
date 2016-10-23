(function ($) {
    $(document).ready(function () {

	var json_str = options.replace(/&quot;/g, '"');
	var selected;
	var btn1Selected=false;
	var btn2Selected=false;
	var postboxOn=true;
	var youtubePlaylistSelected=false;
	var youtubeChannelSelected=false;
	var countVideos;
	var feedTitle;
	
	options = jQuery.parseJSON(json_str);
	//OPTIONS//
	addOption("general", "instanceName", "text", "Player name","");
	addOption("general", "videoPlayerWidth", "text", "Total player width [px]",1006);
	addOption("general", "videoPlayerHeight", "text", "Total player height [px]",420);
	addOption("general", "responsive", "checkbox", "Responsive",false);
	addOption("general", "videoPlayerShadow", "dropdown", "Select player shadow","effect1", ["effect1", "effect2", "effect3", "effect4", "effect5", "effect6", "off"]);
	addOption("playlist", "playlistBehaviourOnPageload", "dropdown", "Playlist behaviour on page load","opened", ["closed", "opened"]);
	addOption("playlist", "playlist", "dropdown", "Playlist","Right playlist", ["Right playlist", "Off"]);
	addOption("youtube", "youtubeControls", "dropdown", "Select YouTube player controls","custom controls", ["custom controls", "default controls"]);
	addOption("youtube", "youtubeSkin", "dropdown", "Select YouTube skin","dark", ["dark", "light"]);
	addOption("youtube", "youtubeColor", "dropdown", "Select YouTube color","red", ["red", "white"]);
	addOption("youtube", "youtubeQuality", "dropdown", "Select YouTube quality","default", ["default", "small", "medium", "large", "hd720", "hd1080", "highres"]);
	addOption("youtube", "youtubeShowRelatedVideos", "dropdown", "Show YouTube related videos","Yes", ["Yes", "No"]);
	addOption("vimeo", "vimeoColor", "text", "Vimeo player color","00adef");
	addOption("general", "colorAccent", "text", "Player color accent","#cc181e");
//addOption("youtube", "youtubeShowRelatedVideos", "dropdown", "Show related YouTube videos", "Yes", ["Yes","No"]);
	addOption("behavior", "onFinish", "dropdown", "On video finish","playNextOnFinish", ["Play next video","Restart video", "Stop video"]);
	addOption("behavior", "autoplay", "checkbox", "Autoplay",false);
	addOption("behavior", "loadRandomVideoOnStart", "dropdown", "Random video on page load", "No", ["Yes","No"]);
	addOption("general", "posterImg", "selectImage", "Poster image","");
	addOption("HTML5", "hideVideoSource", "checkbox", "Hide video source",false);
	addOption("HTML5", "showAllControls", "checkbox", "Show all controls",true);
	addOption("HTML5", "rightClickMenu", "checkbox", "Right-click menu",true);
	addOption("HTML5", "autohideControls", "text", "Auto hide controls (sec)",2);
	addOption("HTML5", "hideControlsOnMouseOut", "dropdown", "Hide controls on mouse rollout", "No", ["Yes","No"]);
	addOption("behavior", "shuffle", "dropdown", "Shuffle videos on finish", "No", ["Yes","No"]);
	addOption("playlist", "playlistScrollType", "dropdown", "Select scrollbar type", "light", ["light","minimal","light-2","light-3","light-thick","light-thin","inset","inset-2","inset-3","rounded","rounded-dots","3d"]);
	addOption("HTML5", "fullscreen", "dropdown", "Fullscreen","Fullscreen native", ["Fullscreen native","Fullscreen browser"]);
	addOption("HTML5", "nowPlayingText", "dropdown", "Show now playing title","Yes", ["Yes","No"]);
	addOption("HTML5", "infoShow", "dropdown", "Show info button","Yes", ["Yes","No"]);
	addOption("HTML5", "shareShow", "dropdown", "Show share button","Yes", ["Yes","No"]);
	addOption("HTML5", "facebookShow", "dropdown", "Show facebook button","Yes", ["Yes","No"]);
	addOption("HTML5", "twitterShow", "dropdown", "Show twitter button","Yes", ["Yes","No"]);
	addOption("HTML5", "mailShow", "dropdown", "Show mail button","Yes", ["Yes","No"]);
	addOption("HTML5", "facebookShareName", "text", "Facebook share / Name","Elite video player");
	addOption("HTML5", "facebookShareLink", "text", "Facebook share / Link","http://codecanyon.net/item/elite-video-player-wordpress-plugin/10496434");
	addOption("HTML5", "facebookShareDescription", "textarea", "Facebook share / Description","Elite Video Player is stunning, modern, responsive, fully customisable high-end video player for WordPress that support advertising and the most popular video platforms like YouTube, Vimeo or self-hosting videos (mp4).");
	addOption("HTML5", "facebookSharePicture", "selectImage", "Facebook share / Picture","");
	addOption("HTML5", "twitterText", "text", "Twitter share / Text","Elite video player");
	addOption("HTML5", "twitterLink", "text", "Twitter share / Link","http://codecanyon.net/item/elite-video-player-wordpress-plugin/10496434");
	addOption("HTML5", "twitterHashtags", "text", "Twitter share / Hashtags","wordpressvideoplayer");
	addOption("HTML5", "twitterVia", "text", "Twitter share / Via","Creative media");
	addOption("HTML5", "googlePlus", "text", "Google + / Link","http://codecanyon.net/item/elite-video-player-wordpress-plugin/10496434");
	addOption("general", "logoShow", "dropdown", "Logo","Yes", ["Yes","No"]);
	addOption("general", "logoPath", "selectImage", "Select logo image","");
	addOption("general", "logoPosition", "dropdown", "Logo position","bottom-right", ["bottom-right","bottom-left"]);
	addOption("general", "logoClickable", "dropdown", "Logo clickable","Yes", ["Yes","No"]);
	addOption("general", "logoGoToLink", "text", "Logo Link URL","http://codecanyon.net/");
	addOption("general", "allowSkipAd", "checkbox", "Allow users to skip ad",true);
	addOption("HTML5", "embedShow", "dropdown", "Show embed button","Yes", ["Yes","No"]);
	addOption("HTML5", "embedCodeSrc", "textarea", "Embed code iframe src","http://yourwebsite.com/player/deploy/index.html");
	addOption("HTML5", "embedCodeW", "text", "Embed code iframe width","746");
	addOption("HTML5", "embedCodeH", "text", "Embed code iframe height","420");
	addOption("HTML5", "embedShareLink", "text", "Share your site URL","http://codecanyon.net/");
	
	selectPlayerType("videoType", "dropdown", "Select video player type","- Select player type -", ["- Select player type -", "HTML5 (self-hosted)", "YouTube","YouTube playlist", "YouTube channel", "Vimeo", "Image"]);

	$('.postbox .hndle').click(function(e){
		$(this).parent().toggleClass("closed")
	});
	$('.postbox .handlediv').click(function(e){
		$(this).parent().toggleClass("closed")
	});
	
	btn1Selected=true;
	toggle_options();
	
	$('.btn1').click(function(e){
		btn1Selected=true;
		btn2Selected=false;
		toggle_options();
	});
	$('.btn2').click(function(e){
		btn1Selected=false;
		btn2Selected=true;
		toggle_options();
	});
	
	function toggle_options(){
		if(btn1Selected)
		{
			$('.options_general').show();
			$('.options_videos').hide();
			$('.btn1').css({
				background:"#2ea2cc",
				"border-color": "#2ea2cc", 
				"border-width":"1px", 
				"border-style":"solid"
			});
			$('#btn1-title').css({
				color:"#ffffff"
			});
			$('.btn2').css({
				background:"#f2f2f2",
				"border": ""
			});
			$('#btn2-title').css({
				color:"#2ea2cc"
			});
		}
		else if(btn2Selected)
		{
			$('.options_general').hide();
			$('.options_videos').show();
			$('.btn1').css({
				background:"#f2f2f2",
				"border": ""
			});
			$('#btn1-title').css({
				color:"#2ea2cc"
			});
			$('.btn2').css({
				background:"#2ea2cc",
				"border-color": "#2ea2cc", 
				"border-width":"1px", 
				"border-style":"solid"
			});
			$('#btn2-title').css({
				color:"#ffffff"
			});
		}
	}
	
	function addOption(section,name,type,desc,defaultValue,values){

		var table = $("#player-options-"+section+"");
		var tableBody = table.find('tbody');
		var row = $('<tr valign="top"  class="field-row"></tr>').appendTo(tableBody);
		var th = $('<th scope="row">'+desc+'</th>').appendTo(row);
		var td = $('<td></td>').appendTo(row);

		switch(type){
			case "text":
				var input = $('<input type="text" name="'+name+'"/>').appendTo(td);
				if(typeof(options[name]) != 'undefined'){
					input.attr("value",options[name]);
				}else {
					input.attr('value',defaultValue);
				}
				break;
			case "textarea":
				// var a = stripslashes(options[name]);
				// var b = stripslashes(defaultValue);
			    var textarea = $('<textarea type="text" name="'+name+'" cols=45" rows="1"></textarea>').appendTo(td);
				if(typeof(options[name]) != 'undefined'){
					textarea.attr("value",options[name]);
					// textarea.attr("value",a);
				}else {
					textarea.attr('value',defaultValue);
					// textarea.attr('value',b);
				}
				break;
			case "checkbox":
				var inputHidden = $('<input type="hidden" name="'+name+'" value="false"/>').appendTo(td);
				var input = $('<input type="checkbox" name="'+name+'" value="true"/>').appendTo(td);
				if(typeof(options[name]) != 'undefined'){
					input.attr("checked",options[name]);
				}else {
					input.attr('checked',defaultValue);
				}
				break;
			case "selectImage":
				var input = $('<input type="text" name="'+name+'"/><a class="select-image-button button-secondary button80" href="#">Select image</a>').appendTo(td);
				if(typeof(options[name]) != 'undefined'){
					input.attr("value",options[name]);
				}else {
					input.attr('value',defaultValue);
				}
				break;
			case "dropdown":
				var select = $('<select name="'+name+'">').appendTo(td);
				for ( var i = 0; i < values.length; i++ )
				{
					var option = $('<option name="'+name+'" value="'+values[i]+'">'+values[i]+'</option>').appendTo(select);
					if(typeof(options[name]) != 'undefined')
					{
						if(options[name] == values[i])
						{
							option.attr('selected','true');
						}
					}
					else if(defaultValue == values[i])
					{
						option.attr('selected','true');
					}
				}
				break;
		}

	}
	function selectPlayerType(name,type,desc,defaultValue,values){

		var table = $("#player-options-table-right");
		var tableBody = table.find('tbody');
		var row = $('<tr valign="top"  class="field-row"></tr>').appendTo(tableBody);
		var th = $('<th scope="row">'+desc+'</th>').appendTo(row);
		var td = $('<td></td>').appendTo(row);

		switch(type){
			case "dropdown":
				var select = $('<select id="type" name="'+name+'">').appendTo(td);
				for ( var i = 0; i < values.length; i++ )
				{
					var option = $('<option name="'+name+'" value="'+values[i]+'">'+values[i]+'</option>').appendTo(select);
					if(typeof(options[name]) != 'undefined')
					{
						if(options[name] == values[i])
						{
							option.attr('selected','true');
						}
					}
					else if(defaultValue == values[i])
					{
						option.attr('selected','true');
					}
				}
				break;
		}

	}
	function youtubePlaylistInput(name,type,desc,defaultValue,values){

		var table = $("#player-options-table-right");
		var tableBody = table.find('tbody');
		var row = $('<tr valign="top"  class="field-row youtube-playlist-field-row"></tr>').appendTo(tableBody);
		var th = $('<th scope="row">'+desc+'</th>').appendTo(row);
		var td = $('<td></td>').appendTo(row);

		switch(type){
			case "text":
				var input = $('<input id="youtubePlaylistInput" type="text" name="'+name+'"/>').appendTo(td);
				if(typeof(options[name]) != 'undefined'){
					input.attr("value",options[name]);
				}else {
					input.attr('value',defaultValue);
				}
				break;
		}

	}
	function youtubeChannelInput(name,type,desc,defaultValue,values){

		var table = $("#player-options-table-right");
		var tableBody = table.find('tbody');
		var row = $('<tr valign="top"  class="field-row youtube-channel-field-row"></tr>').appendTo(tableBody);
		var th = $('<th scope="row">'+desc+'</th>').appendTo(row);
		var td = $('<td></td>').appendTo(row);
		switch(type){
			case "text":
				var input = $('<input id="youtubeChannelInput" type="text" name="'+name+'"/>').appendTo(td);
				if(typeof(options[name]) != 'undefined'){
					input.attr("value",options[name]);
				}else {
					input.attr('value',defaultValue);
				}
				break;
		}

	}
	
	$('#type').change(function() {
		removeVideos();
		selected = $('#type option:selected').val();
		
		if(selected=="YouTube playlist" && !youtubePlaylistSelected)
		{
			$(".youtube-channel-field-row").remove();
			youtubePlaylistSelected=true;
			youtubeChannelSelected=false;
			youtubePlaylistInput("youtubePlaylistID", "text", "YouTube playlist ID","youtube playlist ID");
			
			$('#youtubePlaylistInput').change(function() {
				youtubePlaylistSelected=false;
				removeVideos();
				loadYoutubeData($(this).val());
			});
			loadYoutubeData(options.youtubePlaylistID);
			$("#add-new-video-button").hide();
		}
		else if(selected=="YouTube channel" && !youtubeChannelSelected)
		{
			$(".youtube-playlist-field-row").remove();
			youtubeChannelSelected=true;
			youtubePlaylistSelected=false;
			youtubeChannelInput("youtubeChannelID", "text", "YouTube channel ID","youtube channel ID");
			
			$('#youtubeChannelInput').change(function() {
				youtubeChannelSelected=false;
				removeVideos();
				loadYoutubeData($(this).val());
			});
			loadYoutubeData(options.youtubeChannelID);
			$("#add-new-video-button").hide();
		}
		else if(selected=="- Select player type -" || selected=="HTML5 (self-hosted)" || selected=="YouTube" || selected=="Vimeo" || selected=="Image")
		{
			$(".youtube-playlist-field-row").remove();
			$(".youtube-channel-field-row").remove();
			youtubePlaylistSelected=false;
			youtubeChannelSelected=false;
			self.options.youtubePlaylistID = "";
			$("#add-new-video-button").show();
		}
		addListeners();
	});
	selected = $('#type option:selected').val();
	
	//if playlist
	if(selected=="YouTube playlist")
	{
		$("#add-new-video-button").hide();
		youtubePlaylistSelected=true;
		youtubePlaylistInput("youtubePlaylistID", "text", "YouTube playlist ID","youtube playlist ID");
		
		$('#youtubePlaylistInput').change(function() {
			youtubePlaylistSelected=false;
			removeVideos();
			loadYoutubeData($(this).val());
		});
		loadYoutubeData(options.youtubePlaylistID);
	}//if channel
	else if(selected=="YouTube channel")
	{
		$("#add-new-video-button").hide();
		youtubeChannelSelected=true;
		youtubeChannelInput("youtubeChannelID", "text", "YouTube channel ID","youtube channel ID");
		
		$('#youtubeChannelInput').change(function() {
			youtubeChannelSelected=false;
			removeVideos();
			loadYoutubeData($(this).val());
		});
		loadYoutubeData(options.youtubeChannelID);
	}
	
	// options

	//for all videos in  options.videos create video
	for(var i= 0; i < options.videos.length; i++){
		var video = options.videos[i];
		var videosContainer = $("#videos-container");
		
		switch(options.videoType){
			case "HTML5 (self-hosted)":
			
                var videoItem = createVideoHtml_html5("videos["+i+"]", i, video.title, video.mp4, video.prerollAD, video.prerollGotoLink, video.preroll_mp4,video.prerollSkipTimer, video.description, video.thumbImg, video.info, video.midrollAD, video.midrollAD_displayTime, video.midrollGotoLink, video.midroll_mp4, video.midrollSkipTimer, video.postrollAD, video.postrollGotoLink, video.postroll_mp4, video.postrollSkipTimer, video.popupAdShow, video.popupImg, video.popupAdStartTime, video.popupAdEndTime, video.popupAdGoToLink);
                videoItem.appendTo(videosContainer);
				break;
			case "YouTube":
                var videoItem = createVideoHtml_youtube("videos["+i+"]", i, video.title , video.youtubeID, video.mp4, video.prerollAD, video.prerollGotoLink,video.preroll_mp4,video.prerollSkipTimer, video.midrollAD, video.midrollAD_displayTime, video.midrollGotoLink, video.midroll_mp4, video.midrollSkipTimer, video.postrollAD, video.postrollGotoLink, video.postroll_mp4, video.postrollSkipTimer, video.popupAdShow, video.popupImg, video.popupAdStartTime, video.popupAdEndTime, video.popupAdGoToLink ,video.description, video.thumbImg,video.info);
                videoItem.appendTo(videosContainer);
				break;
			case "Vimeo":
                var videoItem = createVideoHtml_vimeo("videos["+i+"]", i, video.title , video.vimeoID, video.mp4,  video.prerollAD, video.prerollGotoLink,video.preroll_mp4,video.prerollSkipTimer, video.description, video.thumbImg, video.midrollAD, video.midrollAD_displayTime, video.midrollGotoLink, video.midroll_mp4, video.midrollSkipTimer, video.postrollAD, video.postrollGotoLink, video.postroll_mp4, video.postrollSkipTimer, video.popupAdShow, video.popupImg, video.popupAdStartTime, video.popupAdEndTime, video.popupAdGoToLink);
                videoItem.appendTo(videosContainer);
				break;
			case "Image":
                var videoItem = createVideoHtml_image("videos["+i+"]", i, video.title, video.description, video.thumbImg, video.imageUrl, video.imageTimer);
                videoItem.appendTo(videosContainer);
				break;
		}
	}
	
	function removeVideos(){
		
		options.videos = [];
		countVideos=-1;
		$(".videosToggle2").parent().animate({
				'opacity': 0
			}, 300).slideUp(300, function () {
					$(this).remove();
				});
	}
	function loadYoutubeData(inputVal){
		
		//load playlist data and create video preroll sections
		// var channelURL = 'http://gdata.youtube.com/feeds/api/users/'+inputVal+'/uploads?alt=json&orderby=published';
		// var playListURL = 'http://gdata.youtube.com/feeds/api/playlists/'+inputVal+'?v=2&alt=json';
		var channelURL = 'https://www.googleapis.com/youtube/v3/search?order=date&maxResults=50&part=snippet&channelId='+inputVal+'&key=AIzaSyClbVoeyPLBHb9n6Abm0z-AlrzNKeWLKTc';
		var playListURL = 'https://www.googleapis.com/youtube/v3/playlistItems?&maxResults=50&part=snippet&playlistId='+inputVal+'&key=AIzaSyClbVoeyPLBHb9n6Abm0z-AlrzNKeWLKTc';
		var url;
		
		if(selected=="YouTube playlist")
			url = playListURL;
		else if(selected=="YouTube channel")
			url = channelURL; 
		
		// if(inputVal != "" && inputVal != "enter youtube channel ID" && inputVal != "enter youtube playlist ID"){
		if(inputVal != "" && inputVal != $(".youtubeChannelInput").val() && inputVal != $(".youtubePlaylistInput").val()){
			
			$.ajax({
				url: url,
				success: function(data) {
					self.data = data;
					// console.log(data)
					$.each(data.items, function(i, item) {
					 
						countVideos=countVideos+1;
						feedTitle = item.snippet.title;
						
						//no videos
						if(jQuery.isEmptyObject(options.videos))
						{
							var videoItem = createVideoHtml_youtubePlaylist("videos["+countVideos+"]", countVideos,feedTitle,/*, "title",*/ /*"videoType",*/ /*"youtubeID",//*, "vimeoID",*/ /*"mp4",*/ /*"webm",*/ "prerollAD","prerollGotoLink","preroll_mp4","prerollSkipTimer","midrollAD","midrollAD_displayTime","midrollGotoLink","midroll_mp4","midrollSkipTimer","postrollAD","postrollGotoLink","postroll_mp4","postrollSkipTimer", /*"webmAD",*/ /*"description", "thumbImg" */ "popupAdShow", "popupImg", "popupAdStartTime", "popupAdEndTime", "popupAdGoToLink"/*, "info"*//*, "textAdShow","textAd","textAdStartTime","textAdEndTime","textAdGoToLink"*/);
						}
						//videos exists
						else
						{
							var video = options.videos[countVideos];
							var videoItem = createVideoHtml_youtubePlaylist("videos["+countVideos+"]", countVideos,feedTitle,/*, "title",*/ /*"videoType",*/ /*"youtubeID",//*, "vimeoID",*/ /*"mp4",*/ /*"webm",*/ video.prerollAD,video.prerollGotoLink,video.preroll_mp4,video.prerollSkipTimer,video.midrollAD,video.midrollAD_displayTime,video.midrollGotoLink,video.midroll_mp4,video.midrollSkipTimer,video.postrollAD,video.postrollGotoLink,video.postroll_mp4,video.postrollSkipTimer /*"webmAD",*/ /*"description", "thumbImg" */,video.popupAdShow, video.popupImg, video.popupAdStartTime, video.popupAdEndTime, video.popupAdGoToLink/*, "info"*//*, "textAdShow","textAd","textAdStartTime","textAdEndTime","textAdGoToLink"*/);
						}
						
						
						var videosContainer = $("#videos-container");
						videoItem.appendTo(videosContainer);
						
						addListeners();
					});
				}
			});
		}
	}

	$(".tabs").tabs();
	$(".ui-sortable").sortable();
	addListeners();

	if ($('.video').length > 0) {
		// it exists
		countVideos=videosContainer.find(".video").length-1;}
	else{
		countVideos=-1;}

	$('#add-new-video-button').click(function (e) {

		e.preventDefault();
		
		if(selected=="- Select player type -")
			countVideos = -1;
		else
			countVideos=countVideos+1;

        switch(selected){
            case "HTML5 (self-hosted)":
                var videoItem = createVideoHtml_html5("videos["+countVideos+"]", countVideos, "title", "mp4", "prerollAD","prerollGotoLink","preroll_mp4","prerollSkipTimer", "description", "thumbImg", "info", "midrollAD", "midrollAD_displayTime", "midrollGotoLink", "midroll_mp4", "midrollSkipTimer", "postrollAD", "postrollGotoLink", "postroll_mp4", "postrollSkipTimer", "popupAdShow", "popupImg", "popupAdStartTime", "popupAdEndTime", "popupAdGoToLink");
                var videosContainer = $("#videos-container");
                videoItem.appendTo(videosContainer);
                break;
            case "YouTube":
                var videoItem = createVideoHtml_youtube("videos["+countVideos+"]", countVideos, "title", "youtubeID", "mp4", "prerollAD","prerollGotoLink","preroll_mp4","prerollSkipTimer", "midrollAD", "midrollAD_displayTime", "midrollGotoLink", "midroll_mp4", "midrollSkipTimer", "postrollAD", "postrollGotoLink", "postroll_mp4", "postrollSkipTimer", "popupAdShow", "popupImg", "popupAdStartTime", "popupAdEndTime", "popupAdGoToLink", "description", "thumbImg","info");
                var videosContainer = $("#videos-container");
                videoItem.appendTo(videosContainer);
                break;
            case "Vimeo":
                var videoItem = createVideoHtml_vimeo("videos["+countVideos+"]", countVideos, "title", "vimeoID", "mp4", "prerollAD","prerollGotoLink","preroll_mp4","prerollSkipTimer", "description", "thumbImg", "midrollAD", "midrollAD_displayTime", "midrollGotoLink", "midroll_mp4", "midrollSkipTimer", "postrollAD", "postrollGotoLink", "postroll_mp4", "postrollSkipTimer", "popupAdShow", "popupImg", "popupAdStartTime", "popupAdEndTime", "popupAdGoToLink");
                var videosContainer = $("#videos-container");
                videoItem.appendTo(videosContainer);
                break;
			case "Image":
                var videoItem = createVideoHtml_image("videos["+countVideos+"]", countVideos, "title", "description", "thumbImg", "imageUrl", "imageTimer");
                var videosContainer = $("#videos-container");
                videoItem.appendTo(videosContainer);
                break;
        }

		addListeners();

		return;
	});

	function addListeners(){
		$('.submitdelete').click(function () {
		/* console.log("delete"); */
			$(this).parent().parent().parent().parent().animate({
				'opacity': 0
			}, 100).slideUp(100, function () {
					$(this).remove();
				});			
		});
		if(selected=="YouTube playlist")
		$('.submitdelete').parent().hide();
		else if(selected=="YouTube channel")
		$('.submitdelete').parent().hide();
		else
		$('.submitdelete').parent().show();
		
		$('.select-image-button').click(function(e) {
			e.preventDefault();
			
			var imageURLInput = $(this).parent().find("input");
			var custom_uploader = wp.media({
				title: 'Select image',
				button: {
					text: 'Select'
				},
				multiple: false  // Set this to true to allow multiple files to be selected
			})
			.on('select', function() {
				var arr = custom_uploader.state().get('selection');
				var url = arr.models[0].attributes.url;
				imageURLInput.val(url);
			})
			.open();
		});

		jQuery('.videosToggle1').each(function loopingItems()
		{
			jQuery(this).unbind('click');
		});
		
		jQuery('.videosToggle1').each(function loopingItems()
		{
			jQuery(this).click( function() 
			{
				jQuery(jQuery(this).parent().get(0)).toggleClass('closed');
			});
		});
		
		jQuery('.videosToggle2').each(function loopingItems()
		{
			jQuery(this).unbind('click');
		});
		
		jQuery('.videosToggle2').each(function loopingItems()
		{
			jQuery(this).click( function() 
			{
				jQuery(jQuery(this).parent().get(0)).toggleClass('closed');
			});
		});
	}
	
    function createVideoHtml_html5(prefix, id, title, mp4, prerollAD, prerollGotoLink, preroll_mp4, prerollSkipTimer,  description, thumbImg, info, midrollAD, midrollAD_displayTime, midrollGotoLink, midroll_mp4, midrollSkipTimer, postrollAD, postrollGotoLink, postroll_mp4, postrollSkipTimer, popupAdShow, popupImg, popupAdStartTime, popupAdEndTime, popupAdGoToLink)
	{
		if (typeof(prerollAD) == 'undefined' || prerollAD != "yes") 
			prerollAD = "no";
		if (typeof(midrollAD) == 'undefined' || midrollAD != "yes") 
			midrollAD = "no";
		if (typeof(postrollAD) == 'undefined' || postrollAD != "yes") 
			postrollAD = "no";
		if (typeof(popupAdShow) == 'undefined' || popupAdShow != "yes") 
			popupAdShow = "no";
		
		/*if (typeof(textAdShow) == 'undefined' || textAdShow != "yes") 
			textAdShow = "no";*/
			
		var markup = $(
		+'<div class="videos">'
			+'<div class="postbox">'
				+'<div class="handlediv videosToggle2" title="Click to toggle"></div>'
				+'<h3 class="hndle videosToggle1">'
					+'<span id="sortable-title">Video Title '+id+' : '+title+'</span>'
				+'</h3>'
				+'<div class="inside postBoxInside">'
					
				+'<div id="'+id+'"class="video">'
					+'<text id="video-section-count"> HTML5 video '+id+'</text>'
						+ '<table class="form-table" id="player-videos-table">'
							+'<tbody>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Video title</th>'
									+'<td><input id="video-title" name="'+prefix+'[title]" type="text" placeholder="Enter video title" value="'+title+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Description</th>'
									+'<td><textarea id="video-description" name="'+prefix+'[description]" type="text" cols="30" rows="2" placeholder="Enter video description">'+description+'</textarea></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Info</th>'
									+'<td><textarea id="video-info" name="'+prefix+'[info]" type="text" cols="30" rows="2" placeholder="Enter video info">'+info+'</textarea></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Thumbnail image</th>'
									+'<td><input id="image-path" name="'+prefix+'[thumbImg]" type="text" placeholder="Thumbnail URL" value="'+thumbImg+'" /><a class="select-image-button button-secondary button80" href="#">Select image</a></td>'
								+'</tr>'
//								+'<tr valign="top" class="field-row">'
//									+'<th scope="row">YouTube ID</th>'
//									+'<td><input id="youtube-id" name="'+prefix+'[youtubeID]" type="text" placeholder="Enter youtube ID" value="'+youtubeID+'" /></td>'
//								+'</tr>'
//								+'<tr valign="top" class="field-row">'
//									+'<th scope="row">Vimeo ID</th>'
//									+'<td><input id="youtube-id" name="'+prefix+'[vimeoID]" type="text" placeholder="Enter vimeo ID" value="'+vimeoID+'" /></td>'
//								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">MP4 video URL</th>'
									+'<td><input id="video-mp4" name="'+prefix+'[mp4]" type="text" placeholder="Enter .mp4 video URL" value="'+mp4+'" /></td>'
								+'</tr>'
								/* +'<tr valign="top" class="field-row">'
									+'<th scope="row">Webm video URL</th>'
									+'<td><input id="video-webm" name="'+prefix+'[webm]" type="text" placeholder="Enter .webm video URL" value="'+webm+'" /></td>'
								+'</tr>' */
								/***************************+'<tr valign="top" class="field-row">'
									+'<th scope="row">Show Pre Roll Video Ad?</th>'
									+'<td><input id="video-ad-show" name="'+prefix+'[prerollAD]" type="text" placeholder="yes / no" value="'+prerollAD+'" /></td>'
								+'</tr>'*********************/
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Show Pre Roll Video Ad?</th>'
									+'<td>'
									+ '<select id="preroll-show" name="'+prefix+'[prerollAD]">'
										// + '<option name="'+prefix+'[prerollAD]" value="yes">yes</option>'
										// + '<option name="'+prefix+'[prerollAD]" value="no">no</option>'
									+ '</select>'
									+'</td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pre Roll Video Ad Link URL</th>'
									+'<td><input id="video-ad-goto" name="'+prefix+'[prerollGotoLink]" type="text" placeholder="go to link when ad clicked" value="'+prerollGotoLink+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pre Roll Video Ad MP4 URL</th>'
									+'<td><input id="video-mp4-ad" name="'+prefix+'[preroll_mp4]" type="text" placeholder="Enter .mp4 pre-roll URL" value="'+preroll_mp4+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pre Roll Video Skip Timer (sec)</th>'
									+'<td><input id="video-mp4-skip-timer" name="'+prefix+'[prerollSkipTimer]" type="text" placeholder="Enter sec when to skip ad" value="'+prerollSkipTimer+'" /></td>'
								+'</tr>'
								//============= mid roll =============//
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Show Mid Roll Video Ad?</th>'
									+'<td>'
									+ '<select id="midroll-show" name="'+prefix+'[midrollAD]">'
										// + '<option name="'+prefix+'[midrollAD]" value="yes">yes</option>'
										// + '<option name="'+prefix+'[midrollAD]" value="no">no</option>'
									+ '</select>'
									+'</td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Mid Roll Video Ad Display Time</th>'
									+'<td><input id="show-midroll-time" name="'+prefix+'[midrollAD_displayTime]" type="text" placeholder="minutes : seconds" value="'+midrollAD_displayTime+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Mid Roll Video Ad Link URL</th>'
									+'<td><input id="midroll-video-ad-goto" name="'+prefix+'[midrollGotoLink]" type="text" placeholder="go to link when ad clicked" value="'+midrollGotoLink+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Mid Roll Video Ad MP4 URL</th>'
									+'<td><input id="preroll-video-mp4-ad" name="'+prefix+'[midroll_mp4]" type="text" placeholder="Enter .mp4 mid-roll URL" value="'+midroll_mp4+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Mid Roll Video Skip Timer (sec)</th>'
									+'<td><input id="midroll-video-mp4-skip-timer" name="'+prefix+'[midrollSkipTimer]" type="text" placeholder="Enter sec when to skip ad" value="'+midrollSkipTimer+'" /></td>'
								+'</tr>'
								//============= post roll =============//
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Show Post Roll Video Ad?</th>'
									+'<td>'
									+ '<select id="postroll-show" name="'+prefix+'[postrollAD]">'
										// + '<option name="'+prefix+'[postrollAD]" value="yes">yes</option>'
										// + '<option name="'+prefix+'[postrollAD]" value="no">no</option>'
									+ '</select>'
									+'</td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Post Roll Video Ad Link URL</th>'
									+'<td><input id="postroll-video-ad-goto" name="'+prefix+'[postrollGotoLink]" type="text" placeholder="go to link when ad clicked" value="'+postrollGotoLink+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Post Roll Video Ad MP4 URL</th>'
									+'<td><input id="preroll-video-mp4-ad" name="'+prefix+'[postroll_mp4]" type="text" placeholder="Enter .mp4 pre-roll URL" value="'+postroll_mp4+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Post Roll Video Skip Timer (sec)</th>'
									+'<td><input id="postroll-video-mp4-skip-timer" name="'+prefix+'[postrollSkipTimer]" type="text" placeholder="Enter sec when to skip ad" value="'+postrollSkipTimer+'" /></td>'
								+'</tr>'
								//============= pop up =============//
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Show Pop Up Ad?</th>'
									+'<td>'
									+ '<select id="popup-show-HTML5" name="'+prefix+'[popupAdShow]">'
										// + '<option name="'+prefix+'[popupAdShow]" value="yes">yes</option>'
										// + '<option name="'+prefix+'[popupAdShow]" value="no">no</option>'
									+ '</select>'
									+'</td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad image</th>'
									+'<td><input id="popup-image-path" name="'+prefix+'[popupImg]" type="text" placeholder="pop-up Ad URL" value="'+popupImg+'" /><a class="select-image-button button-secondary button80" href="#">Select image</a></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad start time</th>'
									+'<td><input id="popup-ad-start-time" name="'+prefix+'[popupAdStartTime]" type="text" placeholder="pop-up ad start time" value="'+popupAdStartTime+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad end time</th>'
									+'<td><input id="popup-ad-end-time" name="'+prefix+'[popupAdEndTime]" type="text" placeholder="pop-up ad end time" value="'+popupAdEndTime+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop Up Ad Link URL</th>'
									+'<td><input id="popup-ad-goto-link" name="'+prefix+'[popupAdGoToLink]" type="text" placeholder="Pop Up Ad Link URL" value="'+popupAdGoToLink+'" /></td>'
								+'</tr>'
								/*+'<tr valign="top" class="field-row">'
									+'<th scope="row">Show Text Ad?</th>'
									+'<td>'
									+ '<select id="popupText-show" name="'+prefix+'[textAdShow]">'
										// + '<option name="'+prefix+'[textAdShow]" value="yes">yes</option>'
										// + '<option name="'+prefix+'[textAdShow]" value="no">no</option>'
									+ '</select>'
									+'</td>'
								+'</tr>'*/
								/*+'<tr valign="top" class="field-row">'
									+'<th scope="row">Text Ad Content</th>'
									+'<td><textarea id="video-textad" name="'+prefix+'[textAd]" type="text" cols="30" rows="2" placeholder="Enter text for ad">'+textAd+'</textarea></td>'
								+'</tr>'*/
								/*+'<tr valign="top" class="field-row">'
									+'<th scope="row">Text Ad Start Time</th>'
									+'<td><input id="text-ad-start-time" name="'+prefix+'[textAdStartTime]" type="text" placeholder="Text Ad Start Time" value="'+textAdStartTime+'" /></td>'
								+'</tr>'*/
								/*+'<tr valign="top" class="field-row">'
									+'<th scope="row">Text Ad End Time</th>'
									+'<td><input id="text-ad-end-time" name="'+prefix+'[textAdEndTime]" type="text" placeholder="Text Ad End Time" value="'+textAdEndTime+'" /></td>'
								+'</tr>'*/
								/*+'<tr valign="top" class="field-row">'
									+'<th scope="row">Text Ad Link URL</th>'
									+'<td><input id="text-ad-goto-link" name="'+prefix+'[textAdGoToLink]" type="text" placeholder="Text Ad Link URL" value="'+textAdGoToLink+'" /></td>'
								+'</tr>'*/
								+ '<div class="button-secondary submitbox deletediv"><a class="submitdelete deletion">Delete</a></div>'
							+'</tbody>'
						+'</table>'
					+ '<div class="sep"></div>'
				+ '</div>'
				
				
				
				+'</div>'
			+'</div>'
		+'</div>'
			);

			var values = ["no", "yes"];
			var select = markup.find('#preroll-show');
			for ( var i = 0; i < values.length; i++ ) {
				var option = $('<option name="'+prefix+'[prerollAD]" value="'+values[i]+'">'+values[i]+'</option>').appendTo(select);
				if(typeof(options["videos"][id]) != 'undefined'){
					if(options["videos"][id]["prerollAD"] == values[i]){
						option.attr('selected','true');
					}
				}
			}
			
			var values2 = ["no", "yes"];
			var select2 = markup.find('#midroll-show');
			for ( var i = 0; i < values2.length; i++ ) {
				var option = $('<option name="'+prefix+'[midrollAD]" value="'+values2[i]+'">'+values2[i]+'</option>').appendTo(select2);
				if(typeof(options["videos"][id]) != 'undefined'){
					if(options["videos"][id]["midrollAD"] == values2[i]){
						option.attr('selected','true');
					}
				}
			}
			
			var values3 = ["no", "yes"];
			var select3 = markup.find('#postroll-show');
			for ( var i = 0; i < values3.length; i++ ) {
				var option = $('<option name="'+prefix+'[postrollAD]" value="'+values3[i]+'">'+values3[i]+'</option>').appendTo(select3);
				if(typeof(options["videos"][id]) != 'undefined'){
					if(options["videos"][id]["postrollAD"] == values3[i]){
						option.attr('selected','true');
					}
				}
			}
			
			var values4 = ["no", "yes"];
			var select4 = markup.find('#popup-show-HTML5');
			for ( var i = 0; i < values4.length; i++ ) {
				var option = $('<option name="'+prefix+'[popupAdShow]" value="'+values4[i]+'">'+values4[i]+'</option>').appendTo(select4);
				if(typeof(options["videos"][id]) != 'undefined'){
					if(options["videos"][id]["popupAdShow"] == values4[i]){
						option.attr('selected','true');
					}
				}
			}
			/*var values3 = ["yes", "no"];
			var select3 = markup.find('#popupText-show');
			for ( var i = 0; i < values3.length; i++ ) {
				var option = $('<option name="'+prefix+'[textAdShow]" value="'+values3[i]+'">'+values3[i]+'</option>').appendTo(select3);
				if(typeof(options["videos"][id]) != 'undefined'){
					if(options["videos"][id]["textAdShow"] == values3[i]){
						option.attr('selected','true');
					}
				}
			}*/
			
			return markup;
			
			
	}    
	
	function createVideoHtml_youtube(prefix,id,title,youtubeID,mp4,prerollAD,prerollGotoLink,preroll_mp4,prerollSkipTimer,midrollAD, midrollAD_displayTime, midrollGotoLink, midroll_mp4, midrollSkipTimer, postrollAD, postrollGotoLink, postroll_mp4, postrollSkipTimer,  popupAdShow, popupImg, popupAdStartTime, popupAdEndTime, popupAdGoToLink, description,thumbImg,info) 
	{
		if (typeof(prerollAD) == 'undefined' || prerollAD != "yes") 
			prerollAD = "no";
		if (typeof(midrollAD) == 'undefined' || midrollAD != "yes") 
			midrollAD = "no";
		if (typeof(postrollAD) == 'undefined' || postrollAD != "yes") 
			postrollAD = "no";
		if (typeof(popupAdShow) == 'undefined' || popupAdShow != "yes") 
			popupAdShow = "no";
		
		/*if (typeof(textAdShow) == 'undefined' || textAdShow != "yes") 
			textAdShow = "no";*/
			
		var markup = $(
		+'<div class="videos">'
			+'<div class="postbox">'
				+'<div class="handlediv videosToggle2" title="Click to toggle"></div>'
				+'<h3 class="hndle videosToggle1">'
					+'<span id="sortable-title">Video Title '+id+' : '+title+'</span>'
				+'</h3>'
				+'<div class="inside postBoxInside">'
		
		
				+'<div id="'+id+'"class="video">'
					+'<text id="video-section-count"> YouTube video '+id+'</text>'
						+ '<table class="form-table" id="player-videos-table">'
							+'<tbody>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Video title</th>'
									+'<td><input id="video-title" name="'+prefix+'[title]" type="text" placeholder="Enter video title" value="'+title+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Description</th>'
									+'<td><textarea id="video-description" name="'+prefix+'[description]" type="text" cols="30" rows="2" placeholder="Enter video description">'+description+'</textarea></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Info</th>'
									+'<td><textarea id="video-description" name="'+prefix+'[info]" type="text" cols="30" rows="2" placeholder="Enter video info">'+info+'</textarea></td>'
								+'</tr>'
//								+'<tr valign="top" class="field-row">'
//									+'<th scope="row">Info</th>'
//									+'<td><textarea id="video-info" name="'+prefix+'[info]" type="text" cols="30" rows="2" placeholder="Enter video info">'+info+'</textarea></td>'
//								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Thumbnail image</th>'
									+'<td><input id="image-path" name="'+prefix+'[thumbImg]" type="text" placeholder="Thumbnail URL" value="'+thumbImg+'" /><a class="select-image-button button-secondary button80" href="#">Select image</a></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">YouTube ID</th>'
									+'<td><input id="youtube-id" name="'+prefix+'[youtubeID]" type="text" placeholder="Enter youtube ID" value="'+youtubeID+'" /></td>'
								+'</tr>'
//								+'<tr valign="top" class="field-row">'
//									+'<th scope="row">Vimeo ID</th>'
//									+'<td><input id="youtube-id" name="'+prefix+'[vimeoID]" type="text" placeholder="Enter vimeo ID" value="'+vimeoID+'" /></td>'
//								+'</tr>'
//								+'<tr valign="top" class="field-row">'
//									+'<th scope="row">Mp4 video URL</th>'
//									+'<td><input id="video-mp4" name="'+prefix+'[mp4]" type="text" placeholder="Enter .mp4 video URL" value="'+mp4+'" /></td>'
//								+'</tr>'
//								+'<tr valign="top" class="field-row">'
//									+'<th scope="row">Webm video URL</th>'
//									+'<td><input id="video-webm" name="'+prefix+'[webm]" type="text" placeholder="Enter .webm video URL" value="'+webm+'" /></td>'
//								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Show Pre Roll Video Ad?</th>'
									+'<td>'
									+ '<select id="preroll-show" name="'+prefix+'[prerollAD]">'
										// + '<option name="'+prefix+'[prerollAD]" value="yes">yes</option>'
										// + '<option name="'+prefix+'[prerollAD]" value="no">no</option>'
									+ '</select>'
									+'</td>'
									/****+'<td><input id="video-ad-show" name="'+prefix+'[prerollAD]" type="text" placeholder="yes / no" value="'+prerollAD+'" /></td>'*****/
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pre Roll Video Ad Link URL</th>'
									+'<td><input id="video-ad-goto" name="'+prefix+'[prerollGotoLink]" type="text" placeholder="go to link when ad clicked" value="'+prerollGotoLink+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pre Roll Video Ad MP4 URL</th>'
									+'<td><input id="video-mp4-ad" name="'+prefix+'[preroll_mp4]" type="text" placeholder="Enter .mp4 pre-roll URL" value="'+preroll_mp4+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pre Roll Video Skip Timer (sec)</th>'
									+'<td><input id="video-mp4-skip-timer" name="'+prefix+'[prerollSkipTimer]" type="text" placeholder="Enter sec when to skip ad" value="'+prerollSkipTimer+'" /></td>'
								+'</tr>'
								//============= mid roll =============//
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Show Mid Roll Video Ad?</th>'
									+'<td>'
									+ '<select id="midroll-show" name="'+prefix+'[midrollAD]">'
										// + '<option name="'+prefix+'[midrollAD]" value="yes">yes</option>'
										// + '<option name="'+prefix+'[midrollAD]" value="no">no</option>'
									+ '</select>'
									+'</td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Mid Roll Video Ad Display Time</th>'
									+'<td><input id="show-midroll-time" name="'+prefix+'[midrollAD_displayTime]" type="text" placeholder="minutes : seconds" value="'+midrollAD_displayTime+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Mid Roll Video Ad Link URL</th>'
									+'<td><input id="midroll-video-ad-goto" name="'+prefix+'[midrollGotoLink]" type="text" placeholder="go to link when ad clicked" value="'+midrollGotoLink+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Mid Roll Video Ad MP4 URL</th>'
									+'<td><input id="preroll-video-mp4-ad" name="'+prefix+'[midroll_mp4]" type="text" placeholder="Enter .mp4 mid-roll URL" value="'+midroll_mp4+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Mid Roll Video Skip Timer (sec)</th>'
									+'<td><input id="midroll-video-mp4-skip-timer" name="'+prefix+'[midrollSkipTimer]" type="text" placeholder="Enter sec when to skip ad" value="'+midrollSkipTimer+'" /></td>'
								+'</tr>'
								//============= post roll =============//
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Show Post Roll Video Ad?</th>'
									+'<td>'
									+ '<select id="postroll-show" name="'+prefix+'[postrollAD]">'
										// + '<option name="'+prefix+'[postrollAD]" value="yes">yes</option>'
										// + '<option name="'+prefix+'[postrollAD]" value="no">no</option>'
									+ '</select>'
									+'</td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Post Roll Video Ad Link URL</th>'
									+'<td><input id="postroll-video-ad-goto" name="'+prefix+'[postrollGotoLink]" type="text" placeholder="go to link when ad clicked" value="'+postrollGotoLink+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Post Roll Video Ad MP4 URL</th>'
									+'<td><input id="preroll-video-mp4-ad" name="'+prefix+'[postroll_mp4]" type="text" placeholder="Enter .mp4 pre-roll URL" value="'+postroll_mp4+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Post Roll Video Skip Timer (sec)</th>'
									+'<td><input id="postroll-video-mp4-skip-timer" name="'+prefix+'[postrollSkipTimer]" type="text" placeholder="Enter sec when to skip ad" value="'+postrollSkipTimer+'" /></td>'
								+'</tr>'
								//============= pop up =============//
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Show Pop Up Ad?</th>'
									+'<td>'
									+ '<select id="popup-show-youtube" name="'+prefix+'[popupAdShow]">'
										// + '<option name="'+prefix+'[popupAdShow]" value="yes">yes</option>'
										// + '<option name="'+prefix+'[popupAdShow]" value="no">no</option>'
									+ '</select>'
									+'</td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad image</th>'
									+'<td><input id="popup-image-path" name="'+prefix+'[popupImg]" type="text" placeholder="pop-up Ad URL" value="'+popupImg+'" /><a class="select-image-button button-secondary button80" href="#">Select image</a></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad start time</th>'
									+'<td><input id="popup-ad-start-time" name="'+prefix+'[popupAdStartTime]" type="text" placeholder="pop-up ad start time" value="'+popupAdStartTime+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad end time</th>'
									+'<td><input id="popup-ad-end-time" name="'+prefix+'[popupAdEndTime]" type="text" placeholder="pop-up ad end time" value="'+popupAdEndTime+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop Up Ad Link URL</th>'
									+'<td><input id="popup-ad-goto-link" name="'+prefix+'[popupAdGoToLink]" type="text" placeholder="Pop Up Ad Link URL" value="'+popupAdGoToLink+'" /></td>'
								+'</tr>'
								/*+'<tr valign="top" class="field-row">'
									+'<th scope="row">Text ad show?</th>'
									+'<td>'
									+ '<select id="popupText-show" name="'+prefix+'[textAdShow]">'
										// + '<option name="'+prefix+'[textAdShow]" value="yes">yes</option>'
										// + '<option name="'+prefix+'[textAdShow]" value="no">no</option>'
									+ '</select>'
									+'</td>'
								+'</tr>'*/
								/*+'<tr valign="top" class="field-row">'
									+'<th scope="row">Text Ad Content</th>'
									+'<td><textarea id="video-textad" name="'+prefix+'[textAd]" type="text" cols="30" rows="2" placeholder="Enter text for ad">'+textAd+'</textarea></td>'
								+'</tr>'*/
								/*+'<tr valign="top" class="field-row">'
									+'<th scope="row">Text Ad Start Time</th>'
									+'<td><input id="text-ad-start-time" name="'+prefix+'[textAdStartTime]" type="text" placeholder="Text Ad Start Time" value="'+textAdStartTime+'" /></td>'
								+'</tr>'*/
								/*+'<tr valign="top" class="field-row">'
									+'<th scope="row">Text Ad End Time</th>'
									+'<td><input id="text-ad-end-time" name="'+prefix+'[textAdEndTime]" type="text" placeholder="Text Ad End Time" value="'+textAdEndTime+'" /></td>'
								+'</tr>'*/
								/*+'<tr valign="top" class="field-row">'
									+'<th scope="row">Text Ad Link URL</th>'
									+'<td><input id="text-ad-goto-link" name="'+prefix+'[textAdGoToLink]" type="text" placeholder="Text Ad Link URL" value="'+textAdGoToLink+'" /></td>'
								+'</tr>'*/
								+ '<div class="button-secondary submitbox deletediv"><a class="submitdelete deletion">Delete</a></div>'
							+'</tbody>'
						+'</table>'
					+ '<div class="sep"></div>'
				+ '</div>'
				
				
				+'</div>'
			+'</div>'
		+'</div>'
			);

			var values = ["no", "yes"];
			var select = markup.find('#preroll-show');
			for ( var i = 0; i < values.length; i++ ) {
				var option = $('<option name="'+prefix+'[prerollAD]" value="'+values[i]+'">'+values[i]+'</option>').appendTo(select);
				if(typeof(options["videos"][id]) != 'undefined'){
					if(options["videos"][id]["prerollAD"] == values[i]){
						option.attr('selected','true');
					}
				}
			}
			
			var values2 = ["no", "yes"];
			var select2 = markup.find('#midroll-show');
			for ( var i = 0; i < values2.length; i++ ) {
				var option = $('<option name="'+prefix+'[midrollAD]" value="'+values2[i]+'">'+values2[i]+'</option>').appendTo(select2);
				if(typeof(options["videos"][id]) != 'undefined'){
					if(options["videos"][id]["midrollAD"] == values2[i]){
						option.attr('selected','true');
					}
				}
			}
			
			var values3 = ["no", "yes"];
			var select3 = markup.find('#postroll-show');
			for ( var i = 0; i < values3.length; i++ ) {
				var option = $('<option name="'+prefix+'[postrollAD]" value="'+values3[i]+'">'+values3[i]+'</option>').appendTo(select3);
				if(typeof(options["videos"][id]) != 'undefined'){
					if(options["videos"][id]["postrollAD"] == values3[i]){
						option.attr('selected','true');
					}
				}
			}
			
			var values4 = ["no", "yes"];
			var select4 = markup.find('#popup-show-youtube');
			for ( var i = 0; i < values4.length; i++ ) {
				var option = $('<option name="'+prefix+'[popupAdShow]" value="'+values4[i]+'">'+values4[i]+'</option>').appendTo(select4);
				if(typeof(options["videos"][id]) != 'undefined'){
					if(options["videos"][id]["popupAdShow"] == values4[i]){
						option.attr('selected','true');
					}
				}
			}
			/*var values3 = ["yes", "no"];
			var select3 = markup.find('#popupText-show');
			for ( var i = 0; i < values3.length; i++ ) {
				var option = $('<option name="'+prefix+'[textAdShow]" value="'+values3[i]+'">'+values3[i]+'</option>').appendTo(select3);
				if(typeof(options["videos"][id]) != 'undefined'){
					if(options["videos"][id]["textAdShow"] == values3[i]){
						option.attr('selected','true');
					}
				}
			}*/
			
			return markup;
	}
	function createVideoHtml_youtubePlaylist(prefix,id,feedTitle,prerollAD,prerollGotoLink,preroll_mp4,prerollSkipTimer,midrollAD, midrollAD_displayTime, midrollGotoLink, midroll_mp4, midrollSkipTimer, postrollAD, postrollGotoLink, postroll_mp4, postrollSkipTimer,  popupAdShow, popupImg, popupAdStartTime, popupAdEndTime, popupAdGoToLink) 
	{
		if (typeof(prerollAD) == 'undefined' || prerollAD != "yes") 
			prerollAD = "no";
		if (typeof(midrollAD) == 'undefined' || midrollAD != "yes") 
			midrollAD = "no";
		if (typeof(postrollAD) == 'undefined' || postrollAD != "yes") 
			postrollAD = "no";
		if (typeof(popupAdShow) == 'undefined' || popupAdShow != "yes") 
			popupAdShow = "no";
		
		/*if (typeof(textAdShow) == 'undefined' || textAdShow != "yes") 
			textAdShow = "no";*/
			
		var markup = $(
		+'<div class="videos">'
			+'<div class="postbox">'
				+'<div class="handlediv videosToggle2" title="Click to toggle"></div>'
				+'<h3 class="hndle videosToggle1">'
					+'<span id="sortable-title">Video Title '+id+' : '+feedTitle+'</span>'
				+'</h3>'
				+'<div class="inside postBoxInside">'
		
		
				+'<div id="'+id+'"class="video">'
					+'<text id="video-section-count"> YouTube playlist video '+id+'</text>'
						+ '<table class="form-table" id="player-videos-table">'
							+'<tbody>'
								// +'<tr valign="top" class="field-row">'
									// +'<th scope="row">Video title</th>'
									// +'<td><input id="video-title" name="'+prefix+'[title]" type="text" placeholder="Enter video title" value="'+title+'" /></td>'
								// +'</tr>'
								// +'<tr valign="top" class="field-row">'
									// +'<th scope="row">Description</th>'
									// +'<td><textarea id="video-description" name="'+prefix+'[description]" type="text" cols="30" rows="2" placeholder="Enter video description">'+description+'</textarea></td>'
								// +'</tr>'
//								+'<tr valign="top" class="field-row">'
//									+'<th scope="row">Info</th>'
//									+'<td><textarea id="video-info" name="'+prefix+'[info]" type="text" cols="30" rows="2" placeholder="Enter video info">'+info+'</textarea></td>'
//								+'</tr>'
								// +'<tr valign="top" class="field-row">'
									// +'<th scope="row">Thumbnail image</th>'
									// +'<td><input id="image-path" name="'+prefix+'[thumbImg]" type="text" placeholder="Thumbnail URL" value="'+thumbImg+'" /><a class="select-image-button button-secondary button80" href="#">Select image</a></td>'
								// +'</tr>'
								// +'<tr valign="top" class="field-row">'
									// +'<th scope="row">YouTube ID</th>'
									// +'<td><input id="youtube-id" name="'+prefix+'[youtubeID]" type="text" placeholder="Enter youtube ID" value="'+youtubeID+'" /></td>'
								// +'</tr>'
//								+'<tr valign="top" class="field-row">'
//									+'<th scope="row">Vimeo ID</th>'
//									+'<td><input id="youtube-id" name="'+prefix+'[vimeoID]" type="text" placeholder="Enter vimeo ID" value="'+vimeoID+'" /></td>'
//								+'</tr>'
//								+'<tr valign="top" class="field-row">'
//									+'<th scope="row">Mp4 video URL</th>'
//									+'<td><input id="video-mp4" name="'+prefix+'[mp4]" type="text" placeholder="Enter .mp4 video URL" value="'+mp4+'" /></td>'
//								+'</tr>'
//								+'<tr valign="top" class="field-row">'
//									+'<th scope="row">Webm video URL</th>'
//									+'<td><input id="video-webm" name="'+prefix+'[webm]" type="text" placeholder="Enter .webm video URL" value="'+webm+'" /></td>'
//								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Show Pre Roll Video Ad?</th>'
									+'<td>'
									+ '<select id="preroll-show" name="'+prefix+'[prerollAD]">'
										// + '<option name="'+prefix+'[prerollAD]" value="yes">yes</option>'
										// + '<option name="'+prefix+'[prerollAD]" value="no">no</option>'
									+ '</select>'
									+'</td>'
									/****+'<td><input id="video-ad-show" name="'+prefix+'[prerollAD]" type="text" placeholder="yes / no" value="'+prerollAD+'" /></td>'*****/
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pre Roll Video Ad Link URL</th>'
									+'<td><input id="video-ad-goto" name="'+prefix+'[prerollGotoLink]" type="text" placeholder="go to link when ad clicked" value="'+prerollGotoLink+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pre Roll Video Ad MP4 URL</th>'
									+'<td><input id="video-mp4-ad" name="'+prefix+'[preroll_mp4]" type="text" placeholder="Enter .mp4 pre-roll URL" value="'+preroll_mp4+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pre Roll Video Skip Timer (sec)</th>'
									+'<td><input id="video-mp4-skip-timer" name="'+prefix+'[prerollSkipTimer]" type="text" placeholder="Enter sec when to skip ad" value="'+prerollSkipTimer+'" /></td>'
								+'</tr>'
								//============= mid roll =============//
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Show Mid Roll Video Ad?</th>'
									+'<td>'
									+ '<select id="midroll-show" name="'+prefix+'[midrollAD]">'
										// + '<option name="'+prefix+'[midrollAD]" value="yes">yes</option>'
										// + '<option name="'+prefix+'[midrollAD]" value="no">no</option>'
									+ '</select>'
									+'</td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Mid Roll Video Ad Display Time</th>'
									+'<td><input id="show-midroll-time" name="'+prefix+'[midrollAD_displayTime]" type="text" placeholder="minutes : seconds" value="'+midrollAD_displayTime+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Mid Roll Video Ad Link URL</th>'
									+'<td><input id="midroll-video-ad-goto" name="'+prefix+'[midrollGotoLink]" type="text" placeholder="go to link when ad clicked" value="'+midrollGotoLink+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Mid Roll Video Ad MP4 URL</th>'
									+'<td><input id="preroll-video-mp4-ad" name="'+prefix+'[midroll_mp4]" type="text" placeholder="Enter .mp4 mid-roll URL" value="'+midroll_mp4+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Mid Roll Video Skip Timer (sec)</th>'
									+'<td><input id="midroll-video-mp4-skip-timer" name="'+prefix+'[midrollSkipTimer]" type="text" placeholder="Enter sec when to skip ad" value="'+midrollSkipTimer+'" /></td>'
								+'</tr>'
								//============= post roll =============//
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Show Post Roll Video Ad?</th>'
									+'<td>'
									+ '<select id="postroll-show" name="'+prefix+'[postrollAD]">'
										// + '<option name="'+prefix+'[postrollAD]" value="yes">yes</option>'
										// + '<option name="'+prefix+'[postrollAD]" value="no">no</option>'
									+ '</select>'
									+'</td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Post Roll Video Ad Link URL</th>'
									+'<td><input id="postroll-video-ad-goto" name="'+prefix+'[postrollGotoLink]" type="text" placeholder="go to link when ad clicked" value="'+postrollGotoLink+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Post Roll Video Ad MP4 URL</th>'
									+'<td><input id="preroll-video-mp4-ad" name="'+prefix+'[postroll_mp4]" type="text" placeholder="Enter .mp4 pre-roll URL" value="'+postroll_mp4+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Post Roll Video Skip Timer (sec)</th>'
									+'<td><input id="postroll-video-mp4-skip-timer" name="'+prefix+'[postrollSkipTimer]" type="text" placeholder="Enter sec when to skip ad" value="'+postrollSkipTimer+'" /></td>'
								+'</tr>'
								//============= pop up =============//
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Show Pop Up Ad?</th>'
									+'<td>'
									+ '<select id="popup-show-youtube-playlist" name="'+prefix+'[popupAdShow]">'
										// + '<option name="'+prefix+'[popupAdShow]" value="yes">yes</option>'
										// + '<option name="'+prefix+'[popupAdShow]" value="no">no</option>'
									+ '</select>'
									+'</td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad image</th>'
									+'<td><input id="popup-image-path" name="'+prefix+'[popupImg]" type="text" placeholder="pop-up Ad URL" value="'+popupImg+'" /><a class="select-image-button button-secondary button80" href="#">Select image</a></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad start time</th>'
									+'<td><input id="popup-ad-start-time" name="'+prefix+'[popupAdStartTime]" type="text" placeholder="pop-up ad start time" value="'+popupAdStartTime+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad end time</th>'
									+'<td><input id="popup-ad-end-time" name="'+prefix+'[popupAdEndTime]" type="text" placeholder="pop-up ad end time" value="'+popupAdEndTime+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop Up Ad Link URL</th>'
									+'<td><input id="popup-ad-goto-link" name="'+prefix+'[popupAdGoToLink]" type="text" placeholder="Pop Up Ad Link URL" value="'+popupAdGoToLink+'" /></td>'
								+'</tr>'
								+ '<div class="button-secondary submitbox deletediv"><a class="submitdelete deletion">Delete</a></div>'
							+'</tbody>'
						+'</table>'
					+ '<div class="sep"></div>'
				+ '</div>'
				
				
				+'</div>'
			+'</div>'
		+'</div>'
			);

			var values = ["no", "yes"];
			var select = markup.find('#preroll-show');
			for ( var i = 0; i < values.length; i++ ) {
				var option = $('<option name="'+prefix+'[prerollAD]" value="'+values[i]+'">'+values[i]+'</option>').appendTo(select);
				if(typeof(options["videos"][id]) != 'undefined'){
					if(options["videos"][id]["prerollAD"] == values[i]){
						option.attr('selected','true');
					}
				}
			}
			
			var values2 = ["no", "yes"];
			var select2 = markup.find('#midroll-show');
			for ( var i = 0; i < values2.length; i++ ) {
				var option = $('<option name="'+prefix+'[midrollAD]" value="'+values2[i]+'">'+values2[i]+'</option>').appendTo(select2);
				if(typeof(options["videos"][id]) != 'undefined'){
					if(options["videos"][id]["midrollAD"] == values2[i]){
						option.attr('selected','true');
					}
				}
			}
			
			var values3 = ["no", "yes"];
			var select3 = markup.find('#postroll-show');
			for ( var i = 0; i < values3.length; i++ ) {
				var option = $('<option name="'+prefix+'[postrollAD]" value="'+values3[i]+'">'+values3[i]+'</option>').appendTo(select3);
				if(typeof(options["videos"][id]) != 'undefined'){
					if(options["videos"][id]["postrollAD"] == values3[i]){
						option.attr('selected','true');
					}
				}
			}
			
			var values4 = ["no", "yes"];
			var select4 = markup.find('#popup-show-youtube-playlist');
			for ( var i = 0; i < values4.length; i++ ) {
				var option = $('<option name="'+prefix+'[popupAdShow]" value="'+values4[i]+'">'+values4[i]+'</option>').appendTo(select4);
				if(typeof(options["videos"][id]) != 'undefined'){
					if(options["videos"][id]["popupAdShow"] == values4[i]){
						option.attr('selected','true');
					}
				}
			}
			/*var values3 = ["yes", "no"];
			var select3 = markup.find('#popupText-show');
			for ( var i = 0; i < values3.length; i++ ) {
				var option = $('<option name="'+prefix+'[textAdShow]" value="'+values3[i]+'">'+values3[i]+'</option>').appendTo(select3);
				if(typeof(options["videos"][id]) != 'undefined'){
					if(options["videos"][id]["textAdShow"] == values3[i]){
						option.attr('selected','true');
					}
				}
			}*/
			
			return markup;
	}
	
    function createVideoHtml_vimeo(prefix,id,title,vimeoID,mp4,prerollAD,prerollGotoLink,preroll_mp4,prerollSkipTimer,description,thumbImg,midrollAD, midrollAD_displayTime, midrollGotoLink, midroll_mp4, midrollSkipTimer, postrollAD, postrollGotoLink, postroll_mp4, postrollSkipTimer, popupAdShow, popupImg, popupAdStartTime, popupAdEndTime, popupAdGoToLink) 
	{
		if (typeof(prerollAD) == 'undefined' || prerollAD != "yes") 
			prerollAD = "no";
		if (typeof(midrollAD) == 'undefined' || midrollAD != "yes") 
			midrollAD = "no";
		if (typeof(postrollAD) == 'undefined' || postrollAD != "yes") 
			postrollAD = "no";
		if (typeof(popupAdShow) == 'undefined' || popupAdShow != "yes") 
			popupAdShow = "no";
		
		/*if (typeof(textAdShow) == 'undefined' || textAdShow != "yes") 
			textAdShow = "no";*/
			
		var markup = $(
		+'<div class="videos">'
			+'<div class="postbox">'
				+'<div class="handlediv videosToggle2" title="Click to toggle"></div>'
				+'<h3 class="hndle videosToggle1">'
					+'<span id="sortable-title">Video Title '+id+' : '+title+'</span>'
				+'</h3>'
		+'<div class="inside postBoxInside">'
		
				+'<div id="'+id+'"class="video">'
					+'<text id="video-section-count"> Vimeo video '+id+'</text>'
						+ '<table class="form-table" id="player-videos-table">'
							+'<tbody>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Video title</th>'
									+'<td><input id="video-title" name="'+prefix+'[title]" type="text" placeholder="Enter video title" value="'+title+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Description</th>'
									+'<td><textarea id="video-description" name="'+prefix+'[description]" type="text" cols="30" rows="2" placeholder="Enter video description">'+description+'</textarea></td>'
								+'</tr>'
//								+'<tr valign="top" class="field-row">'
//									+'<th scope="row">Info</th>'
//									+'<td><textarea id="video-info" name="'+prefix+'[info]" type="text" cols="30" rows="2" placeholder="Enter video info">'+info+'</textarea></td>'
//								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Thumbnail image</th>'
									+'<td><input id="image-path" name="'+prefix+'[thumbImg]" type="text" placeholder="Thumbnail URL" value="'+thumbImg+'" /><a class="select-image-button button-secondary button80" href="#">Select image</a></td>'
								+'</tr>'
//								+'<tr valign="top" class="field-row">'
//									+'<th scope="row">YouTube ID</th>'
//									+'<td><input id="youtube-id" name="'+prefix+'[youtubeID]" type="text" placeholder="Enter youtube ID" value="'+youtubeID+'" /></td>'
//								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Vimeo ID</th>'
									+'<td><input id="youtube-id" name="'+prefix+'[vimeoID]" type="text" placeholder="Enter vimeo ID" value="'+vimeoID+'" /></td>'
								+'</tr>'
//								+'<tr valign="top" class="field-row">'
//									+'<th scope="row">Mp4 video URL</th>'
//									+'<td><input id="video-mp4" name="'+prefix+'[mp4]" type="text" placeholder="Enter .mp4 video URL" value="'+mp4+'" /></td>'
//								+'</tr>'
//								+'<tr valign="top" class="field-row">'
//									+'<th scope="row">Webm video URL</th>'
//									+'<td><input id="video-webm" name="'+prefix+'[webm]" type="text" placeholder="Enter .webm video URL" value="'+webm+'" /></td>'
//								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Show Pre Roll Video Ad?</th>'
									+'<td>'
									+ '<select id="preroll-show" name="'+prefix+'[prerollAD]">'
										// + '<option name="'+prefix+'[prerollAD]" value="yes">yes</option>'
										// + '<option name="'+prefix+'[prerollAD]" value="no">no</option>'
									+ '</select>'
									+'</td>'
									/******+'<td><input id="video-ad-show" name="'+prefix+'[prerollAD]" type="text" placeholder="yes / no" value="'+prerollAD+'" /></td>'*******/
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pre Roll Video Ad Link URL</th>'
									+'<td><input id="video-ad-goto" name="'+prefix+'[prerollGotoLink]" type="text" placeholder="go to link when ad clicked" value="'+prerollGotoLink+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pre Roll Video Ad MP4 URL</th>'
									+'<td><input id="video-mp4-ad" name="'+prefix+'[preroll_mp4]" type="text" placeholder="Enter .mp4 pre-roll URL" value="'+preroll_mp4+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pre Roll Video Skip Timer (sec)</th>'
									+'<td><input id="video-mp4-skip-timer" name="'+prefix+'[prerollSkipTimer]" type="text" placeholder="Enter sec when to skip ad" value="'+prerollSkipTimer+'" /></td>'
								+'</tr>'
								//============= mid roll =============//
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Show Mid Roll Video Ad?</th>'
									+'<td>'
									+ '<select id="midroll-show" name="'+prefix+'[midrollAD]">'
										// + '<option name="'+prefix+'[midrollAD]" value="yes">yes</option>'
										// + '<option name="'+prefix+'[midrollAD]" value="no">no</option>'
									+ '</select>'
									+'</td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Mid Roll Video Ad Display Time</th>'
									+'<td><input id="show-midroll-time" name="'+prefix+'[midrollAD_displayTime]" type="text" placeholder="minutes : seconds" value="'+midrollAD_displayTime+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Mid Roll Video Ad Link URL</th>'
									+'<td><input id="midroll-video-ad-goto" name="'+prefix+'[midrollGotoLink]" type="text" placeholder="go to link when ad clicked" value="'+midrollGotoLink+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Mid Roll Video Ad MP4 URL</th>'
									+'<td><input id="preroll-video-mp4-ad" name="'+prefix+'[midroll_mp4]" type="text" placeholder="Enter .mp4 mid-roll URL" value="'+midroll_mp4+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Mid Roll Video Skip Timer (sec)</th>'
									+'<td><input id="midroll-video-mp4-skip-timer" name="'+prefix+'[midrollSkipTimer]" type="text" placeholder="Enter sec when to skip ad" value="'+midrollSkipTimer+'" /></td>'
								+'</tr>'
								//============= post roll =============//
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Show Post Roll Video Ad?</th>'
									+'<td>'
									+ '<select id="postroll-show" name="'+prefix+'[postrollAD]">'
										// + '<option name="'+prefix+'[postrollAD]" value="yes">yes</option>'
										// + '<option name="'+prefix+'[postrollAD]" value="no">no</option>'
									+ '</select>'
									+'</td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Post Roll Video Ad Link URL</th>'
									+'<td><input id="postroll-video-ad-goto" name="'+prefix+'[postrollGotoLink]" type="text" placeholder="go to link when ad clicked" value="'+postrollGotoLink+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Post Roll Video Ad MP4 URL</th>'
									+'<td><input id="preroll-video-mp4-ad" name="'+prefix+'[postroll_mp4]" type="text" placeholder="Enter .mp4 pre-roll URL" value="'+postroll_mp4+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Post Roll Video Skip Timer (sec)</th>'
									+'<td><input id="postroll-video-mp4-skip-timer" name="'+prefix+'[postrollSkipTimer]" type="text" placeholder="Enter sec when to skip ad" value="'+postrollSkipTimer+'" /></td>'
								+'</tr>'
								//============= pop up =============//
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Show Pop Up Ad?</th>'
									+'<td>'
									+ '<select id="popup-show-vimeo" name="'+prefix+'[popupAdShow]">'
										// + '<option name="'+prefix+'[popupAdShow]" value="yes">yes</option>'
										// + '<option name="'+prefix+'[popupAdShow]" value="no">no</option>'
									+ '</select>'
									+'</td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad image</th>'
									+'<td><input id="popup-image-path" name="'+prefix+'[popupImg]" type="text" placeholder="pop-up Ad URL" value="'+popupImg+'" /><a class="select-image-button button-secondary button80" href="#">Select image</a></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad start time</th>'
									+'<td><input id="popup-ad-start-time" name="'+prefix+'[popupAdStartTime]" type="text" placeholder="pop-up ad start time" value="'+popupAdStartTime+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad end time</th>'
									+'<td><input id="popup-ad-end-time" name="'+prefix+'[popupAdEndTime]" type="text" placeholder="pop-up ad end time" value="'+popupAdEndTime+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop Up Ad Link URL</th>'
									+'<td><input id="popup-ad-goto-link" name="'+prefix+'[popupAdGoToLink]" type="text" placeholder="Pop Up Ad Link URL" value="'+popupAdGoToLink+'" /></td>'
								+'</tr>'
								+ '<div class="button-secondary submitbox deletediv"><a class="submitdelete deletion">Delete</a></div>'
							+'</tbody>'
						+'</table>'
					+ '<div class="sep"></div>'
				+ '</div>'
				
				
				+'</div>'
			+'</div>'
		+'</div>'
			);
			
			var values = ["no", "yes"];
			var select = markup.find('#preroll-show');
			for ( var i = 0; i < values.length; i++ ) {
				var option = $('<option name="'+prefix+'[prerollAD]" value="'+values[i]+'">'+values[i]+'</option>').appendTo(select);
				if(typeof(options["videos"][id]) != 'undefined'){
					if(options["videos"][id]["prerollAD"] == values[i]){
						option.attr('selected','true');
					}
				}
			}
			
			var values2 = ["no", "yes"];
			var select2 = markup.find('#midroll-show');
			for ( var i = 0; i < values2.length; i++ ) {
				var option = $('<option name="'+prefix+'[midrollAD]" value="'+values2[i]+'">'+values2[i]+'</option>').appendTo(select2);
				if(typeof(options["videos"][id]) != 'undefined'){
					if(options["videos"][id]["midrollAD"] == values2[i]){
						option.attr('selected','true');
					}
				}
			}
			
			var values3 = ["no", "yes"];
			var select3 = markup.find('#postroll-show');
			for ( var i = 0; i < values3.length; i++ ) {
				var option = $('<option name="'+prefix+'[postrollAD]" value="'+values3[i]+'">'+values3[i]+'</option>').appendTo(select3);
				if(typeof(options["videos"][id]) != 'undefined'){
					if(options["videos"][id]["postrollAD"] == values3[i]){
						option.attr('selected','true');
					}
				}
			}
			
			var values4 = ["no", "yes"];
			var select4 = markup.find('#popup-show-vimeo');
			for ( var i = 0; i < values4.length; i++ ) {
				var option = $('<option name="'+prefix+'[popupAdShow]" value="'+values4[i]+'">'+values4[i]+'</option>').appendTo(select4);
				if(typeof(options["videos"][id]) != 'undefined'){
					if(options["videos"][id]["popupAdShow"] == values4[i]){
						option.attr('selected','true');
					}
				}
			}
			
			return markup;
			
	}
	
	function createVideoHtml_image(prefix,id,title,description,thumbImg,imageUrl,imageTimer) 
	{
		if (typeof(prerollAD) == 'undefined' || prerollAD != "yes") 
			prerollAD = "no";
		
		/*if (typeof(popupAdShow) == 'undefined' || popupAdShow != "yes") 
			popupAdShow = "no";*/
		
		/*if (typeof(textAdShow) == 'undefined' || textAdShow != "yes") 
			textAdShow = "no";*/
			
		var markup = $(
		+'<div class="videos">'
			+'<div class="postbox">'
				+'<div class="handlediv videosToggle2" title="Click to toggle"></div>'
				+'<h3 class="hndle videosToggle1">'
					+'<span id="sortable-title">Video Title '+id+' : '+title+'</span>'
				+'</h3>'
		+'<div class="inside postBoxInside">'
		
				+'<div id="'+id+'"class="video">'
					+'<text id="video-section-count"> Vimeo video '+id+'</text>'
						+ '<table class="form-table" id="player-videos-table">'
							+'<tbody>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Video title</th>'
									+'<td><input id="video-title" name="'+prefix+'[title]" type="text" placeholder="Enter video title" value="'+title+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Description</th>'
									+'<td><textarea id="video-description" name="'+prefix+'[description]" type="text" cols="30" rows="2" placeholder="Enter video description">'+description+'</textarea></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Thumbnail image</th>'
									+'<td><input id="image-path" name="'+prefix+'[thumbImg]" type="text" placeholder="Thumbnail URL" value="'+thumbImg+'" /><a class="select-image-button button-secondary button80" href="#">Select image</a></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Display image</th>'
									+'<td><input id="display-image-path" name="'+prefix+'[imageUrl]" type="text" placeholder="Image URL" value="'+imageUrl+'" /><a class="select-image-button button-secondary button80" href="#">Select image</a></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Display image timer (sec)</th>'
									+'<td><input id="display-image-timer" name="'+prefix+'[imageTimer]" type="text" placeholder="Seconds to skip image" value="'+imageTimer+'" /></td>'
								+'</tr>'
								+ '<div class="button-secondary submitbox deletediv"><a class="submitdelete deletion">Delete</a></div>'
							+'</tbody>'
						+'</table>'
					+ '<div class="sep"></div>'
				+ '</div>'
				
				
				+'</div>'
			+'</div>'
		+'</div>'
			);
			
			var values = ["no", "yes"];
			var select = markup.find('#preroll-show');
			for ( var i = 0; i < values.length; i++ ) {
				var option = $('<option name="'+prefix+'[prerollAD]" value="'+values[i]+'">'+values[i]+'</option>').appendTo(select);
				if(typeof(options["videos"][id]) != 'undefined'){
					if(options["videos"][id]["prerollAD"] == values[i]){
						option.attr('selected','true');
					}
				}
			}
			/*var values2 = ["yes", "no"];
			var select2 = markup.find('#popup-show');
			for ( var i = 0; i < values2.length; i++ ) {
				var option = $('<option name="'+prefix+'[popupAdShow]" value="'+values2[i]+'">'+values2[i]+'</option>').appendTo(select2);
				if(typeof(options["videos"][id]) != 'undefined'){
					if(options["videos"][id]["popupAdShow"] == values2[i]){
						option.attr('selected','true');
					}
				}
			}*/
			/*var values3 = ["yes", "no"];
			var select3 = markup.find('#popupText-show');
			for ( var i = 0; i < values3.length; i++ ) {
				var option = $('<option name="'+prefix+'[textAdShow]" value="'+values3[i]+'">'+values3[i]+'</option>').appendTo(select3);
				if(typeof(options["videos"][id]) != 'undefined'){
					if(options["videos"][id]["textAdShow"] == values3[i]){
						option.attr('selected','true');
					}
				}
			}*/
			
			return markup;
			
	}
	
	/* console.log("selected",$("#player-videos-table").find("#select-video-type").val("youtube")) */
	/* $("#player-videos-table").find("#select-video-type").val("HTML5") */
	/*$("#player-videos-table").find("#select-video-type").change(function(){ 
			console.log("changed", $("#select-video-type").val())
			if($("#select-video-type").val()=="HTML5"){
				console.log("set html5 as val")
				$("#player-videos-table").find("#select-video-type").val("HTML5")
			}
			else if($("#select-video-type").val()=="youtube"){
				console.log("set youtube as val")
				$("#player-videos-table").find("#select-video-type").val("youtube")
			}
		});*/
		

		
	});
})(jQuery);

function stripslashes (str) {
  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: Ates Goral (http://magnetiq.com)
  // +      fixed by: Mick@el
  // +   improved by: marrtins
  // +   bugfixed by: Onno Marsman
  // +   improved by: rezna
  // +   input by: Rick Waldron
  // +   reimplemented by: Brett Zamir (http://brett-zamir.me)
  // +   input by: Brant Messenger (http://www.brantmessenger.com/)
  // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
  // *     example 1: stripslashes('Kevin\'s code');
  // *     returns 1: "Kevin's code"
  // *     example 2: stripslashes('Kevin\\\'s code');
  // *     returns 2: "Kevin\'s code"
  return (str + '').replace(/\\(.?)/g, function (s, n1) {
	switch (n1) {
	case '\\':
	  return '\\';
	case '0':
	  return '\u0000';
	case '':
	  return '';
	default:
	  return n1;
	}
  });
}