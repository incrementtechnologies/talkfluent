<template>
  <div class="row">
      <div class="col-lg-10 mx-auto">
        <ul class="breadcrumb">
          <li class="breadcrumb-item active" v-on:click="redirect('/dashboard')"><a :href="config.WEB_APP + 'dashboard'" onclick="return false;">Dashboard</a></li>
        </ul>
      </div>
      <span class="col-lg-10 mx-auto text-center welcome-title"><h3>WELCOME TO {{config.WEBSITE_TITLE}}</h3></span>
      <div class="col-lg-10 mx-auto row center-small">
        <span class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
          <span class="header-dashboard" >Start Here</span>
          <div style="margin-top: 80px; margin-bottom: 20px">
            <div class="wistia_responsive_padding" style="padding:49.06% 0 0 0;position:relative;"><div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;"><span class="wistia_embed wistia_async_4fqxpgrjw1 popover=true popoverAnimateThumbnail=true videoFoam=true" style="display:inline-block;height:100%;position:relative;width:100%">&nbsp;</span></div></div>
          </div>
        </span>
        <span class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
          <span class="header-dashboard">How to learn?</span>
          <div style="margin-top: 80px; margin-bottom: 20px ">

           <div class="wistia_responsive_padding" style="padding:49.06% 0 0 0;position:relative;"><div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;"><span class="wistia_embed wistia_async_36v1optnqo popover=true popoverAnimateThumbnail=true videoFoam=true" style="display:inline-block;height:100%;position:relative;width:100%">&nbsp;</span></div></div>
            </div>
          </span>
        <span class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
          <span class="header-dashboard">3000 words video clips</span>
          <div style="margin-top: 80px; margin-bottom: 20px;">
            <div class="wistia_responsive_padding" style="padding:56.25% 0 0 0;position:relative;"><div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;"><span class="wistia_embed wistia_async_ud7le43chh popover=true popoverAnimateThumbnail=true videoFoam=true" style="display:inline-block;height:87%;position:relative;width:100%">&nbsp;</span></div></div>
          </div>
        </span>
<!--         <span class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mx-auto account-error" v-if="user.status === 'NOT_VERIFIED'">
          <span class="header-dashboard available-lesson bg-danger text-white" style="margin-top:10px;">
           Account Verification Needed!
          </span>
          <span  v-bind:class="{'text-danger': verificationFlag === false, 'text-success': verificationFlag === true}" class="error-message">{{verificationMessage}}</span>
            <span class="error-message">
              Your account needs an email verification first before you can use it. Kindly Verify by clicking the button below.
            </span>
            <span class="error-action" style="margin-bottom: 20px;">
              <button class="btn btn-warning" data-toggle="modal" data-target="#emailAddressModal">Change Email Address</button>
              <button class="btn btn-primary" v-on:click="resendVerification()">Resend Email Verification</button>
          </span>
        </span> -->
<!--         <span  v-if="user.status === 'FREE_TRIAL'" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mx-auto account-error">
          <span class="header-dashboard available-lesson bg-danger text-white" style="margin-top:10px;">
           Free Trial Version
          </span>
          <span v-bind:class="{'text-danger': upgradeStatusFlag === false, 'text-success': upgradeStatusFlag === true}" class="error-message">{{upgradeStatusMessage}}</span>
            <span class="error-message">
              Your Free Trial Account will end after 7 Days.
            </span>
            <span class="error-action">
              <button class="btn btn-primary" v-on:click="upgradeAccount()">Upgrade</button>
          </span>
        </span> -->
        <span class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mx-auto account-error" v-if="user.paymentStatus === 'failed'">
          <span class="header-dashboard available-lesson bg-danger text-white" style="margin-top:10px;">
           Billing Status
          </span>
          <span class="error-message">
            You may have pending payment or you have failed payment.
          </span>
          <span class="error-action" style="margin-bottom: 20px;">
            <button class="btn btn-primary" v-on:click="redirect('/my_plan')">Go to Billing</button>
          </span>
        </span>
        <span class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mx-auto" style="margin-bottom: 20px;">
          <span class="header-dashboard available-lesson" style="margin-top:10px;">
            Words, Sentences and Lessons Known
          </span>
          <span class="bg-primary redirect-buttons" v-on:click="redirect('/already_known/word')"><label>Words</label></span>
          <span class="bg-primary redirect-buttons" v-on:click="redirect('/already_known/sentence')"><label>Sentences</label></span>
          <span class="bg-primary redirect-buttons" v-on:click="redirect('/already_known/lesson')"><label>Lessons</label></span>
        </span>
        
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mx-auto" style="margin-bottom: 50px;" v-if="dashboardLessons !== null">
          <span class="header-dashboard available-lesson">
            Available Lesson
          </span>
          <span class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mx-auto">
            <span class="top-lesson-holder" v-for="item, index in dashboardLessons">
              <span class="top-lesson-content"><h5>{{item.title}}</h5></span>
              <span class="category-lesson-holder" v-for="itemCat, indexCat in dashboardLessons[index].category" v-if="dashboardLessons[index].category !== null">
                <span class="category-lesson-content"><h6> &#9679; {{itemCat.title}}</h6></span>
                <span v-if="itemCat.lesson !== null">
                  <span class="lesson-holder" v-for="itemLesson, indexLesson in itemCat.lesson"  v-on:click="retrieveContent(itemLesson.id, itemLesson)" >
                    <span class="lesson-holder-text" v-if="itemLesson.display_status === 'success'">
                      <label class="lesson-holder-index" style="padding-top:7px;">{{indexLesson + 1}}.</label> {{itemLesson.title}}
                    </span>
                    <span class="lesson-holder-text text-danger" v-if="itemLesson.display_status === 'danger'">
                      <label class="lesson-holder-index" style="padding-top:7px;">{{indexLesson + 1}}.</label> {{itemLesson.title}} - COMING SOON!
                    </span>
                    <span class="lesson-holder-test-checker" v-if="itemLesson.display_status === 'success'">
                      <label class="test-check" v-for="i in parseInt(itemLesson.test_days)"><i class="fa fa-check"></i></label>
                      <label  class="test-check-number" v-for="i, index in (5 - parseInt(itemLesson.test_days))">{{index + parseInt(itemLesson.test_days) + 1}}</label>
                    </span>
                    
                  </span>
<!--                   <span v-if="itemLesson.display_status === 'danger'" class="lesson-holder text-danger"><label class="lesson-holder-index text-danger">{{indexLesson + 1}}.</label> {{itemLesson.title}} - COMING SOON!</span> -->
                </span>
                <span v-else class="lesson-holder text-danger">No Lessons</span>
              </span>
            </span>
          </span>
        </div>
        <span v-if="dashboardLessons === null && user.status !== 'NOT_VERIFIED'" class="empty-lesson">No Lesssons Available</span>
      </div>


      <!---Change Email Address -->
    
    <div class="modal fade" id="emailAddressModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">
              Change Email Address
            </h5>
<!--             <div class="guide-holder pull-right">
              <label>How to Use</label>
              <div class="wistia_embed guide-video popover=true wistia_async_u8p9wq6mq8" onclick="pause()"></div>
            </div> -->
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-white">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <label v-bind:class="{'text-danger': updateEmailFlag === false, 'text-success': updateEmailFlag === true}" class="col-lg-12" style="font-size: 12px;" >
              {{errorMessage}}
            </label>
            <br>
            <input type="text" class="form-control" v-model="emailAddress" placeholder="New Email Address">
            <span style="font-size: 12px;">
              <i class="fa fa-info-circle"></i> By Clicking Update, It will send an email verification to your email address inputted above.
            </span>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Cancel</button>
              <button type="button" class="btn btn-primary" v-on:click="updateEmailAddress()">Update</button>
          </div>
        </div>
      </div>
    </div>

      <!--- test modal -->
    
    <div class="modal fade" id="testModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="selectedLesson !== null">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">
              <button class="btn btn-warning dis-button">TEST</button>
              Lesson # {{selectedLesson.id}}. {{selectedLesson.title}}
              <button class="btn btn-warning" @click="audioHandler.hideModal('#testModal'), audioHandler.showModal('#dashboardModal'), audioHandler.pause()">Lesson</button>
            </h5>
