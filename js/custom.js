$(function () {
    // use setTimeout for domready handler, because one theme (https://wordpress.org/themes/exposition-lite/)
    // was modifying whole page content in its domready, and misplacing our #bt-overlay-inabox.
    setTimeout(function () {
        //create a category array with default category All.
        var catArr = ['All'];
        // default selected portfolio index for overlay is null.
        var selectedPortfolioIndex = null;
        //Default selected category is All.
        var selectedCategory = 'All';
        //create portfolio html in list view ..
        var portfolio_list = '';

        // move to bottom of body.....to properly display overlay
        $("#bt-overlay-inabox").appendTo("body");

        for (var index = 0; index < portfolioJson.length; index++) {
            var portfolioArr = portfolioJson[index];
            var tagArr = portfolioArr.tags;
            //loop for push available category in catArr array ..
            for (var index2 = 0; index2 < tagArr.length; index2++) {
                if (jQuery.inArray(tagArr[index2], catArr) < 0) {
                    catArr.push(tagArr[index2]);
                }
            }

            //Create html for portfolio item
            portfolio_list += bt_portfolio_list(portfolioArr, tagArr, index);
        }
        //Add portfolio item in list view 
        jQuery("#bt-portfolio-list").html(portfolio_list);

        //Add category navigation html ..
        var nav_html = "";
        if(!hideProductFilter){
        for (var index = 0; index < catArr.length; index++) {
            if (index == 0)
            {
                nav_html += "<li><a href='javascript:void(0)' class='bt_nav_item active' data-tag='" + catArr[index] + "'>" + catArr[index] + "</a></li>";
            }
            else
            {
                nav_html += "<li><a href='javascript:void(0)' class='bt_nav_item' data-tag='" + catArr[index] + "'>" + catArr[index] + "</a></li>";
            }

        }
    }
        jQuery("#bt-portfolio-nav").html(nav_html);


        /*
         * Display portfolio items based on selected category
         */
        jQuery("#bt-portfolio-nav li a").click(function () {

            var selectedCat = $(this).data('tag');
            var selectedCat = $(this).data('tag');
            selectedCategory = selectedCat;
            //Get the reference of all portfolio
            var listItems = $("#bt-portfolio-list li");
            listItems.each(function (index, elm) {
                //If portfolio does not belongs to selected category then hide the portfolio item.
                var portfolio = $(elm);
                var tags = portfolio.data('tag');
                var tagArr = tags.split(",");
                //check whether selected category match with portfolio item's tags(categories)
                var isExist = tagArr.indexOf(selectedCat);
                //Hide the portfolio items if selected category does not match with item's tags.
                if (isExist < 0) {
                    portfolio.hide(1000);

                } else {
                    portfolio.show(1000);

                }
            });
            $('.bt_nav_item').removeClass('active');
            $(this).addClass('active');

        });
        /*
         * Open overlay onclick of portfolio item.
         */
        jQuery('.btOverlay').click(function (e) {
            e.preventDefault();
            jQuery('.bt-overlay').show();
            //get the portfolio index ...
            var index = $(this).data('index');

            openOverlay('#bt-overlay-inabox');

            var portfolioInfo = portfolioJson[index];

            btOverlayHtml(portfolioInfo);
            selectedPortfolioIndex = index;
        });

        /*
         * On click event to close overlay
         */
        jQuery('#bt-overlay-shade, .bt-overlay a.close').on('click', function (e) {
            selectedPortfolioIndex = null;
            closeOverlay();
        });

        /*
         * On escape press close overlay
         */
        jQuery(document).keyup(function (e) {
            if (e.keyCode == 27) { // escape key maps to keycode `27`
                selectedPortfolioIndex = null;
                closeOverlay();
            }
        });

        /*
         * On Click event to switch previous portfolio items
         */
        jQuery('#bt-overlay-shade, .bt-overlay a.bt_previous').on('click', function (e) {
            getPreviousPortfolio();

        });
        /*
         * On Click event to switch next portfolio items
         */
        jQuery('#bt-overlay-shade, .bt-overlay a.bt_next').on('click', function (e) {
            getNextPortfolio();
        });

        /*
         * Function used to display previous portfolio item
         */
        function getPreviousPortfolio() {
            var isLastportfolio = true;
            //Loop for display previous portfolio
            loop1:
                    for (var index = selectedPortfolioIndex - 1; index >= 0; index--) {
                var catArr = portfolioJson[index].tags;
                catArr.push('All');
                loop2:
                        for (var index2 = 0; index2 < catArr.length; index2++) {
                    if (catArr[index2] == selectedCategory) {
                        selectedPortfolioIndex = index;
                        isLastportfolio = false;
                        break loop1;
                    }
                }
            }
            //If selected portfolio is first then display last portfolio
            if (isLastportfolio) {
                loop1:
                        for (var index = portfolioJson.length - 1; index >= 0; index--) {
                    var catArr = portfolioJson[index].tags;
                    catArr.push('All');
                    loop2:
                            for (var index2 = 0; index2 < catArr.length; index2++) {
                        if (catArr[index2] == selectedCategory) {
                            selectedPortfolioIndex = index;
                            break loop1;
                        }
                    }
                }
            }
            var portfolioInfo = portfolioJson[selectedPortfolioIndex];
            btOverlayHtml(portfolioInfo);
        }
        /*
         * Function used to display next portfolio item
         */
        function getNextPortfolio() {
            var isFirstportfolio = true;

            //Loop for display next portfolio
            loop1:
                    for (var index = selectedPortfolioIndex + 1; index <= portfolioJson.length - 1; index++) {
                var catArr = portfolioJson[index].tags;
                //push All category to all portfolio items
                catArr.push('All');
                loop2:
                        for (var index2 = 0; index2 < catArr.length; index2++) {
                    if (catArr[index2] == selectedCategory) {
                        selectedPortfolioIndex = index;
                        isFirstportfolio = false;
                        break loop1;
                    }
                }
            }
            //If selected portfolio is last then display first portfolio
            if (isFirstportfolio) {
                loop1:
                        for (var index = 0; index <= portfolioJson.length - 1; index++) {
                    var catArr = portfolioJson[index].tags;
                    //push All category to all portfolio items
                    catArr.push('All');
                    loop2:
                            for (var index2 = 0; index2 < catArr.length; index2++) {
                        if (catArr[index2] == selectedCategory) {
                            selectedPortfolioIndex = index;
                            break loop1;
                        }
                    }
                }
            }
            var portfolioInfo = portfolioJson[selectedPortfolioIndex];
            btOverlayHtml(portfolioInfo);
        }
        /*
         * Function used to add overlay html e.g. images,description..
         */
        function btOverlayHtml(portfolioInfo) {
            //display portfolio title on overlay
            jQuery('.bt_portfolio_title').text('');
            if (portfolioInfo['title']) {
                jQuery(".bt_portfolio_title").html(portfolioInfo['title']);
            }

            // clear existing items.....
            jQuery(".bt_description_text").html('');
            if (portfolioInfo['description']) {
                jQuery(".bt_description_text").html(portfolioInfo['description']);
            }
            var largeImgs = portfolioInfo['large'];
            var thumnailImgs = '';
            var thumbnailArr = portfolioInfo['thumbnail'];
            var thumnailImgs = '<ul id="image-gallery" class="list-unstyled cS-hidden">';
            for (var index = 0; index < thumbnailArr.length; index++) {
                var img = '<li data-thumb="' + thumbnailArr[index] + '">';
                img += '<img src="' + largeImgs[index] + '" />';
                img += '</li>';
                thumnailImgs += img;
            }
            thumnailImgs += '</ul>';
            jQuery('#bt_overlay_container').html(thumnailImgs);

            //project links
            var linksArr = portfolioInfo['project'];
            var linksStr = '';
            for (var index = 0; index < linksArr.length; index++) {
                var links = linksArr[index];
                linksStr += '<a href="' + links['project_url'] + '" class="button-fixed"  target="_blank">';
                linksStr += '<img src="' + (links['image_url'] ? links['image_url'] : BT_PORTFOLIO_DIR + "img/link.png") + '">';
                linksStr += '</a>';
            }
            jQuery('.bt_overlay_projects').html(linksStr);

            jQuery('#image-gallery').lightSlider({
                gallery: true,
                item: 1,
                thumbItem: 9,
                slideMargin: 0,
                speed: autoSliderSpeed,
                auto: hideAutoSlider,
                loop: true,
                pause:pauseTimingSlider,
                pauseOnHover: true,
                adaptiveHeight: true,
                onSliderLoad: function () {
                    $('#image-gallery').removeClass('cS-hidden');
                }
            });
        }

        /*
         * function used to display selected item in overlay
         */
        function openOverlay(olEl) {
            $oLay = $(olEl);
            if (jQuery('#bt-overlay-shade').length == 0)
                jQuery('body').prepend('<div id="bt-overlay-shade"></div>');
            jQuery('#bt-overlay-shade').fadeTo(300, 0.7, function () {
                $oLay
                        .css({
                            display: 'block',
                        })
                        .animate({
                            opacity: 1
                        }, 400);
            });

            window.scrollTo(0, 0);
        }


        /*
         * Function used to create portfolio list html 
         */
        function bt_portfolio_list(portfolioArr, tagArr, index) {
            //use first image as portfolio thumbnail image.
            var thumbnail = null;
            if (portfolioArr['thumbnail'].length) {
                thumbnail = portfolioArr['thumbnail'][0];
            }
            var tagStr = tagArr.toString();
            var strVar = "";
            strVar += "    <li class='li-portfolio-left btOverlay' data-tag='All," + tagStr + "' data-index='" + index + "'> ";
            strVar += "        <a href=\"javascript:void(0)\"><div class=\"portfolio-thumb\"><img src='" + thumbnail + "'> <div class='project-title'><div class='project-title-text'>" + portfolioArr['title'] + "<\/div></div></div>";
            strVar += "        <\/a>";
            strVar += "    <\/li>";
            return strVar;
        }


        /*
         * Function used to close overlay.
         */
        function closeOverlay() {
            jQuery('.bt-overlay').animate({
                //top: '-=300',
                opacity: 0
            }, 400, function () {
                jQuery('#bt-overlay-shade').fadeOut(300);
                jQuery(this).hide();
            });
        }

    }, 0);
});