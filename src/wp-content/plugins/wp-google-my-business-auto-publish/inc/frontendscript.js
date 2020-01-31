jQuery(document).ready(function ($) {
    $('.make-me-slick').slick();


    function readmoreActivationSlick(){
        $('.review-comment-readmore').readmore({
                moreLink: '<a class="read-more-link" href="#">Read more...</a>', // (raw HTML)
                lessLink: '<a class="read-less-link" href="#">Read less</a>', // (raw HTML)
//                sectionCSS: 'display: block; width: 100%;', // (sets the styling of the blocks)
                heightMargin: 16, // (in pixels, avoids collapsing blocks that are only slightly larger than maxHeight)
                collapsedHeight: 40

        }); 
    }

    readmoreActivationSlick();
});