<!--             <div class="guide-holder pull-right">
              <label>How to Use</label>
              <div class="wistia_embed guide-video popover=true wistia_async_u8p9wq6mq8" onclick="pause()"></div>
            </div> -->
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="audioHandler.pause()">
              <span aria-hidden="true" class="text-white">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <test-checker :selectedLesson="selectedLessonId" v-on:change="selectedLessonId"></test-checker>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close" @click="audioHandler.pause()">Close</button>
          </div>
        </div>
      </div>
    </div>


    



    <!-- Modal -->
    <div class="modal fade" id="dashboardModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="selectedLesson !== null">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <span class="modal-title" id="exampleModalLabel">
              <label>
                Lesson Script:
                <i class="fa fa-volume-up header-fa-volume-up" @click="audioHandler.playerHowler(config.BACKEND_URL + selectedLesson.lesson_audio, 'lessonAudioPlayButton')" v-bind:class="{'lesson-button-active': isLBActive === true, 'text-danger': selectedLesson.lesson_audio === null}" id="lessonAudioPlayButton">
                </i>
                <i class="fa fas fa-sync header-fa-volume-up repeat-audio-icon-ios" @click="audioHandler.playerHowler(config.BACKEND_URL + selectedLesson.lesson_audio ,'lessonAudioPlayButtonRepeat')" v-bind:class="{'lesson-button-active': isLBActive === true, 'text-danger': selectedLesson.lesson_audio === null}" id="lessonAudioPlayButtonRepeat">
                </i>
              </label>
