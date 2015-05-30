<?php include 'header.html'; ?>
    <!-- masthead -->
    <div class="jumbotron">
      <h2 class="jumbotron--title">Twiz</h2>
      <p class="jumbotron--sub-title">Parse <span class="icon-star"></span> Objective C  <span class="icon-star"></span> Design</p>
      <a href="https://itunes.apple.com/WebObjects/MZStore.woa/wa/viewSoftware?id=907314308&mt=8" class="btn  btn--green">Download</a>
    </div><!-- /masthead -->

        <!-- main-content -->
        <div class="container  main-content">

          <div class="row">

            <!-- left content -->
            <div class="col-lg-8  blog">

              <!-- blog post -->
              <img src="img/TwizBig.png" class="blog-main-img  img-responsive" alt="blog main image" />

              <h4 class="blog-post-title">Twitter Quiz = Twiz</h4>  
                <ul class="list-unstyled  tags  blog-tags">
                  <li><a href="https://itunes.apple.com/WebObjects/MZStore.woa/wa/viewSoftware?id=907314308&mt=8">Download</a></li>
                </ul>
              <h6 class="blog-post-sub-title">I built this app for App Raptors (a client) while attending Dev Mountain.  It gets your twitter feed and quizes you on who said what rewarding points for correct answers.  Even though I personally believe Javascript will devour the world and the world wide web... it doesn't hurt to put a little understanding of native enviorments in your toolbelt right? 
              <br>
              <br>
              I did it all.  From design to development, this was my baby.  I didn't handle authentication of users or create my own database and api -- big shout out to Parse for that stuff -- but I did get to learn quite a bit about the exciting world of native iOS.
              <br>
              <br>
              </h6>

              <blockquote>
                "any application that can be written in JavaScript, will eventually be written in JavaScript"
                <small>Atwood's Law</small>
              </blockquote>

              <h6 class="blog-post-sub-title">Wanted to mention I haven't touched an iPhone app since graduating. Mentors were great in helping me pass roadblocks, but like the quote above, I just think the web based API and Javascript are going to be more usefull to me over time.  That being said and I worked extensively with the Parse and Twitter API while including my own game logic in a way that the user can just keep playing seemelessly without ever thinking twice.  KEEP IN MIND: This was my first iPhone app, still relatively new to development.  I don't profess this to be clean or awesome code... but it did work :)</h6>

<pre>
////// GAME LOGIC + TWITTER API//////

