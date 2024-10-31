var photoData;

function loadPhotoData(data, obj) {
	if (!data.feed.entry) return;

	data.feed.entry.sort(sortfunction)
	
	function sortfunction(a, b){
		if (parseInt(a.exif$tags.exif$time.$t) < parseInt(b.exif$tags.exif$time.$t)) {
			return 1;
		} else if (parseInt(a.exif$tags.exif$time.$t) > parseInt(b.exif$tags.exif$time.$t)) {
			return -1;
		} else {
			return 0;
		}
	}

	photoData = data;
	displayPhotos(obj);
}

function displayPhotos(obj) {
	var output = "";
	var counter = 1;
	for (var i=0; i<photoData.feed.entry.length; i++) {
		var item = photoData.feed.entry[i];
		
		var thumburl = item.media$group.media$thumbnail[0].url;
		
		if (item.exif$tags.exif$time) {
			var ts = new Date(parseInt(item.exif$tags.exif$time.$t) - 72000000);
		} else {
			var ts = new Date(parseInt(item.gphoto$timestamp.$t) - 72000000);
		}

		
		if (item.media$group.media$content[1]) {
			var medium = item.media$group.media$content[1].medium;
			var fileurl = item.link[1].href;
			var fileurl = "http://video.google.com/googleplayer.swf?videoUrl=" + escape(item.media$group.media$content[2].url) + "&autoplay=yes&playerMode=mini";
		} else {
			var medium = item.media$group.media$content[0].medium;
			var fileurl = item.media$group.media$content[0].url;
		}
	
		if (item.media$group.media$description.$t && item.media$group.media$description.$t.length) {
			var title = item.media$group.media$description.$t;
		} else {
			var title = item.media$group.media$content[0].url.split("/");
			title = title[title.length - 1];
			title = unescape(title.split(".")[0]);
		}

		if (obj.showTitles) {
			var caption = title;
		} else {
			var caption = "";
		}
		
		if (ts <= obj.date) {
			if (medium == "image") {
				var rel = "lightbox[" + obj.id + "]";
			} else {
				var rel = "lightbox[" + obj.id + "];width=320;height=240;";
			}
			if (obj.viewAs == "thumbs") {
				output += '<li><a href="' + fileurl + '" rel="' + rel + '" title="' + caption + '"><img src="' + thumburl + '" width="' + obj.thumbsize + '" height="' + obj.thumbsize + '" alt="' + caption + '" /></a></li>';
			} else {
				output += '<li><a href="' + fileurl + '" rel="' + rel + '" title="' + caption + '">' + title + '</a></li>';
			}
			counter++;
		}
		
		if (counter>obj.maxPhotos) break;
	}
	output += '<li><a href="' + photoData.feed.link[1].href + '" class="widget_picasa_json_more">More...</a></li>';
	document.getElementById('picasa_json-' + obj.id).getElementsByTagName('ul')[0].innerHTML = output;
}