<!--               <label v-if="selectedLesson.english_audio !== null && selectedLesson.english_audio !== 'NONE'">
                Complete Lesson:
                <i class="fa fa-volume-up header-fa-volume-up"  @click="audioHandler.playerHowler(config.BACKEND_URL + selectedLesson.english_audio,'grammarAudioPlayButton')" v-bind:class="{'lesson-button-active': isLBActive === true, 'text-danger': selectedLesson.english_audio === null}" id="grammarAudioPlayButton">
                </i>
                <i class="fa fas fa-sync header-fa-volume-up repeat-audio-icon-ios" @click="audioHandler.playerHowler(config.BACKEND_URL + selectedLesson.english_audio,'grammarAudioPlayButtonRepeat')" v-bind:class="{'lesson-button-active': isLBActive === true, 'text-danger': selectedLesson.english_audio === null}" id="grammarAudioPlayButtonRepeat">
                </i>
              </label> -->
              <label style="font-size: 24px;">Lesson # {{selectedLesson.id}}. {{selectedLesson.title}} 
                <button class="btn btn-warning" @click="audioHandler.hideModal('#dashboardModal'), audioHandler.showModal('#testModal'), audioHandler.pause()" v-if="content !== null">Test</button>
              </label>
              
            </span>
            <button type="button" class="close" aria-label="Close"  @click="audioHandler.pause(), audioHandler.hideDashboardModal()">
              <span aria-hidden="true" class="text-white">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div v-if="content !== null && selectedContent !== null">
              <div class="mobile-viewer">
                <span v-on:click="setActive('content')" v-bind:class="{'page-active-content': pageActiveContent}" class="mobile-viewer-button1"><h5>Content</h5></span>
                <span v-on:click="setActive('audio')" v-bind:class="{'page-active-audio': pageActiveAudio}" class="mobile-viewer-button2"><h5>Audio</h5></span>
              </div>
              <div v-bind:class="{'is-active-cm': responsiveView.content}" class="content-manager">
                <div class="lesson-content-holder">
                  <span style="margin-top:50px;width:100%;float:left;"></span>
                  <span v-for="itemOuter, indexOuter in contentArray" class="content-items" on>
                    <span class="row">
                      <span class="col-lg-12 col-md-12 col-sm-12 col-xs-12 sentence-mobile">
                        <label  v-on:click="retrieveSentence(indexOuter), audioHandler.playerHowler(config.BACKEND_URL + content[indexOuter].audio, 1)" v-bind:class="{'number-clicked': content[indexOuter].saved === 1 && content[indexOuter].audio !== null, 'text-danger': content[indexOuter].audio === null}" class="number">{{indexOuter + 1}}. </label>
                        <span v-for="item, index in contentArray[indexOuter]"  v-on:click="setWord(item.word, index, indexOuter), audioHandler.playerHowler(config.BACKEND_URL + item.audio , 1)" v-bind:class="{'tooltips-clicked': item.click === 1 && item.audio !== null, 'text-danger': item.audio === null}" class="tooltips">
                          <label v-html="item.word + item.char + '&nbsp;'"></label>
                          <span class="tooltipTitle">{{item.translation}}</span>
                        </span>
                      </span>
                    </span>
                    <span class="row">
                      <span  class="content-translation col-lg-12 col-md-12 col-sm-12 col-xs-12 sentence-mobile content-translation-mobile">{{indexOuter + 1}}. {{content[indexOuter].translation}}</span>
                    </span>
                  </span>
                </div>

             <!--    <div class="lesson-content-tip">
                  <span class="tip-content" v-if="selectedLesson !== null">
                    <b v-if="(selectedLesson.grammar_audio !== null && selectedLesson.grammar_audio !== 'NONE') || (selectedLesson.grammar_tip !== null && selectedLesson.grammar_tip !== 'NONE')">Grammar Tip</b>
                    <br>
                    <i class="fa fa-volume-up regular-volume-up" @click="audioHandler.playerHowler(config.BACKEND_URL + selectedLesson.grammar_audio,'grammarAudioPlayButton1')" v-if="selectedLesson.grammar_audio !== null && selectedLesson.grammar_audio !== 'NONE'" v-bind:class="{'grammar-button-active': isGrammarActive === true, 'text-danger': selectedLesson.grammar_audio === null || selectedLesson.grammar_audio === 'NONE'}" id="grammarAudioPlayButton1"></i>
                    <i class="fa fa-sync regular-volume-up regular-sync repeat-audio-icon-ios" @click="audioHandler.playerHowler(config.BACKEND_URL + selectedLesson.grammar_audio,'grammarRepeatAudioPlayButton')" v-if="selectedLesson.grammar_audio !== null && selectedLesson.grammar_audio !== 'NONE'" v-bind:class="{'grammar-button-active': isGrammarActive === true, 'text-danger': selectedLesson.grammar_audio === null  || selectedLesson.grammar_audio === 'NONE'}" id="grammarRepeatAudioPlayButton"></i>
                    <br>
                    <label v-if="selectedLesson.grammar_tip !== null && selectedLesson.grammar_tip !== 'NONE'" v-html="selectedLesson.grammar_tip"></label>
                    <br>
                    <span style="margin-top:40px;width:100%;float:left;"></span>
                    <b v-if="(selectedLesson.culture_tip !== null && selectedLesson.culture_tip !== 'NONE') || (selectedLesson.culture_audio !== null && selectedLesson.culture_audio !== 'NONE')">Culture Tip</b>
                    <br>
                    <i class="fa fa-volume-up regular-volume-up" @click="audioHandler.playerHowler(config.BACKEND_URL + selectedLesson.culture_audio,'cultureAudioPlayButton')" v-if="selectedLesson.culture_audio !== null && selectedLesson.culture_audio !== 'NONE'" v-bind:class="{'culture-button-active': isCultureActive === true, 'text-danger': selectedLesson.culture_audio === null}" id="cultureAudioPlayButton"></i>
                    <i class="fa fa-sync regular-volume-up repeat-audio-icon-ios" @click="audioHandler.playerHowler(config.BACKEND_URL + selectedLesson.culture_audio,'cultureRepeatAudioPlayButton')" v-if="selectedLesson.culture_audio !== null && selectedLesson.culture_audio !== 'NONE'" v-bind:class="{'culture-button-active': isCultureActive === true, 'text-danger': selectedLesson.culture_audio === null}" id="cultureRepeatAudioPlayButton"></i>
                    <br>
                    <label  v-if="selectedLesson.culture_tip !== null && selectedLesson.culture_tip !== 'NONE'" v-html="selectedLesson.culture_tip"></label>
                  </span>
                </div> -->
              </div>
            <div  v-bind:class="{'is-active-am-word': responsiveView.audio, 'close-audio-manager':closeAudioManager, 'is-word-audio': isWordAudio, 'bg-white': audioSelected.word === null}" class="audio-manager">
              <!-- <span class="pull-right"><i class="fa fa-close audio-close" v-on:click="closeAudioManager()"></i></span> -->
            <div class="container">
              <div class="row">
                <div class="col-sm-12">
                  <div class="audio-holder">
                    <span class="audio-content-holder">
                        <span class="audio-icons" style="width:100px">
                          <i @click="audioHandler.visualizerPlay('visualizerAudioPlayButton')" class="fa fa-volume-up regular-volume-up"  v-bind:class="{'visualizer-button-active': isVisualizerActive === true, 'text-danger': audioSelected.audio === null}"  id="visualizerAudioPlayButton" v-if="audioSelected.word !== null"></i>
                          <i class="fa fa-sync regular-volume-up margin-audio-mobile repeat-audio-icon-ios"  v-bind:class="{'visualizer-button-active': isVisualizerActive === true, 'text-danger': audioSelected.audio === null}" @click="audioHandler.visualizerPlay('visualizerRepeatAudioPlayButton')" id="visualizerRepeatAudioPlayButton" v-if="audioSelected.word !== null"></i>
                        </span>
                        <span id="audio-content">
                          <span id="word" v-html="audioSelected.word" v-if="audioSelected.word !== null"></span>
                          <span id="translation" v-html="audioSelected.translation" v-if="audioSelected.word !== null"></span>
                        </span>
                    </span>
    <!--                 <div class="progress" v-bind:class="{'bg-white': audioSelected.word === null}"id="progressBarFlag">
                      <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" id="progressBar"></div>
                    </div> -->
                    <div class="spinner" id="spinnerFlag">
                      <i v-bind:class="{'text-primary': audioSelected.word !== null, 'text-white': audioSelected.word === null}" class="fas fa-circle-notch fa-spin"></i>
                    </div>
                    <span class="audio-visualizer" id="mp3_player">
                    </span>
                    <span class="audio-content-holder" v-if="audioSelected.word !== null">
                        <span class="audio-icons" style="margin-bottom: 5px;width:100px">
                          <i class="fa fa-volume-up regular-volume-up" @click="audioHandler.playerHowler(config.BACKEND_URL + audioSelected.syllabus_audio,'syllabusAudioPlayButton')"  v-bind:class="{'text-danger': audioSelected.syllabus_audio === null}" id="syllabusAudioPlayButton"></i>
                          <i class="fa fa-sync regular-volume-up margin-audio-mobile repeat-audio-icon-ios" @click="audioHandler.playerHowler(config.BACKEND_URL + audioSelected.syllabus_audio, 'syllabusRepeatAudioPlayButton')"  v-bind:class="{'text-danger': audioSelected.syllabus_audio === null}" id="syllabusRepeatAudioPlayButton"></i>
                        </span>
                        <span id="audio-content">
                          <span id="word" v-html="audioSelected.syllabus"></span>
                        </span>
                    </span>
                  </div>
                </div>
              </div>
              <div class="row">
                <!-- <span class="col-lg-6 col-md-8 col-sm-12 col-xs-12" v-if="audioSelected.video_url !== null" style="margin-bottom: 10px;">
                  <iframe title="vimeo-player" class="video vml-audio" :src="audioSelected.video_url" style="border:none;" allowfullscreen></iframe>
                </span> -->
              </div>
            </div>
              <div class="accent-holder" v-if="audioSelected.accent_text !== null && audioSelected.word !== null">
                <span class="accent-item">
                  <component :is="compiledData"></component>
                </span>
                  <!-- <accent-text :data="audioSelected.accent_text"></accent-text> -->
              </div>
              <!-- <div class="audio-list-holder test" v-if="listFlag === true && audioSelected.word !== null">
                <span class="word-images-holder" v-if="wordImage !== null">
                  <span class="src-image-holder" v-for="imageItem, imageIndex in wordImage">
                    <img v-bind:src="config.BACKEND_URL + imageItem.url" v-if="imageItem.type === 'OWN'" v-bind:onclick="'insertImageCarousel(\'' + config.BACKEND_URL + '\',\'' + JSON.stringify(wordImage) + '\', \'' + imageIndex + '\')'">
                    <img v-bind:src="imageItem.url" v-else v-bind:onclick="'insertImageCarousel(\'' + imageItem.url + '\')'">
                  </span>
                </span>
                <span class="audio-list-item" v-for="item, index in wordPopup" v-if="wordPopup.length !== 0">
                <span class="audio-list-button">
                  <i class="fa fa-volume-up small-volume-icons" v-bind:class="{'text-danger': item.audio === null}" @click="audioHandler.playerHowler(config.BACKEND_URL + item.audio, 'wordPopupAudio' + index + 'PlayButton')" v-bind:id="'wordPopupAudio' + index + 'PlayButton'">
                  </i>
                  <i class="fa fa-sync small-volume-icons margin-audio-mobile repeat-audio-icon-ios" v-bind:class="{'text-danger': item.audio === null}" @click="audioHandler.playerHowler(config.BACKEND_URL + item.audio, 'wordPopupRepeatAudio' + index + 'PlayButton')" v-bind:id="'wordPopupRepeatAudio' + index + 'PlayButton'">
                  </i>
                </span>
                <span class="extension-holder">
                  <span class="audio-list-content">{{index + 1}}.&nbsp;
                    <span class="tooltipsAudio" v-for="innerItem, innerIndex in contentArray2DAudio[index]">
                      <label  v-html="innerItem.word + innerItem.char + '&nbsp;'"></label>
                      <span class="tooltipTitleAudio">{{innerItem.translation}}</span>
                    </span>
                  </span>
                  <span  class="audio-list-translation">{{index + 1}}. &nbsp;{{item.translation}}</span>
                  </span>
                </span>
              </div> -->
              <div class="audio-list-holder test1" v-else>
                <span class="audio-list-item" v-for="item, index in sentenceSelectedListSuggestion" v-if="audioSelected.word !== null">
                  <span class="audio-list-button">
                    <i class="fa fa-volume-up small-volume-icons" v-bind:class="{'text-danger': item.audio === null}" @click="audioHandler.playerHowler(config.BACKEND_URL + item.audio + '\',\'sentencePopupAudio' + index + 'PlayButton')" v-bind:id="'sentencePopupAudio' + index + 'PlayButton'">
                    </i>
                  
                    <i class="fa fa-sync small-volume-icons margin-audio-mobile repeat-audio-icon-ios" v-bind:class="{'text-danger': item.audio === null}" @click="audioHandler.playerHowler(config.BACKEND_URL + item.audio + '\',\'sentencePopupRepeatAudio' + index + 'PlayButton')" v-bind:id="'sentencePopupRepeatAudio' + index + 'PlayButton'">
                    </i>
                  </span>
                  <span class="audio-list-content" v-if="contentArray2D !== null">{{index + 1}}.&nbsp;
                    <span class="tooltipsAudio" v-for="innerItem, innerIndex in contentArray2D[index]">
                      <label v-html="innerItem.word + innerItem.char + '&nbsp;'"></label>
                      <span class="tooltipTitleAudio">{{innerItem.translation}}</span>
                    </span>
                  </span>
                  <span  class="audio-list-translation">{{index + 1}}. &nbsp;{{item.translation}}</span>
                </span>
              </div>
            </div>
          </div>
          <div v-else class="text-center">
              <h5 class="text-danger">No content added to this lesson.</h5>
              <span>Please report to support@talkfluent.com</span>
          </div>
          </div>
          <div class="modal-footer">
            <!-- <div class="column" v-if="videoUrl !== null" :class="{'hide-video': hideVideo === true}">
                <iframe title="vimeo-player" class="video vml-footer" :src="videoUrl" style="border:none;" allowfullscreen></iframe>
            </div> -->
            <div class="column">
              <button type="button" class="btn btn-primary closeBtn pull-right" id="closeBtn" data-dismiss="modal" data-target="#dashboardModal" aria-label="Close" @click="audioHandler.pause()">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- <div class="modal fade modal2" id="videoModal" tabindex="-1" role="dialog" aria-hidden="true" style="backdrop-filter: opacity(20%);z-index:1051 !important;">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-body">
                    <iframe title="vimeo-player" :src="videoUrl" width="100%" height="300" style="border:none;" allowfullscreen></iframe>
                </div>
            </div>
        </div> -->
  </div>