- (void) loadTweetBucketDictionaryWithCompletion:(void (^)(bool success))block{ //requests timeline in the background

    NSString *bodyString = @"";
    if (!self.currentUser){ // if user stops using app, then re-opens app it erases self.currentUser, this sets it.
        self.currentUser = [[NSUserDefaults standardUserDefaults] objectForKey:CURRENT_USER_KEY];
    }
    if (!self.lastTweetID) {
        bodyString = [NSString stringWithFormat:@"https://api.twitter.com/1.1/statuses/home_timeline.json?screen_name=%@&count=100", self.currentUser];
    } else {
        bodyString = [NSString stringWithFormat:@"https://api.twitter.com/1.1/statuses/home_timeline.json?screen_name=%@&count=100&since_id=%@", self.currentUser, self.lastTweetID];
    }
    
    NSURL *url = [NSURL URLWithString:bodyString];
    NSMutableURLRequest *tweetRequest = [NSMutableURLRequest requestWithURL:url];
    [[PFTwitterUtils twitter] signRequest:tweetRequest];
   
    NSOperationQueue *queue = [[NSOperationQueue alloc] init];
    [NSURLConnection sendAsynchronousRequest:tweetRequest queue:queue completionHandler:^()
     {
         if (error) { // error for when you exeed your limit
             NSLog(@"error %@", error);
             if (block) { // if passes "nil" then this ensures it doesn't throw an error
                 block(NO);
             }
         }
         else if ([data length] >1)
         {
             NSDictionary* json = [NSJSONSerialization JSONObjectWithData:data options:NSJSONWritingPrettyPrinted error:&error];
             
             for(id key in json){
                 
                 // for active tweet dictionary
                 NSNumber *singleTweetID = [key objectForKey:@"id"];
                 NSString *singleTweetText = [key objectForKey:@"text"];
                 NSString *singleTweetAuthorID = [[key objectForKey:@"user"]objectForKey:@"screen_name"];
                 NSNumber *defaultPoints = [NSNumber numberWithInteger:-1];
                 
                 NSURL *singleTweetimageURL = [NSURL URLWithString:[[key objectForKey:@"user"] objectForKey:@"profile_image_url_https"]];
                 
                 NSDictionary *singleTweet = @{tweetTextKey:singleTweetText,tweetAuthorIDKey:singleTweetAuthorID,tweetIDKey:singleTweetID,
                 tweetPhotoURLKey: 
                 singleTweetimageURL};
                
                 
                 // sets possibleAnswerBucketArray to unique answers
                 if (!self.possibleAnswerBucketArray) { // on initial load creates a new possible Answer bucket array
                     self.possibleAnswerBucketArray = [NSMutableArray new];
                 }
                 NSString *query = [NSString stringWithFormat:@"%@ = %%@", possibleAnswerAuthorKey];
                 NSPredicate *pred = [NSPredicate predicateWithFormat:query,singleTweetAuthorID];
                 NSArray *filteredArray = [self.possibleAnswerBucketArray filteredArrayUsingPredicate:pred];
                 if (filteredArray.count == 0) {
                     NSDictionary *possibleAnswer = @{possibleAnswerAuthorKey:singleTweetAuthorID,
                                                      possibleAnswerPhotoURLKey: singleTweetimageURL,
                                                      tweetPointsKey:defaultPoints};
                     [self.possibleAnswerBucketArray addObject:possibleAnswer];
                 }
                 
                 if (!self.tweetBucketDictionary) { // for the initial load, if no dictionary it creates one
                     self.tweetBucketDictionary = [NSMutableDictionary new];
                 }
                 [self.tweetBucketDictionary setValue:singleTweet forKey:[NSString stringWithFormat:@"%@",singleTweetID]];
             }
             
             if (self.InitialLoadState) {
                 self.InitialLoadState = NO; // turns off auto ask
             }

             NSLog(@"tweet bucket finished Loading");
             if (self.possibleAnswerBucketArray < 4) { // checks to see if there are atleast 4 possible answers
                 UIAlertView *infiniteLoopAlert = [[UIAlertView alloc]
                                                   initWithTitle:@"Whoops! Lets try this again"
                                                   message:@"Something went wrong under the hood.  Usually it's because you didn't have at least 4 new tweets to create your quiz with, so close the app and wait a couple of minutes then try again"
                                                   delegate:self
                                                   cancelButtonTitle:@"Thanks!"
                                                   otherButtonTitles:nil];
                 [infiniteLoopAlert show];
             }

             if (block) { // if passes "nil" then this ensures it doesn't throw an error
                 block(YES);
             }
             
         }
         else if ([data length] == 0 && error == nil)
         {
             NSLog(@"Nothing was downloaded.");
             if (block) { // if passes "nil" then this ensures it doesn't throw an error
                 block(NO);
             }
         }
         else if (error != nil){
             UIAlertView *errorAlertView = [[UIAlertView alloc] initWithTitle:@"Error"
                                                                      message:@"Something went wrong, check your internet connection then try again"
                                                                     delegate:self
                                                            cancelButtonTitle:@"OK"
                                                            otherButtonTitles:nil];
             [errorAlertView show];
             NSLog(@"Error = %@", error);
             if (block) { // if passes "nil" then this ensures it doesn't throw an error
                 block(NO);
             }
         }
     }];
}
</pre>

            </div><!-- /left content -->

              <!-- right content -->
              <div class="col-lg-4  right-hand-bar">

                <hr class="hidden-lg">

                  <h5 class='no-top-margin'>Featurettes</h5>

                  <ul class="list-unstyled  tags  category-tags">
                    <li><a>Coded while at Dev Mountain</a></li>
                    <li><a>Twitter Login</a></li>
                    <li><a>Endured Apple Certificate Headaches</a></li>
                    <li><a>Game Logic</a></li>
                    <li><a>Design</a></li>
                    <li><a>Animations</a></li>
                  </ul>

              </div><!-- /right content -->

          </div>

        </div><!-- /main-content -->
<?php include 'footer.html'; ?>