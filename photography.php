<?php include 'header.php'; ?>

<div id='pic-container'></div>

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.2.21/css/lightgallery.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.2.21/js/lightgallery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.2.21/js/lg-hash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.2.21/js/lg-zoom.min.js"></script>
<script src="https://npmcdn.com/masonry-layout@4.0/dist/masonry.pkgd.min.js"></script>
<script src="https://npmcdn.com/imagesloaded@4.1/imagesloaded.pkgd.min.js"></script>
<script src=""></script>

<style type="text/css">
	body {
  padding: 5px;
}
    
.grid-item {
  width: calc(33.33% - 10px);
  margin: 5px;
  -webkit-transition: -webkit-box-shadow .25s;
  transition: box-shadow .25s;
}
.grid-item:hover {
  cursor: pointer;
  -webkit-box-shadow: 0 5px 10px 0 rgba(0, 0, 0, 0.5), 0 6px 20px 0 rgba(0, 0, 0, 0.6);
  box-shadow: 0 5px 10px 0 rgba(0, 0, 0, 0.5), 0 6px 20px 0 rgba(0, 0, 0, 0.6);
}
</style>

<script type="text/javascript">
	var apiKey = '7e8cb277db159e4701934bd8dc1cec7e',
    authorId = '44124296946@N01',
    perPage = 10,
    startPage = 0;

// Main content container
var $container = $('#pic-container');

// Masonry 
$container.masonry({
  itemSelector: '.grid-item',
  columnWidth: '.grid-item',
  percentPosition: true
});

var gallery; 

function loadImages(page, callback) {
  console.log('loadImages page: '+page);
  var url = 'https://api.flickr.com/services/rest/?method=flickr.photos.getPopular&api_key=' + apiKey + '&user_id=' + authorId + '&per_page=' + perPage + '&page=' + page + '&format=json&nojsoncallback=1';
  $.getJSON(url, function(response) {
    if (response.stat === 'ok') {
      (function loadEachImg(arrPhotos, index) {
        if (index < arrPhotos.length) {
          var photo = arrPhotos[index];
          
          var link = 'https://farm' + photo.farm + '.staticflickr.com/' + photo.server + '/' + photo.id + '_' + photo.secret + '.jpg';
          var $newElem = $('<a href="https://www.flickr.com/photos/farnar/'+photo.id+'" target="_blank"><img class="grid-item " src=' + link + ' style="display: none"></a>');
          $container.append($newElem);
          // ensure that new image loaded before adding to masonry layout
          $newElem.imagesLoaded(function(){
            $newElem.show();
            $container.masonry( 'appended', $newElem, true );
            $container.masonry('layout');
            // Init lightGallery
            if (gallery) {
              gallery.destroy(false);
            }
            gallery = $container.lightGallery({
              thumbnail: true,
              animateThumb: true,
              showThumbByDefault: false,
            }).data('lightGallery'); 
            loadEachImg(arrPhotos, ++index);
          });
        } else {	// done looping
          if (callback) {
            callback();
          }
        }
      })(response.photos.photo, 0);
    }
  });
}
console.log('-----------loadImages');
loadImages(++startPage, function() {
  // make sure body has scroll therefore be able to do infinitescroll
  if (document.body.scrollHeight <= window.innerHeight) {
    loadImages(++startPage);
  }
});

// infinite scroll
var loadingImages = false;
$(document).scroll(function() {
  var docScrollTop = $(document).scrollTop();
  var endScroll = $(document).height() - $(window).height() - 100;
  if (!loadingImages && (docScrollTop > endScroll)) {
    loadingImages = true;
    loadImages(++startPage, function(){
      loadingImages = false;
    });
  }
});


</script>

	<div class="container">
  <div class="pt-4 pb-4"> <hr>
Find me on <a href="https://twitter.com/nurulamin">Twitter</a>,  <a href="https://www.linkedin.com/in/nurulamin/">Linkedin</a>. <a href="photography.php">Check out my photography</a>
</div>
</div>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-155013425-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-155013425-1');
</script>


</body>
</html>