</template>
<style>
  body{
    background: #fff;
  }
  small {
    font-size: 100%;
  }
  #video{
    margin-top: -5% !important;
    margin-bottom: -7% !important;
    /* width: 300px */
  }  
  #video2{
    margin-bottom: -10% !important;
    margin-top:-10% !important
  }
  .row::after {
  content: "";
  clear: both;
  display: block;
}
@media only screen and (max-width: 768px) {
  /* For mobile phones: */
  [class*="col-"] {
    width: 100%;
  }
}
  .dis-button:hover{
    cursor: default !important;
    background: #ffc107;
    border-color: #ffc107;
  }
  .td-font{
    font-size: 20px;
  }
  .checked i{
    color: #1caceb;
  }
  .checked td{
    color: black;
  }
  .hover-on{
    color: #a1a1a1;
  }
  .hover-on:hover{
    background-color: #efefef;
  }
  .history{
    margin-top: 25px;
  }
  .bg-primary{
    background: #1caceb !important; 
  }
  .form-control{
    height: 45px !important;
  }
  .modal{
    width: 100%;
    position: fixed;
    padding-left: 0px !important;
  }
  .dash-header{
    text-align: center;
  }
  .empty-lesson{
    height: 50px;
    width: 100%;
    font-size: 24px;
    color: #ff0000;
  }
  td button i{
    padding-right: 10px;
  }
  thead{
    font-weight: 700;
  }
  .audio_container{
    visibility: hidden;
  }
  .welcome-title{
    margin: 20px 0 20px 0;
  }
  .header-dashboard{
    width: 100%;
    background: #ccc;
    float: left;
    text-align: center;
    font-weight: 500;
    font-size:20px;
    min-height: 50px;
    overflow-y: hidden;
    padding-top: 10px;
    float: left;
  }
  .available-lesson{
    text-align: left;
    padding-left: 20px;
    margin-top: 10px;
  }
  .wistia-holder{
    float: left; 
    height: 300px;
    width: 100%;
  }
  .redirect-buttons{
    height: 50px;
    width: 20%;
    float: left;
    margin-right: 1%;
    margin-top: 10px;
    font-size:20px;
    text-align: center;
  }
  .redirect-buttons label{
    padding-top: 10px;
  }
  .redirect-buttons:hover{
    cursor: pointer;
  }
  
  .speech-bubble {
    position: relative;
    background: #1caceb;
    border-radius: 4em;
    text-align: center;
    height: 450px;
    padding-top: 10%;
  }
.speech-bubble:after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  width: 0;
  height: 0;
  border: 20px solid transparent;
  border-top-color: #1caceb;
  border-bottom: 0;
  border-left: 0;
  margin-left: -10px;
  margin-bottom: -20px;
}
.speech-bubble h1{
  color: white;
  font-size: 150px;
}
.speech-bubble h2{
  color: gray;
  font-size: 75px;
}
.table{
  margin-top: 50px;
  margin-bottom: 50px;
}
.top-lesson-holder{
  width: 100%;
  min-height: 40px;
  float: left;
  overflow: hidden;
}
.top-lesson-content{
  width: 100%;
  float: left;
  height: 100%;
  margin-bottom: 10px;
}
.category-lesson-holder{
  width: 90%;
  min-height: 40px;
  float: left;
  margin-left: 10%;
  overflow: hidden;
  margin-bottom: 10px;
}
.category-lesson-content{
  width: 100%;
  float: left;
  height: 100%;
}
.lesson-holder{
  width: 90%;
  margin-left: 10%;
  float: left;
  padding-top: 15px;
  padding-bottom: 10px;
  font-size: 16px;
  border-bottom: 1px solid #ccc;
}
.lesson-holder-index{
  padding-left: 10px;
  padding-right: 10px;
}
.lesson-holder:hover{
  background: #1caceb;
  cursor: pointer;
  color: white;
}
.lesson-holder:hover .test-check{
  color: #1caceb;
  background: #fff;
}
.lesson-holder-text{
  float: left;
  height: 100%;
  margin-right: 20px;
}
.lesson-holder-test-checker{
  float: left;
  height: 100%;
}
.test-check-number, .test-check{
  background: #888;
  padding-top: 7px;
  width: 40px;
  height: 40px;
  border-radius: 20px;
  color: #fff;
  text-align: center;
}
.test-check{
  color: #fff;
  text-align: center;
  background: #1caceb;
}
.test-check i{
  font-size: 18px;
}
.modal-body{
  min-height: 50px !important;
  overflow: hidden;
}
/*
  CONTENT
*/
.mobile-viewer{
  display: none;
}
.mobile-viewer-button1, .mobile-viewer-button2{
  width: 48%;
  float: left;
  text-align: center;
  border: 1px solid #ccc;
  margin-bottom: 20px;
  margin-top: 20px;
  height: 50px;
}
.mobile-viewer-button1{
  margin-left: 2%;
}
.mobile-viewer-button1 h5, .mobile-viewer-button2 h5{
  line-height: 50px;
  margin-bottom: 0px;
}
.mobile-viewer-button1:hover, .mobile-viewer-button2:hover{
  cursor: pointer;
  background: #1caceb;
  color: #fff;
  border: 1px solid #1caceb;
}
.content-manager{
  float: left;
  width: 55%;
  display: block;
}
.lesson-content-holder{
  width: 96%;
  min-height: 200px;
  overflow: hidden;
  margin: 0 2% 0 2%;
  float: left;
  }
