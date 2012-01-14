$(document).ready(function() {
    
    $('textarea').autogrow();

    /* Javascript Functions for operations */
    
    $('.noLeapFrogAction').live("click", function(){
        var element = $(this);
        $.post( noLeapFrogPostUrl, 
        {
            postId: $(element).parents(".post").attr('postId')
        },
        function(data){
            if($(element).hasClass('noLeapFrogged'))
            {
                $(element).removeClass('noLeapFrogged');
            }
            else 
            {
                $(element).addClass('noLeapFrogged');
            }
        });
    });

    $('.upvoteAction').live("click", function(){
        var element = $(this);
        $.post(upvotePostUrl, 
        {
            postId: $(this).parents(".post").attr('postId')
        },
        function(data){
            changeVoteValues(element, data.upvoteCount, data.downvoteCount);
        }, "json");
    });
 
    $('.downvoteAction').live("click", function(){
        var element = $(this);
        $.post(downvotePostUrl, 
        {
            postId: $(this).parents(".post").attr('postId')
        },
        function(data){
            changeVoteValues(element, data.upvoteCount, data.downvoteCount);
        }, "json");
    });

    /**
     * Update the vote values and adjust the highlight on the vote buttons.
     * Called by upvote/downvote to update values.
     */
    function changeVoteValues(element, upvoteCount, downvoteCount)
    {
        $(element).parents('.postVoting').find('.upvoteCount').html(upvoteCount);
        $(element).parents('.postVoting').find('.downvoteCount').html(downvoteCount);

        /*
         * If the element already has class 'favourite', then it has been clicked, 
         * and it should be removed. If it does not have the class, we must check 
         * if it's neighbour does and act accordingly.
         */
        if ($(element).hasClass('upvoteActive')) {
            $(element).removeClass('upvoteActive');
        } 
        else if ($(element).hasClass('downvoteActive')) {
             $(element).removeClass('downvoteActive');
        }
        else {
            var otherVote = $(element).siblings(".vote");
            if (otherVote.hasClass('downvoteActive')) {
                otherVote.removeClass('downvoteActive');
            } 
            else if (otherVote.hasClass('upvoteActive')) {
                otherVote.removeClass('upvoteActive');
            }
            else {
                if($(element).hasClass('upvoteAction'))
                { 
                    $(element).addClass('upvoteActive');
                }
                else 
                {
                    $(element).addClass('downvoteActive');
                }
            }
        }
    }

    $('.favouriteAction').live("click", function(){
        var element = $(this); 
        $.post(favouritePostUrl, 
        {
            postId: $(element).parents(".post").attr('postId')
        },
        function(data){
            //alert($(element).parents(".post").attr('id'));
            if (data.status == 'success')
                changeFavouriteValue(element, data.newValue);
        }, "json");
    });

    function changeFavouriteValue(element, newFavValue) {
        if (newFavValue)
            $(element).addClass("favourited");
        else
            $(element).removeClass("favourited");
    }

    $('.respondAction').live("click", function(){
        var element = $(this);
        var postCommentingBox = $(element).parents('.postMiddle').children('.postCommenting');
        postCommentingBox.toggle();
        if ($(postCommentingBox).is(":visible"))
            postCommentingBox.children("textarea").focus();
    });



    
    
    //Budget feedback javascript
    $('.feedbackBlock #submitFeedback').live('click', function() {
        var feedback = $(this).parents('.feedbackBlock').find('.feedback');
        $.post( submitFeedbackUrl, 
        {
            feedback: $(feedback).val()
        },
        function(data){
            $(feedback).val("");
            alert('Thanks for taking the time to give us feedback. We read \n\
                    every single comment so keep them coming!');
        });
    });


    //remove image from post once added
    $('.attachedImageRemove').live('click', function () {
        $(this).parent().remove(); 
    });


    /**
     * Checks if a post should be added, and acts accordlingly.
     * @param HTML htmlElement The element to which the post is prepended.
     * @param HTML inputBox The input box that entered the data.
     * @param JSON data The data to use to check for success, and to add to the 
     * page.
     */
    function showPost(htmlElement, inputBox, returnData) {
        var postGroupHtml = jQuery.parseJSON(returnData).postGroupHtml;
        $(postGroupHtml).prependTo(htmlElement).hide().slideDown();
        // clear textarea
        inputBox.val("");
        $(".postAttachmentBlock").hide();
    }

    //Submits Post
    $('#postSubmitButton').click( function() {
    
        var postVideoType = "";
        var submitButton = $(this);


        //TEMPORARY
        if($('#postVideoSource').val() != '')
        {
            var postVideoType = "youtube"; //for now until we parse urls
        }

        var images = $('input[name=\"imageNames\"]').serializeArray();
       
        var links = new Array();
        $('#postSubmissionPanel').find('.postLinkBlock').each( function(index) {
            var element = new Array($(this).children('#url').val(), 
                $(this).children('#title').val());
            links.push(element);
        });
        
        $.post(makePostUrl,
        {
            postBody: $('#postInputText').val(), 
            postVideoType: postVideoType,
            postVideoSource: $('#postVideoSource').val(), 
            postImages: images,
            postLinks: links
        },
        function(data){
            if (data.success) {
                var postId = data.returnData.postId;
                console.log(submitButton);
                var inputBox = submitButton.parents('#postSubmissionPanel')
                        .find("#postInputText");
                loadRelatedPosts(postId);
                $('.postAttachments').html("");
                $('#postVideoUrl').val('');
                showPost($("#mainFeed .feedWrapper"), inputBox, data.returnData);
            } else {
                alert(data.error);
            }
        }, "json");
    });
   
    //Toggles visibility of the video attach block
    $("#postAttachVideo").click( function() {
        $('#postImagesBlock').hide();
        $('#postLinkBlock').hide();
        $("#postVideoBlock").toggle();
    });

    //Toggles visibility of the images attach block
    $("#postAttachImages").click( function() {
        $('#postLinkBlock').hide();
        $('#postVideoBlock').hide();
        $("#postImagesBlock").toggle();
    });
    
    //Toggles visibility of the link attach block
    $("#postAttachLink").click( function() {
        $('#postImagesBlock').hide();
        $('#postVideoBlock').hide();
        $("#postLinkBlock").toggle();
    });
    
    //Adds link to post, returns link data as grabbed from server side process.
    $("#postLinkSubmit").click( function() {
        $.post(postLinkSubmitUrl,
        {
            linkUrl: $('#postLinkUrl').val()
        },
        function(data){
            $('#postAttachedLinks').append(data);
            $('#postLinkUrl').val("");
        });
    });
    
    $(".removeLink").live('click', function(){
        $(this).parents('.postLinkBlock').remove();
    });

    //Displays the embed video attached
    $("#postVideoUrl").change( function() {

        /*
         * Eventually wrap all this in a function called on blur to determine
         * where the video URL is from, and parse and generate embed code
         * dependant on origin. Will open source that work too. 
         */
        var postVideoUrl = $('#postVideoUrl').val(); 
        var videoType = determineVideoType(postVideoUrl);

        embedVideoHtmlAndSource(videoType, postVideoUrl);
    });
        
    //Helper for above .change call for postVideoUrl
    function embedVideoHtmlAndSource(videoType, postVideoUrl) {
            
        if(videoType == 'youtube')
        {
            var indexOfYoutubeId = postVideoUrl.indexOf("=");
            if(indexOfYoutubeId != -1)
            {
                var youtubeId = postVideoUrl.substring(indexOfYoutubeId+1);
                $("#postVideoSource").val(youtubeId);
                $("#postVideoPreview").html("<embed src='"+protocol+"www.youtube.com/v/"+youtubeId+"?fs=1&amp;hl=en_US' type='application/x-shockwave-flash' width='200' height='150'></embed>");
            }
        }
        else if(videoType == 'vimeo')
        {
            var indexOfVimeoId = postVideoUrl.indexOf("/", postVideoUrl.indexOf('vimeo'));
            if(indexOfVimeoId != -1)
            {
                var vimeoId = postVideoUrl.substring(indexOfVimeoId+1);
                $("#postVideoSource").val(vimeoId);
                $("#postVideoPreview").html("<iframe src='"+protocol+"player.vimeo.com/video/"+vimeoId+"?title=0&amp;byline=0&amp;portrait=0' width='400' height='225' frameborder='0' webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>");
            }
        }
        else
        {
            $("#postVideoSource").val("");
            $("#postVideoPreview").html("");
        }
            
    }
        
    //Helper for video function above
    function determineVideoType(url)
    {
        var youtubeIndex = url.indexOf('youtube.com', 0);
        var vimeoIndex = url.indexOf('vimeo.com', 0);

        if(youtubeIndex != -1)
        {
            return 'youtube';
        }
        else if(vimeoIndex != -1)
        {
            return 'vimeo';
        }
            
    }

    //load related posts for a given post
    function loadRelatedPosts(postId)
    {
        $.post( loadRelatedPostsUrl,
        {
            postId: postId, 
            offset: 0
        },
        function(data){
            $("#mainFeed .postGroup:first").find(".postRelatedGroup").html(data.returnData);
        }, "json");
    }


    /**
     * Check for alerts to append to a feed
     */
    function checkForAlertsAndAppend() {
        $.post("/alerts/getAlert", function(data) {
            if (data.success) {
                $(data.returnData).prependTo("#centerColumn")
                .hide().slideDown();
            } else {
                console.log(data.error);
            }
        }, "json");
    }


    
    // MORE ALERTS JAVASCRIPT ==========================================
    // append and alert if there is one
    checkForAlertsAndAppend();
    
    $(".alert").live("click", function() {
        var alertId = $(this).attr("alertId");
        $(this).slideUp();
        $.post("/alerts/ignoreAdminNotification", {
            alertId:alertId
        }, function(data) {
            
        });
    });
    
    // PROFILE JAVASCRIPT ==============================================
    $('#profileToggler').click(function(){
        $("#userProfileDropDown").slideToggle();
    });
    
    $("html").click(function() {
        $(".interestsAutocomplete").hide();
    });
    $(".addInterest").click(function(event) {
        event.stopPropagation();
    });

    $(".userInfo .tidbit").hover(function() {
        $(this).children("img.edit").show();
    },
    function() {
        $(this).children("img.edit").hide();
    });

    $(".userInfo .tidbit span, img.edit").click(function () {
        var inputBox = $(this).siblings("input, textarea");
        var parent = $(this).parent();
        parent.children().toggle();
        parent.children("input, textarea")[0].focus(":first-child");
        
    });

    $(".closeAccount").click(function() {
        var closeAccount = confirm("Really close your account?");
        if (closeAccount)
        {
            $.post('/user/ajaxhandler',
            {
                goToFunction:'setUserInactive', 
                params:{}
            },
            function(data) {
                // logout and redirect to main page
                window.location = "/site/logout";
            });
        }
    });

    $(".tidbit input[type=text], .tidbit textarea").keydown(function(e) {
        // submit when enter is pressed
        if (e.keyCode == 13) {     // enter
            // if this is an editable tidbit, submit the change
            if ($(this).parent().hasClass("editable"))
                submitUserInfo($(this));
            // else, trigger the nearby submit button
            else
                $(this).siblings("input[type=submit]").click();
        }

        // close edit bar when escape is pressed
        if (e.keyCode == 27) {     // esc
            toggleEditBar($(this));
        }
    });


    $(".userInfo .tidbit input[type=text], .userInfo .tidbit textarea").blur(function() {
        if (!$(this).siblings("input, textarea").is(":focus"))
            toggleEditBar($(this));
    });

    function toggleEditBar(element) {
        element.parent().children().toggle();
    }

    // takes in an element (as an array, for some reason!!)
    function submitUserInfo(element) {
        var htmlElement = element[0];
        var tagType = htmlElement.nodeName;
        var elements = element.parent().children(tagType);
        var attributes = {};
        for (i=0; i<elements.length; i++) {
            attributes[$(elements[i]).attr("edit")] = $(elements[i]).val();
        }
        var params = {
            attributes:attributes
        };
        $.post('/user/ajaxhandler', {
            goToFunction:'setUserAttributes', 
            params:params
        },
        function(data) {
            });
        updateField(element);
        toggleEditBar(element);
    }

    function submitPasswordChange(password1, password2)
    {
        $.post('/user/ajaxhandler', {
            goToFunction:'changePassword',
            params:{
                password:password1, 
                passwordConfirm:password2
            }
        },
        function(data) {
            if (data['status'] == 'success') {
                // clear text boxes and hide div
                $("form.changePassword").children("input[type=password]").val("");
                $("div.changePassword").hide();
                alert("Successful password change!");
            }
            else if (data['status'] == 'failure')
            {
                alert(data['error']);
            }
        }, "json");
    }

    /**
         * Takes an element and updates it's sibling
         * span with its value
         */
    function updateField(element) {
        // concatenate (space-separated) all values from inputs and textareas
        var elements = element.parent().children("input, textarea");
        var valueArray = new Array();
        for (i=0; i<elements.length; i++) {
            valueArray.push($(elements[i]).val());
        }
        var newText = valueArray.join(" ");
        var siblingSpan = element.siblings("span");
        siblingSpan.text(newText);
    }
    
    function clearEditBar(element) {
        element.val("");
    }
    
    $("span.changePassword").click(function() {
        $("div.changePassword").toggle();
        $("form.changePassword").children("input").first().focus();
    });
    
    $("form.changePassword input[type=submit]").click(function() {
        // change user's password
        submitPasswordChange(
            $("input[name=password1]").val(),
            $("input[name=password2]").val()
            );

        if (!$("div.changePassword").is(":visible"))
            $("div.changePassword").toggle();
        return false;
    });

  
    // PROFILE CARD JAVASCRIPT =========================================
    
    // load the profileCard for the relevant user
    $(".username").live("click", function() {
        $(".popoutElement").hide();
        $("#profileCard").html("");
        $("#profileCard").show().focus();
        var params = {
            username:$(this).attr("username")
        };
        $("#profileCard").load('/user/ajaxhandler', 
        {
            goToFunction:'loadUserCard', 
            params:params
        });

        return false; // prevents card from closing, or other actions
    });
    
        
    $('html').live("click", function() {
        $("#profileCard").hide();
    });
    $("#profileCard").live("click", function (event) {
        event.stopPropagation();
    });
    $(".exit").live("click", function() {
        $(this).parents(".popoutElement").hide();
    });
    
    // TOPICS JAVASCRIPT ===============================================
    /**
     * Add text in autocomplete box to input box.
     */
    $(".topicName").live("click", function() {
        var inputBox = $(this).parents(".interestsAutocomplete")
        .siblings("input[type=text]");
        inputBox.val($(this).text());
        inputBox.focus();
    });
    
    /**
     * Display topic card when hovering over a topic
     */
    $(".topicSection").hover(function() {
        $(this).children(".topicCard").addClass("visible");
    }, 
    function() {
        $(this).children(".topicCard").removeClass("visible");
    });
    
    /**
     * Deals with adding a topic when clicking inside the topic card
     */
    $(".addTopicAsInterest").live("click", function() {
        var topic = $(this).parents(".topicSection").find(".topicName").text();
        var addButton = $(this);
        var profileTopicsList = $(".userInfo .interests");
        var params = {
            inputText:topic
        };
        $.post('/user/ajaxhandler',
        {
            goToFunction: 'addTopicToUser', 
            params: params
        },
        function(data) {
            if (data.success) {
                addTopicToProfile(data.returnData);
            } else {
                alert(data.error);
            }
        }, "json");
    });
    
    $(".topicSection .removeTopic").live("click", function() {
        // check if it has a sibling with class 'topicName'
        var topicName = '';
        if ($(this).siblings(".topicName").length) {
            topicName = $(this).siblings(".topicName").text();
        } else if ($(this).parents(".topicSection").find(".topicName").length) {
            topicName = $(this).parents(".topicSection").find(".topicName").text();
        }
        var params = {
            topicName:topicName
        };
        var topicSection = $(this).parents(".topicSection");
        $.post('/user/ajaxhandler', {
            goToFunction:'removeTopicFromUser', 
            params:params
        }, function(data) {
            topicSection.slideUp();
        });
    });
    
    $("input[type=submit].addInterest").click(function() {
        var inputBox = $(this).siblings("input[type=text].addInterest");
        var params = {
            inputText:inputBox.val()
        };
        $.post('/user/ajaxhandler',
        {
            goToFunction: 'addTopicToUser', 
            params: params
        },
        function(data) {
            if (data.success) {
                addTopicToProfile(data.returnData);
            } else {
                alert(data.error);
            }
        }, "json");

        clearEditBar(inputBox);
    });

    function addTopicToProfile(htmlToAdd) {
        var profileTopicsList = $(".userInfo .interests");
        $(htmlToAdd).prependTo($(profileTopicsList))
        .hide().slideDown();
    }

    // deal with button presses like tab and the arrow keys
    $("input[type=text].addInterest").live("keydown", function(e) {

        var inputBox = this;
        var interests = $(inputBox).siblings(".interestsAutocomplete");
        // if user hits tab, select first element and enter it into the inputBox
        if (e.keyCode == 9 || e.keyCode == 39 || e.keyCode == 13) { // tab or right button or enter
            if (interests.is(":visible")) {
                autocompleteItem = interests.find(".interests .topicName")[0];
                $(autocompleteItem).click();
            }

            return false;
        }
        else if (e.keyCode == 40 || e.keyCode == 38) { // up or down buttons
            var highlightedTopic = interests.children(".interests")
            .children(".highlight");
            var currentTopic = highlightedTopic.attr("topicNum");
            if (e.keyCode == 40) { // down
                var nextTopic = currentTopic + 1;
            }
            else if (e.keyCode == 38) { // up
                var nextTopic = currentTopic - 1;
            }

            // HARD CODED CRAP
            nextTopic = Math.min(nextTopic, 5);
            nextTopic = Math.max(nextTopic, 0);
            // END OF HARDCODED CRAP
            highlightedTopic.removeClass("highlight");
        }

    });

    $("input[type=text].addInterest").keyup(function(e) {
        // display autocomplete suggestions while typing
        //    console.log(e.keyCode);
        var inputBox = $(this);
        if (inputBox.val() == "")
            $(".interestsAutocomplete").hide();
        else {
            // ensure button pressed was not arrow, enter, tab, or escape
            if ((e.keyCode <= 36 || e.keyCode >= 41) && e.keyCode != 9 
                && e.keyCode != 27 && e.keyCode != 13)
                {  
                var params = {
                    text:$(this).val()
                };
                $.post('/topics/ajaxhandler', {
                    goToFunction:'getAutocompleteSuggestion',
                    params:params
                }, function(data) {
                    $(".interestsAutocomplete").show();
                    var interestsDiv = inputBox.siblings(".interestsAutocomplete");
                    interestsDiv.html(data);
                    // highlight the first entry (ie. it is visisbly selected)
                    $(".interestsAutocomplete").children(".interests").
                    children(":first-child").addClass('highlight');

                });
            }
            else if (e.keyCode == 13) { // if user hits enter, submit!
                $("input[type=submit].addInterest").click();
            }
        }

    });
    
    
    
    // FEED JAVASCRIPT ===============================================
        
    $('.feedMore').live("click", function() {
        var offset = $('.feedMore').attr('offset');
        var feeds = $(".feed .feedWrapper");
        var feedName = feeds.filter(":first").attr("feedName");
        var userIdList = new Array();
        if (feedName == 'tracking') {
            $.each($("input[name='filterTracking[]']:checked"), function() {
                userIdList.push($(this).val());
            });
        }
        var params = {
            offset:offset, 
            feedName:feedName, 
            filters:userIdList
        };

        $.post(feedAjaxhandlerUrl,
        {
            goToFunction:'getFeedPosts', 
            params:params
        },
        function(data) {
            if (data.success) {
                var postGroupsHtml = data.returnData.postGroupsHtml;
                var numPostGroups = data.returnData.numPostGroups;
                $(postGroupsHtml).appendTo(feeds).hide().slideDown();
                $('.feedMore').attr('offset', parseInt($('.feedMore')
                    .attr('offset')) + numPostGroups);
            } else {
                alert(data.error);
            }

        }, "json");
    });
    
    
    
    
    $('.showMoreComments').live("click", function() {
        var showMoreCommentsButton = $(this);
        var postId = $(this).parent().parent().attr('postId');
        var offset = $(this).parent().children('.postComments').children('#commentOffset').val();
        //var commentTotal = $(this).parent().children('.postComments').children('#commentTotal').val();
        var commentBlock = $(this).parent().children('.postComments');
        //var offsetElement = $(this).parent().children('.postComments').children('#commentOffset');
        $.post(loadAdditionalCommentsUrl,
        {
            postId: postId,
            offset: offset
        },
        function(data) {
            //Commented out, for staggered display (i.e. show 2 at a time)
            //var newOffset = parseInt(offset)+parseInt(data.numComments);
            //offsetElement.val(newOffset);
            //if(commentTotal <= newOffset)
            //{
            $(showMoreCommentsButton).hide();
            //}
            $(data.commentsHtml).prependTo(commentBlock);
        }, 'json');

    });
        
    $('html').live("click", function() {
        $(".postCard").hide();
    });
    $(".postCard").live("click", function (event) {
        event.stopPropagation();
    });
                

    $(".postBody.popout").live("click", function() {
        $(".popoutElement").hide();
        $(".postCard").html("");
        $(".postCard").show().focus();
        var postId = $(this).parents(".post").attr("postId");
        var params = {
            postId:postId
        };

        $.post(postCardAjaxUrl, 
        {
            goToFunction:'getSinglePostCard', 
            params:params
        }, 
        function(data) {
            if (data.success) {
                $(".postCard").html(data.returnData);
            } else {
                alert(data.error);
                $(".postCard").hide();
            }
        }, "json");
                
        return false; // prevents card from closing, or other actions
    });
    
    
    $('.track').live('click',function(){
        alert('Tracking!');
    });
   
});


function submitComment(element, parentPostId) {
    var newPostCommentText = $(element).parents(".postCommenting").children('.newPostCommentText');
    var comment = newPostCommentText.val();
    var inputBox = $(element).siblings(".newPostCommentText");
    $.post(makePostUrl,
    {
        postBody: comment, 
        parentPostId: parentPostId
    },
    function(data){
        var commentElement = $(element).parents(".postCommenting").siblings('.postComments');
        console.log(commentElement); 
        showComment(commentElement, inputBox, data);
    }, "json");
}

function showComment(htmlElement, inputBox, data) {
    if (data.success) {
        $(data.returnData).appendTo(htmlElement).show();
        $(htmlElement).siblings('.postCommenting').hide();
        // clear textarea
        inputBox.val("");
    } else {
        alert(data.error);
    }
}