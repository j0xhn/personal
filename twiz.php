<?php include 'header.html'; ?>
    <!-- masthead -->
    <div class="jumbotron">
      <h2 class="jumbotron--title">Twiz</h2>
      <p class="jumbotron--sub-title">Objective C <span class="icon-star"></span> Parse <span class="icon-star"></span> Design</p>
      <a href="https://itunes.apple.com/WebObjects/MZStore.woa/wa/viewSoftware?id=907314308&mt=8" class="btn  btn--green">View Site</a>
    </div><!-- /masthead -->

        <!-- main-content -->
        <div class="container  main-content">

          <div class="row">

            <!-- left content -->
            <div class="col-lg-8  blog">

              <!-- blog post -->
              <img src="img/TwizBig.png" class="blog-main-img  img-responsive" alt="blog main image" />

              <h4 class="blog-post-title">User Platform in Angular</h4>  
                <ul class="list-unstyled  tags  blog-tags">
                  <li><a href="https://itunes.apple.com/WebObjects/MZStore.woa/wa/viewSoftware?id=907314308&mt=8">Download</a></li>
                </ul>
              <h6 class="blog-post-sub-title">Going into this project I had little (and I'm talking LITTLE) experience with HTML and CSS.  I wanted to gain experience in Javascript and specifically Angular so I didn't fidle much with backend code, although I learned a bit of Node.js.
              <br>
              <br>
              Although I did do the design of the site, I wouldn't necessarily it was one of my "design porfolio" pieces.  I was mainly focused on functionality of adding users, tracking, and displaying their information. 
              <br>
              <br>
              </h6>

              <blockquote>
                "Shows that I can learn new technologies and skills rapidly.  Learned completely new tools and systems with a little in-class instruction, and a whole lot of outside of class experimenting. Loved it. "
                <small>John D. Storey (me)</small>
              </blockquote>

              <h6 class="blog-post-sub-title">Libraries/Technologies include Angular, Firebase, jQuery, completely custom CSS and HTML, here is a little sample:</h6>

<pre>
//////VIEW//////

&lt;!-- TAG BOX--&gt;
  &lt;div class='tagBox'&gt;
    &lt;span&gt;Other Tags&nbsp;&lt;/span&gt;&lt;i&gt;(click to vote up)&lt;/i&gt;:&nbsp; 
    &lt;p ng-repeat="tag in selectedPerson.votes | object2Array | orderBy:'value':true" ng-click='upVote(tag.tagName, selectedPerson, userID)'&gt;
    {{ tag.tagName }},&nbsp;
    &lt;/p&gt;
&lt;!-- INPUT TAG --&gt;
    &lt;form ng-submit="tagCreate(tagFromView, selectedPerson, userID)" id='tagCreateForm'&gt;
      &lt;input class='tagName' type='text' name='tagFromView' placeholder='Create new tag' ng-model="tagFromView"&gt;
      &lt;button type='submit'class='btn btn-xs tagBtns' id='submitBtnMinimal'&gt;create&lt;/button&gt;
    &lt;/form&gt;
  &lt;/div&gt;

//////CONTROLLER//////

$scope.upVote = function (tagName, selectedPerson, userID, $filter) {
    console.log('You clicked for up vote');
    if (userID){
        if(selectedPerson.votes[tagName][userID]){
            // tell them they can't vote
            if ( selectedPerson.votes[tagName][userID].type === 1){
                alert('you already voted on this');
            } else {
                selectedPerson.votes[tagName][userID].type=1;
                selectedPerson.votes[tagName].value++;
            }
        } else {
            //create the userId for this tagName
            selectedPerson.lastVote = new Date();
            selectedPerson.votes[tagName][userID] = {type:1};
            selectedPerson.votes[tagName].value++;
        }
        //rewards user for engagment 
        people[userID].overallVotes.value++;
    } else {
        peopleService.loginPrompt();
    }
}
</pre>

            <p>
              Basically I learned alot about how to pass parameters, how to interact with Firebase, and how to manipulate values inside of javascript objects.
            </p>

            </div><!-- /left content -->

              <!-- right content -->
              <div class="col-lg-4  right-hand-bar">

                <hr class="hidden-lg">

                  <h5 class='no-top-margin'>Featurettes</h5>

                  <ul class="list-unstyled  tags  category-tags">
                    <li><a>Coded while at Dev Mountain</a></li>
                    <li><a>Facebook Login</a></li>
                    <li><a>Editable settings for Bio, Website, etc..</a></li>
                    <li><a>Custom Filters for AngularJS</a></li>
                    <li><a>3 way binding with Firebase</a></li>
                    <li><a>Custom CSS animations with Key Frames</a></li>
                    <li><a>Designed Logo :)</a></li>
                  </ul>

              </div><!-- /right content -->

          </div>

        </div><!-- /main-content -->
<?php include 'footer.html'; ?>