.content-translation{
  font-size: 20px;
}
.content-translation{
  color: #ccc;
}
.content-items{
  float: left;
  width: 90%;
  margin-left: 10%;
}
.content-items label{
  margin-bottom: 0px;
}
/*
      
    GRAMMAR AND CULTURE TIP
*/
.lesson-content-tip{
  width: 100%;
  min-height: 200px;
  float: left;
  font-size: 16px !important;
  overflow: hidden;
  margin-top: 50px;
}
.tip-header{
  width: 96%;
  float: left;
  margin: 0 2% 0 2%;
}
.fa-play-circle{
  color: #1caceb;
  padding-right: 10px;
  font-size: 50px;
}
.tip-header .icon{
  width: 8%;
  float: left;
}
.tip-header .text{
  width: 92%;
  float: left;
  padding-top: 10px;
}
.tip-content{
  width: 96%;
  float: left;
  margin: 0 2% 0 2%;
}
.tip-content label{
  text-align: justify;
}
/*
    
  AUDIO MANAGER
*/
.audio-manager{
  float: left;
  width: 45%;
  display: none;
  background: #eee;
  min-height: 450px;
  overflow: hidden;
}
.close-audio-manager{
}
.is-word-audio, .is-sentence-audio{
  display: block;
}
.audio-holder{
  width: 96%;
  float: left;
  margin: 20px 2% 0 2%;
}
.audio-icons{
  float: left;
  min-height: 50px;
  width: 20%;
  overflow-y: hidden;
}
#audio-content{
  min-height: 50%;
  width: 80%;
  float: left;
  overflow-y: hidden;
}
#audio-content #word, #audio-content #translation{
  min-height: 25px;
  width: 100%;
  float: left;
  overflow-y: hidden;
}
#audio-content #word{
  font-size: 20px;
  font-weight: 400;
} 
#audio-content #word b, #audio-content #translation b{
  font-weight: 600;
}
#audio-content #translation{
  font-weight: 400;
  font-size: 18px;
  color: #555;
}
.small-audio-icons{
  height: 40px;
}
.small-word{
  height: 20px;
  float: left;
  font-weight: 600;
}
.small-audio:hover{
  text-decoration: underline;
  cursor: pointer;
}
.small-translation{
  height: 20px;
  width: 85%;
  float: left;
  margin-left: 5%;
  color: #555;
}
.lesson-button-active{
  background: #fff;
  border: solid 2px #1caceb;
  color: #1caceb;
}
.audio-button-active, .grammar-button-active, .culture-button-active, .visualizer-button-active, .syllabus-button-active{
  background: #1caceb;
  border: solid 2px #fff;
  color: #fff;
}
.audio-close{
  padding: 10px;
  color: #fff;
}
.audio-close:hover{
  cursor: pointer;
}
.audio-content-holder{
  min-height: 60px;
  width: 100%;
  float: left;
  overflow-y: hidden;
}
/*
      STRESS AND ACCENT
*/
.accent-holder{
  width: 96%;
  float: left;
  height: auto;
  margin: 0 2% 0 2%;
  background: #fff;
  text-align: justify;
}
.accent-header-holder{
  width: 100%;
  float: left;
  min-height: 50px;
  overflow-y: hidden;
  color: #1caceb;
}
.accent-header{
  width: 98%;
  float: left;
  min-height: 30px;
  font-weight: 600;
  margin: 0 1% 0 1%;
  z-index: 10000;
  font-size: 28px !important;
  overflow-y: hidden;
  text-align: justify;
  display: inline;
}
.accent-header label{
  display: initial;
  text-transform: capitalize;
}
.accent-header span{
  text-transform: none;
}
.accent-header span span{
  display: inline-block;
}
.accent-header a:hover{
  cursor: pointer !important;
  text-decoration: underline !important;
}
.accent-item-holder{
  height: 300px;
  width: 100%;
  overflow-y: scroll;
  float: left;
}
.accent-item:last-child{
  margin-bottom: 100px !important;
}
.accent-header-extra{
  width: 98%;
  float: left;
  height: 30px;
  font-weight: 600;
  margin: 0 1% 0 1%;
  font-size: 20px !important;
}
.accent-header-extra-info{
  width: 98%;
  float: left;
  position: relative;
  font-weight: 600;
  margin: 0 1% 0 1%;
  font-size: 20px !important;
}
.accent-item{
  height: auto;
  width: 96%;
  margin: 10px 2% 10px 2%;
  float: left;
}
.accent-content{
  width: 100%;
  float: left;
  height: auto;
}
.accent-content-left{
  width: 22%;
  float: left;
}
.accent-audio{
  width: 50%;
  float: left;
}
.accent-letter{
  width: 50%;
  float: left;
  margin-top: -15px;
  text-align: center;
}
.accent-letter label{
  width: 100%;
  float: left;
  font-size: 50px;
}
.accent-letter .bottom{
  font-size: 15px;
  margin-top: -20px;
  color: #555;
}
.accent-letter-defi{
  width: 72%;
  margin-left: 6%;
  float: left;
  text-align: justify;
}
.accent-letter-defi ul{
  float: left;
}
.accent-letter-defi-extra{
  width: 88%;
  float: left;
  text-align: justify;
}
.accent-media-holder{
  width: 100%;
  float: left;
  text-align: center;
}
.accent-media-holder-extra{
  width: 100%;
  float: left;
  text-align: center;
}
.accent-media-item{
  float: left;
  height: auto;
  width: 100%;
  margin-top: 5px;
}
.accent-media-item:hover{
  cursor: pointer;
  border: 1px solid #ccc;
}
.audio-list-holder{
  height: auto;
  width: 96%;
  margin: 25px 2% 25px 2%;
  float: left;
  font-size: 16px !important;
}
.audio-list-holder .word-images-holder{
  width: 100%;
  float: left;
  min-height: 102px;
  margin-bottom: 25px;
  overflow-y: auto;
}
.word-images-holder .src-image-holder{
  float: left;
  width: 76px;
  height: 102px;
  margin-right: 2px;
}
.src-image-holder img{
  max-width: 100%;
  height: 102px;
}
.word-images-holder .src-image-holder:hover{
  cursor: pointer;
}
.audio-list-item{
  height: auto;
  width: 100%;
  float: left; 
}
.audio-list-item .original{
  padding-left: 10px;
}
.audio-list-item .translation{
  padding-left: 20px;
}
/*
  AUDIO VISUALIZER
    
*/
.audio-visualizer{
  max-height: 64px;
  width: 100%;
  float: left;
  overflow: hidden;
  margin-bottom: 20px;
  margin-top: 20px;
  visibility: hidden;
}
.audio-visualizer > canvas{ 
  width:90%;
  height:70px;
  margin: 15px 5% 15px 5%;
  float:left;
}
.spinner{
  width: 100%;
  height: 60px;
  font-size: 50px;
  line-height: 60px;
  z-index: 10;
  text-align: center;
  display: none;
}
.fa-spin {
  animation-duration: 1s;
}
.progress{
  width: 100%;
  height: 20px;
  float: left;
}
.active-visualizer{
  visibility: visible;
}
.hide-progress-bar{
  display: none;
}
/* Tooltip container */
.tooltips {
  position: relative;
  display: inline-block;
  color: gray;
  font-size: 20px;
  z-index: 10000 !important;
}
.tooltipsAudio{
  position: relative;
  display: inline-block;
  color: gray;
  color:#000;
  margin-right: 5px;
  font-size: 16px;
}
.tooltipsAudio label{
  margin-bottom: 0px;
}
.number{
  color: gray;
  font-size: 20px;
}
.number:hover{
  cursor: pointer;
  text-decoration: underline;
  color: #1caceb;
}
.number-clicked{
  color: black;
}
.tooltips-new-line {
    position: relative;
    color: gray;
}
.tooltips-clicked{
    color: #000 !important;
}
/* 
    Tooltip text 
*/
.tooltips .tooltipTitle,  .tooltips-new-line .tooltipTitle{
    visibility: hidden;
    width: 120px;
    background-color: #e1e1e1;
    color: black;
    text-align: center;
    border-radius: 6px;
    padding: 5px 0;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    margin-left: -60px;
    opacity: 0;
    transition: opacity 1;
    white-space: pre-line;
    font-size: 20px;
}
.tooltipsAudio .tooltipTitleAudio{
    visibility: hidden;
    width: 80px;
    background-color: #e1e1e1;
    color: black;
    text-align: center;
    border-radius: 6px;
    padding: 5px 0;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    margin-left: -40px;
    opacity: 0;
    transition: opacity 1;
    white-space: pre-line;
    font-size: 12px;
}
/* Tooltip arrow */
.tooltips .tooltipTitle::after, .tooltips-new-line .tooltipTitle::after{
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #e1e1e1 transparent transparent transparent;
}
.tooltipsAudio .tooltipTitleAudio::after{
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #e1e1e1 transparent transparent transparent;
}
.audio-list-button{
  width: 20%;
  float: left;
  margin-bottom: 5px;
}
.extension-holder{
  width: 80%;
  float: left;
  margin-bottom: 5px;
}
.audio-list-content{
  width: 100%;
  float: left;
}
.audio-list-translation{
  width: 100%;
  float: left;
  color: #aaa;
}
/* Show the tooltip text when you mouse over the tooltip container */
.tooltips:hover .tooltipTitle, .tooltips-new-line:hover .tooltipTitle, .tooltipsAudio:hover .tooltipTitleAudio {
    visibility: visible;
    opacity: 1;
}
/*
    GUIDE VIDEO
*/
.guide-holder{
  width: 70px;
  margin-right: 10px;
  right: 0;
}
.guide-holder label{
  float: left;
  text-transform: uppercase;
  font-size: 10px;
  font-weight: 600;
}
.guide-holder .guide-video{
  float: left;
  height: 40px;
  width: 70px;
  margin-top: 5px;
}
.account-error .error-message{
  width: 100%;
  float: left;
  min-height: 10px;
  overflow-y: hidden;
  margin-top: 10px;
}
.account-error .error-action{
  width: 100%;
  float: left;
  min-height: 10px;
  overflow-y: hidden;
  margin-top: 10px;
}
.show-on-desktop{
  display: block;
}
.show-on-mobile{
  display: none;
}
.margin-audio-mobile{
  margin-top: 0px;
}
.popover_content, .pronounce_popover_content {
  transform: translate(0,10px);
  padding: 10px;
  width: auto;
  background: #1caceb;
  border: solid 1px #1caceb;
  min-height: 10px;
  font-size: 15px;
  color: #fff;
  font-weight: 400;
  text-align: justify;
  display: none;
  border-bottom-right-radius: 2px;
  border-bottom-left-radius: 2px;
  border-top-right-radius: 2px;
  position: absolute;
  z-index: 10000;
}
.popover_content .header-popover, .pronounce_popover_content .header-popover{
  float: left;
  width: 100%;
  height: 30px;
  text-align: right;
}
.header-popover i{
  line-height: 30px;
  padding-bottom: 5px;
  font-size: 15px;
}
.popover_content:before {
  position: absolute;
  z-index: -1;
  content: '';
  left: 5%;
  top: -10px;
  border-style: solid;
  border-width: 0 10px 10px 10px;
  border-color: transparent transparent #1caceb transparent;
  transition-duration: 0.3s;
  transition-property: transform;
}
.pronounce_popover_content:before {
  position: absolute;
  z-index: -1;
  content: '';
  left: 0%;
  top: -10px;
  border-style: solid;
  border-width: 0 10px 10px 10px;
  border-color: transparent transparent #1caceb transparent;
  transition-duration: 0.3s;
  transition-property: transform;
}
.popover_title{
  padding-right: 5px;
}
.popover_title:hover{
  cursor: pointer;
}
/*.popover_title:hover + .popover_content {
  z-index: 10;
  opacity: 1;
  cursor: pointer;
  display: block;
  transition: all 0.5s cubic-bezier(0.75, -0.02, 0.2, 0.97);
}
*/
.active-popover{
  z-index: 10;
  opacity: 1;
  cursor: pointer;
  display: block;
  transition: all 0.5s cubic-bezier(0.75, -0.02, 0.2, 0.97);
}
.column{
  width: 50%;
  float: left;
}
.video{
  width: auto;
  height: 175px;
}
.vml-footer{
  margin-left: 10%;
}
.vml-audio{
  margin-left: 2%;
}
.hide-video{
  display: block;
}
@media (max-width: 992px) {
  .column{
    width: 100%;
    float: left;
  }
  .video{
    width: 100%;
  }
  .vml-footer{
    margin-left: 0%;
    margin-top: 50px;
    margin-bottom: 50px;
  }
  .modal-footer{
    display: unset;
  }
}
@media screen and (max-width: 767px) {
  .second-video{
    margin-top: 10px;
  }
  .redirect-buttons{
    width: 30%;
    margin-right: 2%;
  }
  .show-on-desktop{
    display: none;
  }
  .show-on-mobile{
    display: block;
  }
  .hide-video{
    display: none;
  }
}
@media screen and (max-width: 400px) {
  .redirect-buttons{
    font-size: 14px;
  }
  .redirect-buttons label{
    padding-top: 15px;
  }
  .show-on-desktop{
    display: none;
  }
  .show-on-mobile{
    display: block;
  }
}
@media  only screen and (max-width: 610px){
   #video2{
    width:95%;
    margin-top:-5% !important;
    margin-bottom: -5% !important;
    margin-left:-2% !important
  } 
}
@media (min-width: 768px){
  .col-md-6{
    max-width: 100%;
  }
}
@media  only screen and (min-width: 768px)  and (max-width: 768px){
   #video2{
    width:95%;
    margin-top:-5% !important;
    margin-bottom: -5% !important;
    margin-left:-2% !important
  } 
   .col-md-6{
    flex: none;
    max-width: -50%;
  }
  #video{
    min-width: 200px;
    margin-left:auto;
    margin-right:auto;
    display: block;
  }
}
@media screen and (min-width: 200px) and (max-width: 991px) {
  #video{
    width: 100%;
    min-width: 40% !important;
    margin-left:auto;
    margin-right:auto;
    display: block;
    margin-bottom: -5%;
  }
  .closeBtn{
    width: 100%;
    margin-left: auto;
    margin-right: auto;
    display: block;
    margin-top: 1px;
  }
  .col-md-6{
    flex: none;
    max-width: -50%;
  }
  #video2{
    width:95%;
    margin-top:-1% !important;
    margin-bottom: -5% !important;
  }
 
  .mobile-viewer{
    display: block;
  }
  .content-manager, .audio-manager{
    width: 100%;
  }
  .content-manager, .audio-manager{
    display: none;
  }
  .is-active-cm, .is-active-am-sentence, .is-active-am-word{
    display: block;
  }
  .page-active-content, .page-active-audio{
    background: #1caceb;
    color: #fff;
    border: 1px solid #1caceb;
  }
  .td-font{
    font-size: 15px;
  }
   .speech-bubble {
    height: 300px;
   }
   .history{
    text-align: center;
   }
   .center-small{
    text-align: center;
   }
  .speech-bubble h1{
    color: white;
    font-size: 100px;
  }
  .speech-bubble h2{
    color: gray;
    font-size: 75px;
  }
  .sentence-mobile{
    width: 100%;
    float: left;
  }
  .wistia-holder{
    height: 300px;
  }
  .header-dashboard{
    font-size: 16px;
  }
  .top-lesson-holder{
    text-align: left;
  }
  .top-lesson-content{
    text-align: left;
  }
  .category-lesson-holder{
    text-align: left;
  }
  .category-lesson-content{
    text-align: left;
  }
  .lesson-holder{
    padding-top: 15px;
    padding-bottom: 10px;
    font-size: 14px;
  }
  .lesson-holder-index{
    padding-left: 5px;
    padding-right: 5px;
  }
  .accent-content-left{
    width: 22%;
    float: left;
  }
  .accent-audio{
    width: 100%;
    float: left;
    text-align: center;
  }
  .accent-letter{
    width: 100%;
    float: left;
    margin-top: -15px;
  }
  .accent-letter label{
    font-size: 50px;
  }
  .accent-letter-defi{
    width: 70%;
    float: left;
    text-align: justify;
    margin-left: 8%;
  }
  .accent-letter-defi-extra{
    width: 90%;
    float: left;
    text-align: justify;
    margin-left: 5%;
  }
  .accent-media-holder-extra{
    width: 90% !important;
    margin-left: 5% !important;
  }
  .accent-media-holder-extra .accent-media-item{
    height: 70px;
  }
  .show-on-desktop{
    display: none;
  }
  .show-on-mobile{
    display: block;
  }
  .margin-audio-mobile{
    margin-top: 5px;
  }
}
</style>
<script src="https://fast.wistia.com/embed/medias/4fqxpgrjw1.jsonp" async></script>
<script src="https://fast.wistia.com/assets/external/E-v1.js" async></script>
<script src="https://fast.wistia.com/embed/medias/36v1optnqo.jsonp" async></script>
<script src="https://fast.wistia.com/assets/external/E-v1.js" async></script>
<script src="https://fast.wistia.com/embed/medias/ud7le43chh.jsonp" async></script>
<script src="https://fast.wistia.com/assets/external/E-v1.js" async></script>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import CONFIG from '../../config.js'
import WaveSurfer from 'wavesurfer.js'
import {Howl, Howler} from 'howler'
import AudioScript from '../../audio.js'
import * as Audio from '../../components/audio'
import axios from 'axios'
export default {
  mounted(){
    AUTH.redirectToLogin()
    this.getLesson()
    window.onresize = () => {
      if(window.innerWidth <= 400 && this.pageActiveAudio === true){
        console.log('active', this.pageActiveAudio)
        this.showVideo = false
        this.pageActiveAudio = true
      }else{
        this.showVideo = true
        console.log('deactive', this.pageActiveAudio)
        this.pageActiveAudio = false
      }
    }
    // window.onresize = () => {
    //   console.log(window.innerWidth)
    //   if(window.innerWidth < 400){
    //     $('#closeBtn').css('width', '100%')
    //     $('#video').css({
    //       'width': '300px'
    //     })
    //   }else{
    //     $('#video').css({
    //       'width': '400px',
    //       'min-width': '200px',
    //       'margin-left': 'auto',
    //       'margin-right': 'auto',
    //       'display': 'block',
    //       'margin-bottom': '-5%'
    //     })
    //     $('.closeBtn').css({
    //       'width': '100%',
    //       'margin-left': 'auto',
    //      ' margin-right': 'auto',
    //       'display': 'block'
    //     })
    //   }
    // }
  },
  created(){
    this.initWistia()
    // this.initBasicAudio()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      lessonFlag: false,
      config: CONFIG,
      dashboardLessons: [],
      lesson: [],
      selectedLesson: null,
      selectedLessonId: null,
      content: [],
      selectedContent: null,
      selectedContentIndex: null,
      active: 0,
      clicked: 0,
      acc: null,
      audio: null,
      contentArray: [],
      wordAudio: null,
      pageActiveAudio: false,
      pageActiveContent: true,
      responsiveView: {
        content: true,
        audio: false
      },
      audioSelected: {
        id: null,
        word: null,
        translation: null,
        path: null,
        audio: null,
        video_url: null
      },
      audioSelectedListSuggestion: [],
      audioManagerClose: true,
      isWordAudio: false,
      isSentenceAudio: false,
      sentenceResponseView: {
        audio: false
      },
      sentenceSelectedListSuggestion: [],
      listFlag: true,
      isClickedNumber: false,
      wavesurfer: null,
      sentenceAudio: null,
      sentenceAudioIndex: null,
      sentenceAudioPath: null,
      contentArray2D: [],
      contentArray2DAudio: [],
      wordPopup: null,
      wistia: {
        url: 'https://fast.wistia.com/assets/external/E-v1.js',
        charset: 'ISO-8859-1'
      },
      activeButton: false,
      isLBActive: false,
      isCultureActive: false,
      isGrammarActive: false,
      isVisualizerActive: false,
      isSyllabusActive: false,
      lessonAudio: null,
      syllabusAudio: null,
      listAudios: null,
      listSentenceAudios: null,
      accentAudio: null,
      drawnFlag: false,
      onReady: false,
      parameterStart: 'playBasicAudio(\'',
      parameterVisualizerStart: 'playVisualizerAudio(\'',
      parameterEnd: '\')',
      mobileFlag: false,
      emailAddress: null,
      errorMessage: null,
      updateEmailFlag: false,
      verificationFlag: false,
      verificationMessage: null,
      upgradeStatusFlag: false,
      upgradeStatusMessage: null,
      wordImage: null,
      audioHandler: Audio,
      accentText: null,
      hasVideo: false,
      videoUrl: null,
      hideVideo: false
    }
  },
  components: {
    'test-checker': require('modules/checker/test.vue')
    // 'accent-text': require('modules/dashboard/AccentText.vue')
  },
  computed: {
    compiledData(){
      return {
        template: `<span>${this.accentText}</span>`,
        data(){
          return {
            popOverFlag: false,
            sentencePopOverFlag: null,
            pronounciationPopOverFlag: null,
            sentenceflag: false,
            previousSentenceIndex: null
          }
        },
        methods: {
          playerHowler: function(p1, p2){
            Audio.playerHowler(p1, p2)
          },
          pause: function(){
            Audio.pause()
          },
          setSentencePopOver(index){
            this.sentenceflag = !this.sentenceflag
            this.pronounciationPopOverFlag = null
            this.pause()
            if(this.sentenceflag === true){
              this.sentencePopOverFlag = index
              this.previousSentenceIndex = index
            }else{
              if(this.previousSentenceIndex !== null && this.previousSentenceIndex !== index){
                this.sentencePopOverFlag = index
                this.sentenceflag = true
                this.previousSentenceIndex = index
              }else{
                this.sentencePopOverFlag = null
                this.previousSentenceIndex = null
                this.sentenceflag = false
              }
            }
          },
          setPronounciationPopOver(index){
            this.sentencePopOverFlag = null
            this.sentenceflag = false
            if(this.pronounciationPopOverFlag == null){
              this.pronounciationPopOverFlag = index
            }else{
              this.pronounciationPopOverFlag = null
              this.pause()
            }
          }
        }
      }
    }
  },
  methods: {
    initiateAudioIcon(){
      let iDevices = [
        'iPad Simulator',
        'iPhone Simulator',
        'iPod Simulator',
        'iPad',
        'iPhone',
        'iPod'
      ]
      let flag = false
      if (navigator.platform) {
        while (iDevices.length) {
          if (navigator.platform === iDevices.pop()){
            $('.repeat-audio-icon-ios').css({display: 'none'})
            // console.log('ios')
            flag = true
          }
        }
      }
      if(flag === false){
        // $('.repeat-audio-icon-ios').css({display: 'none'})
        // console.log('other')
      }
    },
    initWistia(){
      let script = document.createElement('script')
      script.type = 'text/javascript'
      script.src = this.wistia.url
      script.charset = this.wistia.charset
      document.body.appendChild(script)
    },
    initBasicAudio(){
      let script = document.createElement('script')
      script.type = 'text/javascript'
      script.src = AudioScript
      document.body.appendChild(script)
    },
    setModalStatic(){
      $('#myModal').modal({
        backdrop: 'static',
        keyboard: true
      })
    },
    redirect(parameter){
      ROUTER.push(parameter)
    },
    getLesson(){
      let parameter = {
        'account_id': this.user.userID
      }
      // $('#loading').css({display: 'block'})
      this.APIRequest('lesson/dashboard', parameter).done(response => {
        // $('#loading').css({display: 'none'})
        this.dashboardLessons = response.data
        response.data.map(el => {
          el.category.map(el2 => {
            el2.lesson.map(res => {
              if(res.video_url === null){
                this.hasVideo = false
              }else{
                this.hasVideo = true
                this.videoUrl = res.video_url
              }
            })
          })
        })
      })
    },
    retrieveContent(id, lesson){
      this.selectedLessonId = id
      this.testParameter = id
      this.setModalStatic()
      let parameter = {
        'column': 'lesson_id',
        'clause': '=',
        'value': id,
        'account_id': this.user.userID
      }
      this.contentArray = []
      this.isWordAudio = true
      this.isSentenceAudio = false
      this.selectedLesson = lesson
      let lessonAudiosURL = []
      lessonAudiosURL[0] = CONFIG.BACKEND_URL + lesson.lesson_audio
      lessonAudiosURL[1] = CONFIG.BACKEND_URL + lesson.grammar_audio
      lessonAudiosURL[2] = CONFIG.BACKEND_URL + lesson.culture_audio
      $('#loading').css({'display': 'block'})
      this.APIRequest('content/dashboard', parameter).done(response => {
        console.log(response)
        if(response.message === null){
          this.content = response.content
          this.contentArray = response.data
          this.audioSelectedListSuggestion = response.content
          this.listFlag = true
          this.selectedContentIndex = 0
          this.selectedContent = this.audioSelectedListSuggestion[0]
        }else{
          this.content = null
        }
        setTimeout(() => {
          $('#loading').css({'display': 'none'})
          $('#dashboardModal').modal({
            backdrop: 'static',
            keyboard: true,
            show: true
          })
          this.testView()
          this.initiateAudioIcon()
        }, 50)
      })
    },
    initVisualizer(){
      let parameter = {
        container: '#mp3_player',
        waveColor: '#1caceb',
        progressColor: '#1caceb',
        hideScrollbar: true,
        height: 128
        // backend: 'MediaElement'
      }
      if(this.wavesurfer === null){
        this.wavesurfer = WaveSurfer.create(parameter)
      }else{
        this.wavesurfer.empty()
      }
      this.drawnFlag = false
      this.wavesurfer.load(CONFIG.BACKEND_URL + this.audioSelected.audio)
      $('#visualizerAudioPlayButton').css({'background': '#1caceb', 'color': '#FFF'})
      // this.pauseAllAudios()
      $('.progress-bar').css({'width': '0%'})
      this.wavesurfer.on('loading', (response) => {
        this.drawnFlag = false
        $('.progress-bar').css({'width': response + '%'})
      })
      this.wavesurfer.on('ready', () => {
        $('.progress-bar').css({'width': 100 + '%'})
        setTimeout(() => {
          this.drawnFlag = true
          // this.wavesurfer.play()
        }, 500)
      })
      if(this.drawnFlag === true && this.wavesurfer.isPlaying() === false){
        // this.wavesurfer.play()
      }
    },
    play(){
      // this.pauseAllAudios()
      if(this.wavesurfer !== null){
        this.wavesurfer.play(0)
      }
    },
    makeActiveButtons(buttonNumber){
      this.clearAllActive()
      if(buttonNumber === 1){
        this.isLBActive = true
      }
      if(buttonNumber === 2){
        this.isGrammarActive = true
      }
      if(buttonNumber === 3){
        this.isCultureActive = true
      }
      if(buttonNumber === 4){
        this.isVisualizerActive = true
      }
      if(buttonNumber === 5){
        this.isSyllabusActive = true
      }
    },
    deleteLesson(index){
      let parameter = {
        id: index
      }
      this.APIRequest('lesson/delete', parameter).then(response => {
        ROUTER.push('/dashboard')
      })
    },
    saveWord(wordId){
      let parameter = {
        'account_id': this.user.userID,
        'word_audio_id': wordId,
        lesson_id: this.selectedLessonId
      }
      this.APIRequest('save/create', parameter).then(response => {
      })
    },
    wordRetrieve(word, classParameter){
      let parameter = {
        'condition': [{
          'column': 'word',
          'clause': '=',
          'value': word
        }]
      }
      this.wordAudio = ''
      this.APIRequest('word_audio/retrieve', parameter).done(response => {
        if(response.data.length !== 0){
          this.wordAudio = response.data[0].translation
        }else{
          this.wordAudio = 'Not Found'
        }
      })
    },
    setActive(view){
      if(view === 'content'){
        this.responsiveView.content = true
        this.responsiveView.audio = false
        this.pageActiveContent = true
        this.pageActiveAudio = false
        this.sentenceResponseView.audio = false
        this.hideVideo = false
      }else if(view === 'sentence'){
        this.audioIsActive = false
        this.sentenceResponseView.audio = true
        this.responsiveView.content = false
        this.responsiveView.audio = false
        this.hideVideo = false
      }else{
        this.responsiveView.content = false
        this.responsiveView.audio = true
        this.pageActiveContent = false
        this.pageActiveAudio = true
        this.sentenceResponseView.audio = false
        this.hideVideo = true
      }
    },
    setWord(word, index, indexOuter){
      if(this.contentArray[indexOuter][index].audio !== null){
        let parameter = {
          'column': 'word',
          'clause': '=',
          'value': word,
          'account_id': this.user.userID
        }
        // this.pauseAllAudios()
        $('#loading').css({display: 'block'})
        this.contentArray2DAudio = []
        this.APIRequest('word_audio/dashboard', parameter).done(response => {
          $('#loading').css({display: 'none'})
          if(response.data !== null){
            // console.log(this.audioSelected.video_url)
            this.audioSelected = response.data[0]
            // if(this.audioSelected.video_url !== null){
            //   this.audioSelected.video_url = 'https://player.vimeo.com/video/235215203'
            // }
            this.contentArray2DAudio = response.content
            this.wordPopup = response.word
            console.log('audioSelected', this.audioSelected)
            // this.clearAllActive()
            this.wordImage = response.word_images
            this.responsiveView.content = false
            this.responsiveView.audio = true
            this.pageActiveContent = false
            this.pageActiveAudio = true
            this.listFlag = true
            this.isWordAudio = true
            this.contentArray[indexOuter][index].click = 1
            this.accentText = this.audioSelected.accent_text
            this.hideVideo = true
            // this.loadSyllabusAudios(CONFIG.BACKEND_URL + this.audioSelected.syllabus_audio)
            // this.loadListAudios()
            this.saveWord(this.audioSelected.id)
            setTimeout(() => {
              this.initiateAudioIcon()
            }, 100)
          }else{
            this.contentArray2DAudio = null
            this.audioSelected.word = null
            // this.audioSelected.video = null
            this.responsiveView.content = true
            this.responsiveView.audio = false
            this.pageActiveContent = true
            this.pageActiveAudio = false
            this.hideVideo = false
            setTimeout(() => {
              this.initiateAudioIcon()
            }, 100)
          }
          AUTH.checkAuthentication(null)
        })
      }else{
        this.contentArray2DAudio = null
        this.audioSelected.word = null
        this.audioSelected.video_url = null
        this.responsiveView.content = true
        this.responsiveView.audio = false
        this.pageActiveContent = true
        this.pageActiveAudio = false
        this.hideVideo = false
      }
    },
    loadVisualizer(path){
      this.wavesurfer.load(CONFIG.BACKEND_URL + path)
    },
    closeAudioManager(){
      this.audioManagerClose = false
    },
    changeSelected(item){
    },
    clearAllActive(){
      this.isLBActive = false
      this.isGrammarActive = false
      this.isCultureActive = false
      this.isVisualizerActive = false
      this.isSyllabusActive = false
      let i = 0
      if(this.wordPopup !== null){
        for (i = 0; i < this.wordPopup.length; i++) {
          $('.words-popup-active-' + i).css({'background': '#eee', 'color': '#1caceb'})
        }
      }
      if(this.sentenceSelectedListSuggestion !== null){
        for (i = 0; i < this.sentenceSelectedListSuggestion.length; i++) {
          $('.sentence-popup-active-' + i).css({'background': '#eee', 'color': '#1caceb'})
        }
      }
    },
    makePopupButtonActive(section, index){
      this.clearAllActive()
      if(section === 1){
        $('.words-popup-active-' + index).css({'background': '#1caceb', 'color': '#fff'})
      }else{
        $('.sentence-popup-active-' + index).css({'background': '#1caceb', 'color': '#fff'})
      }
    },
    retrieveSentence(index){
      if(this.content[index].audio !== null){
        let sentence = this.content[index]
        console.log('sentence', sentence)
        this.audioSelected.word = sentence.original
        this.audioSelected.translation = sentence.translation
        this.audioSelected.path = sentence.path
        this.audioSelected.audio = sentence.audio
        this.audioSelected.video_url = sentence.video_url
        this.audioSelected.id = sentence.id
        this.audioSelected.syllabus = sentence.syllabus
        this.audioSelected.syllabus_path = sentence.syllabus_path
        this.audioSelected.syllabus_audio = sentence.syllabus_audio
        this.audioSelected.accent_text = sentence.accent_text
        this.accentText = sentence.accent_text
        this.selectedContent.saved = 1
        this.content[index].saved = 1
        this.contentArray2D = null
        // this.pauseAllAudios()
        let parameter = {
          'column': 'content_id',
          'clause': '=',
          'value': this.audioSelected.id,
          'account_id': this.user.userID,
          'save_content': sentence.saved,
          'content_id': this.audioSelected.id,
          'lesson_id': this.selectedLessonId
        }
        $('#loading').css({display: 'block'})
        this.APIRequest('sentence_popup/dashboard', parameter).done(response => {
          $('#loading').css({display: 'none'})
          if(response.data !== null){
            this.sentenceSelectedListSuggestion = response.data
            this.listFlag = false
            this.pageActiveContent = false
            this.pageActiveAudio = true
            this.responsiveView.content = false
            this.responsiveView.audio = true
            this.contentArray2D = response.content_array
            // this.clearAllActive()
            // this.loadSentenceAudios()
            setTimeout(() => {
              this.initiateAudioIcon()
            }, 100)
          }else if(this.audioSelected.word !== null){
            this.sentenceSelectedListSuggestion = null
            this.pageActiveContent = false
            this.pageActiveAudio = true
            this.responsiveView.content = false
            this.responsiveView.audio = true
            this.listFlag = false
            this.contentArray2D = null
            setTimeout(() => {
              this.initiateAudioIcon()
            }, 100)
          }else{
            this.sentenceSelectedListSuggestion = null
            this.wordPopup = null
            this.listFlag = false
            this.contentArray2D = null
            this.pageActiveContent = true
            this.pageActiveAudio = false
            this.responsiveView.content = true
            this.responsiveView.audio = false
            this.accentText = null
            setTimeout(() => {
              this.initiateAudioIcon()
            }, 100)
          }
          AUTH.checkAuthentication(null)
        })
      }else{
        this.sentenceSelectedListSuggestion = null
        this.wordPopup = null
        this.listFlag = true
        this.contentArray2D = null
        this.pageActiveContent = true
        this.pageActiveAudio = false
        this.responsiveView.content = true
        this.responsiveView.audio = false
        this.audioSelected.word = null
        this.audioSelected.video_url = null
      }
    },
    testView(){
      if(this.$children){
        for (var i = 0; i < this.$children.length; i++) {
          if(this.$children[i].$el.id === 'testView'){
            this.$children[0].getLesson(this.selectedLessonId)
          }
        }
      }else{
        //
      }
    },
    updateEmailAddress(){
      if(this.emailAddress !== null || this.emailAddress !== ''){
        let parameter = {
          'id': this.user.userID,
          'email': this.emailAddress,
          'host': this.config.WEBHOST,
          'api': this.config.BACKEND_URL,
          'app': this.config.WEB_APP,
          'host_email': this.config.HOST_EMAIL,
          'app_title': this.config.WEBSITE_TITLE,
          'web': this.config.WEBSITE,
          'browser': this.config.BROWSER
        }
        this.APIRequest('accounts/update_email', parameter).then(response => {
          if(response.data === true){
            this.emailAddress = null
            this.updateEmailFlag = true
            this.errorMessage = 'Successfully Changed. Email Verification will be sent to your email address. Kindly wait for few minutes and check your email.'
          }else{
            this.errorMessage = response.error
            this.updateEmailFlag = false
          }
        })
      }else{
        this.updateEmailFlag = false
        this.errorMessage = 'Please Fillup the required informations.'
      }
    },
    resendVerification(){
      let parameter = {
        'condition': [{
          'column': 'id',
          'value': this.user.userID,
          'clause': '='
        }],
        'host': this.config.WEBHOST,
        'api': this.config.BACKEND_URL,
        'app': this.config.WEB_APP,
        'host_email': this.config.HOST_EMAIL,
        'app_title': this.config.WEBSITE_TITLE,
        'web': this.config.WEBSITE,
        'browser': this.config.BROWSER
      }
      this.APIRequest('accounts/resend_verification', parameter).then(response => {
        if(response.data.length > 0){
          this.verificationFlag = true
          this.verificationMessage = 'Validation email at ' + this.user.email + ' was Successfully Send!'
        }else{
          this.verificationFlag = false
          this.verificationMessage = 'Failed to resend an email verification for the following email account at ' + this.user.email + '!'
        }
      })
    },
    upgradeAccount(){
      let parameter = {
        'id': this.user.userID,
        'status': 'REGULAR'
      }
      this.APIRequest('accounts/update_status', parameter).then(response => {
        if(response.data === true){
          this.getAccountInfo()
          this.upgradeStatusFlag = true
          this.upgradeStatusMessage = 'Successfully Upgrade! Kindly Refresh this page to continue.'
        }else{
          this.upgradeStatusFlag = false
          this.upgradeStatusMessage = 'Failed to upgrade your account.'
        }
      })
    }
  }
}
</script>
